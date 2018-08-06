<?php
namespace common\models;

use paulzi\nestedsets\NestedSetsBehavior;
use yii\db\ActiveRecord;

class Category extends ActiveRecord
{
    public function behaviors() {
        return [
            [
                'class' => NestedSetsBehavior::className(),
                // 'treeAttribute' => 'tree',
            ],
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new CategoryQuery(get_called_class());
    }
}