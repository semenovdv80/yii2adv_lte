<?php
namespace common\models;

use paulzi\nestedsets\NestedSetsQueryTrait;

class CategoryQuery extends \yii\db\ActiveQuery
{
    use NestedSetsQueryTrait;

    /**
     * Get whole tree hierarhy
     *
     * @return null|static
     */
    public function getTree()
    {
        $root = Category::findOne(['id' =>1]);
        $this->addChildren($root);
        return $root;
    }

    /**
     * Include children to node
     *
     * @param $node
     */
    public function addChildren($node)
    {
        $children = [];
        $nodeChildren = $node->getChildren()->all();
        if (!empty($nodeChildren)) {
            foreach ($nodeChildren as $nodeChild) {
                $this->addChildren($nodeChild);
                $children[] = $nodeChild;
            }
            $node->children = $children;
        }
    }
}