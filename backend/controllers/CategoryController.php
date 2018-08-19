<?php
namespace backend\controllers;

use common\models\Category;
use common\models\User;
use backend\models\AddUserForm;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

class CategoryController extends Controller
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
     * Display list of categories.
     *
     * @return string
     */
    public function actionList()
    {
        $pageTitle = Yii::t('app', 'List of categories');

        $breadcrumbs = [
            ['url' => ['/admin'], 'label' => Yii::t('app', 'Admin panel'), ],
            ['url' => ['/admin/category/list'], 'label' => $pageTitle,  'class' => 'active']
        ];

        return $this->render('/admin/category/list.twig', [
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => $pageTitle,
        ]);
    }

    /**
     * Display whole tree hierarhy
     *
     * @return null|static
     */
    public function actionGettree()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return Category::find()->getTree();
    }

    /**
     * Move node on tree
     * 
     * @return bool
     */
    public function actionSettree()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $params = Yii::$app->request->bodyParams;
        if (!empty($params['node_id']) && !empty($params['parent_id'])) {
            $node = Category::findOne($params['node_id']);
            $parent = Category::findOne($params['parent_id']);
            $node->appendTo($parent)->save(); // move existing node
        }
        return true;
    }
}
