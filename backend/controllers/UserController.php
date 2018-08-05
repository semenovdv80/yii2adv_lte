<?php
namespace backend\controllers;

use common\models\User;
use backend\models\AddUserForm;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

class UserController extends Controller
{
    public $layout=false;
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Display list of users.
     *
     * @return string
     */
    public function actionList()
    {
        $breadcrumbs[] = ['label' => 'Админ панель', 'url' => ['/admin']];
        $breadcrumbs[] = ['label' => 'Список пользователей', 'url' => ['/admin/user/list'], 'class' => 'active'];

        $users = User::getList();
        return $this->render('/admin/user/list.twig', [
            'breadcrumbs' => $breadcrumbs,
            'users' => $users
        ]);
    }

    /**
     * Add new user
     *
     * @return string
     */
    public function actionAdd()
    {
        $breadcrumbs[] = ['label' => 'Админ панель', 'url' => ['/admin']];
        $breadcrumbs[] = ['label' => 'Добавить пользователя', 'url' => ['/admin/user/add'], 'class' => 'active'];

        $model = new AddUserForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->addUser()) {
                return $this->redirect(['/admin/user/list']);
            }
        }

        return $this->render('/admin/user/add.twig', [
            'breadcrumbs' => $breadcrumbs,
            'model' => $model,
        ]);
    }
}
