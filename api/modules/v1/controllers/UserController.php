<?php
namespace api\modules\v1\controllers;

use common\models\User;
use backend\models\AddUserForm;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\web\Controller;

class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'authenticator' => [
                'class' => HttpBearerAuth::class, //Authorization : Bearer xxxxxxxxxx
                //'class' => HttpBasicAuth::className(), //Authorization : Basic base64(user:password)
                //'class' => QueryParamAuth::className(), //as param like ?access-token=xxxxxxxx
                //'except' => ['index'],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Users list
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $provider = new ActiveDataProvider([
            'query' => User::find(),
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                    //'title' => SORT_ASC,
                ]
            ],
        ]);

        return $provider->getModels();
    }
}
