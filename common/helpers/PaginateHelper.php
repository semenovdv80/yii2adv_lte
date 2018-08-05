<?php
namespace common\helpers;

use Yii;
use yii\data\Pagination;

class PaginateHelper
{
    /**
     * @param $query
     * @param $request
     * @return mixed
     */
    public static function paginate($query, $request)
    {
        $totalCount = $query->count();
        $pagination = new Pagination(['totalCount' => $totalCount]);
        $pagination->pageSizeParam = 'per_page';
        $pagination->pageSize = $request['per_page'] ?? 25;

        $records = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        Yii::$app->view->params['pagination'] = $pagination;
        Yii::$app->view->params['totalCount'] = $query->count();
        Yii::$app->view->params['itemFrom'] = $pagination->offset +1;
        Yii::$app->view->params['itemTo'] = $pagination->page +1 < $pagination->pageCount ?
            $pagination->offset +$pagination->pageSize :
            $pagination->totalCount;

        return $records;
    }
}