<?php
namespace common\helpers;

class PaginateHelper
{
    /**
     * Paginator extension
     *
     * @param $pagination
     * @param $params
     * @return array
     */
    public static function extend($pagination, $params)
    {
        $pagination->params['orderCol'] = $params['orderCol'];
        $pagination->params['orderDir'] = $params['orderDir'];

        return [
            'pagination' => $pagination,
            'fromItem' => !empty($pagination->totalCount) ? $pagination->offset + 1 : 0,
            'toItem' => ($pagination->page + 1 < $pagination->pageCount) ?
                $pagination->offset + $pagination->pageSize :
                $pagination->totalCount
            ];
    }
}