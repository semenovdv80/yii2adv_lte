<?php
namespace backend\controllers;

use common\models\User;
use backend\models\AddUserForm;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
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
    public function actionIndex()
    {
        $request = Yii::$app->request;

        $params = ArrayHelper::merge($request->get(), [
            'perPage' => $request->get('perPage', Yii::$app->params['per_pages'][0]),
            'orderCol' => $request->get('orderCol', 'id'),
            'orderDir' => $request->get('orderDir', 'asc')
        ]);

        $pageTitle = Yii::t('app', 'List of users');

        $breadcrumbs = [
            ['url' => ['/admin'], 'label' => Yii::t('app', 'Admin panel'), ],
            ['url' => ['/admin/user/list'], 'label' => $pageTitle,  'class' => 'active']
        ];

        $data = User::getList($params);

        return $this->render('/admin/user/list.twig', [
            'params' => $params,
            'pages' => $data['pages'],
            'users' => $data['models'],
            'pageTitle' => $pageTitle,
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    /**
     * View user's data
     *
     * @return string
     */
    public function actionView()
    {
        $userId = Yii::$app->request->get('id');
        $user = User::findOne($userId);

        $pageTitle = Yii::t('app', 'User info');

        $breadcrumbs = [
            ['url' => ['/admin'], 'label' => Yii::t('app', 'Admin panel'), ],
            ['label' => $pageTitle, 'url' => ['/admin/user/'.$userId], 'class' => 'active']
        ];

        return $this->render('/admin/user/view.twig', [
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => $pageTitle,
            'model' => $user,
        ]);
    }

    /**
     * Create new user
     *
     * @return string
     */
    public function actionCreate()
    {
        $pageTitle = Yii::t('app', 'Adding user');

        $breadcrumbs = [
            ['url' => ['/admin'], 'label' => Yii::t('app', 'Admin panel'), ],
            ['label' => $pageTitle, 'url' => ['/admin/user/add'], 'class' => 'active']
        ];

        $model = new AddUserForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->addUser()) {
                return $this->redirect(['/admin/users']);
            }
        }

        return $this->render('/admin/user/add.twig', [
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => $pageTitle,
            'model' => $model,
        ]);
    }
}
