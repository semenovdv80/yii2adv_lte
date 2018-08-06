<?php
namespace common\models;

use paulzi\nestedsets\NestedSetsQueryTrait;

class CategoryQuery extends \yii\db\ActiveQuery
{
    use NestedSetsQueryTrait;
}