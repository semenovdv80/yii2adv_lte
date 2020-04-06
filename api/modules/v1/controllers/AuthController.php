<?php
namespace api\modules\v1\controllers;

use api\modules\v1\models\SignupForm;
use common\models\User;
use backend\models\AddUserForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;
use yii\web\Controller;
use yii\web\HttpException;

class AuthController extends Controller
{
    public $enableCsrfValidation = false;


    /**
     * Sign up
     *
     * @return \yii\web\Response
     * @throws \yii\web\HttpException
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        $params = Yii::$app->request->post();
        $model->load($params);
        if ($model->validate()) {
            if ($user = $model->signup()) {
                return $user;
            }
        } else {
            $errors = json_encode($model->errors, JSON_UNESCAPED_UNICODE);
            throw new HttpException(400, $errors);
        }




//        $model->username = $params['username'];
//        $model->password=$params['password'];
//        $model->email=$params['email'];
//
//        if ($model->signup()) {
//            $response['isSuccess'] = 201;
//            $response['message'] = 'You are now a member!';
//            $response['user'] =\common\models\User::findByUsername($model->username);
//            return $response;
//        }
//        else {
//            //$model->validate();
//            $model->getErrors();
//            $response['hasErrors'] = $model->hasErrors();
//            $response['errors'] = $model->getErrors();
//            //return = $model;
//            return $response;
//
//        }
    }
}
