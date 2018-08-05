<?php
namespace backend\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class AddUserForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $addButton;
    public $labelUsername;
    public $labelPassword;

    /**
     * AddUserForm constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->addButton = Yii::t('app', 'Add user');
        $this->labelUsername = Yii::t('app', 'Username');
        $this->labelPassword = Yii::t('app', 'Password');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            [
                'email',
                'unique',
                'targetClass' => '\common\models\User',
                'message' => Yii::t('app', 'This email address has already been taken.')
            ],

            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Add new user.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function addUser()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        $user->email = $this->email;
        $user->username = $this->username;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        
        return $user->save() ? $user : null;
    }
}
