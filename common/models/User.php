<?php
namespace common\models;

use common\helpers\PaginateHelper;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\mongodb\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    /**
     * Collection name
     *
     * @return array|string
     */
    public static function collectionName()
    {
        return 'user';
    }

    /**
     * MOdel attributes
     *
     * @return array list of attribute names.
     */
    public function attributes()
    {
        return [
            '_id',
            'username',
            'auth_key',
            'password_hash',
            'email',
            'password_reset_token',
            'access_token',
            'status',
            'created_at',
            'updated_at'
        ];
    }

    /**
     * Return only selected fields
     *
     * @return array|false
     */
    public function fields()
    {
        $fields = parent::fields();
        unset($fields['password_hash'], $fields['access_token'],$fields['auth_key']);

        return $fields;
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['_id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return (string) $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Get assigned roles
     *
     * @return \yii\rbac\Assignment[]
     */
    public function getRoles()
    {
        return Yii::$app->authManager->getAssignments($this->id);
    }

    /**
     * Get roles list
     *
     * @return string
     */
    public function getRolesList()
    {
        $assignments = $this->roles ?? [];
        foreach($assignments as $role) {
            $roles[] = $role->roleName;
        }
        return implode(',', $roles ?? []);
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param $password
     * @throws \yii\base\Exception
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * Generate new access_token
     *
     * @param bool $insert
     * @return bool
     * @throws \yii\base\Exception
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->access_token = \Yii::$app->security->generateRandomString();
            }
            return true;
        }
        return false;
    }

    /**
     * List of users
     * @return mixed
     */
    public static function getList()
    {
        $request = Yii::$app->request->get();
        $orderCol = Yii::$app->view->params['orderCol'] = self::orderColumn();
        $orderType = Yii::$app->view->params['orderType'] = $_COOKIE['orderType'] ?? 'desc';

        $query = self::find();

        /*
        $filterBy = [];
        if (!empty($request['id'])) {
            $filterBy['id'] = $request['id'];
        }
        if (!empty($request['username'])) {
            $filterBy['username'] = $request['username'];
        }
        $query->filterWhere($filterBy);
        */

        if (!empty($request['q'])) {
            $query->where(['like', 'id', $request['q']])
                ->orWhere(['like', 'username', $request['q']]);
        }

        $query->orderBy("$orderCol $orderType");

        $records  = PaginateHelper::paginate($query, $request);

        return $records;
    }

    public static function orderColumn()
    {
        $aColumns = self::attributes();
        $orderCol = $_COOKIE['orderCol'] ?? 'id';
        $orderCol = in_array($orderCol, $aColumns) ? $orderCol : 'id';
        return $orderCol;
    }
}
