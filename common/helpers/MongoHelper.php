<?php
namespace common\helpers;

use Yii;
use yii\data\Pagination;

class MongoHelper
{
    /**
     * Autoincrement counter
     *
     * @param $name
     * @return int
     */
    public static function autoInc($name)
    {
        $collection = Yii::$app->mongodb->getCollection('counters');
        $result =  $collection->findAndModify(
            ['_id' => $name],
            ['$inc' => ['seq' => 1]],
            ['seq' => true],
            ['new' => true, 'upsert' => true]
        );

        return (string) $result['seq'] ?? 1;
    }
}