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
        $pageTitle = Yii::t('app', 'List of users');
        Yii::$app->view->params['pageTitle'] = $pageTitle;

        $breadcrumbs[] = ['label' => Yii::t('app', 'Admin panel'), 'url' => ['/admin']];
        $breadcrumbs[] = ['label' => $pageTitle, 'url' => ['/admin/user/list'], 'class' => 'active'];

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
        $pageTitle = Yii::t('app', 'Adding user');
        Yii::$app->view->params['pageTitle'] = $pageTitle;

        $breadcrumbs[] = ['label' => Yii::t('app', 'Admin panel'), 'url' => ['/admin']];
        $breadcrumbs[] = ['label' => $pageTitle, 'url' => ['/admin/user/add'], 'class' => 'active'];

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
