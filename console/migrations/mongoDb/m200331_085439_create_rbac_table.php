<?php

class m200331_085439_create_rbac_table extends \yii\mongodb\Migration
{
    public function up()
    {
        $this->insert('auth_assignment', [
            'item_name' => 'admin',
            'user_id' => '1',
            'created_at' => time(),
        ]);

        $this->batchInsert('auth_item', [
            [
                'name' => 'admin',
                'type' => 1,
                'description' => null,
                'rule_name' => null,
                'data' => null,
                'created_at' => time(),
                'updated_at' => time(),
            ],
            [
                'name' => 'user',
                'type' => 1,
                'description' => null,
                'rule_name' => null,
                'data' => null,
                'created_at' => time(),
                'updated_at' => time(),
            ],
        ]);

        $this->createCollection('auth_rule');
    }

    public function down()
    {
        $this->dropCollection('auth_assignment');
        $this->dropCollection('auth_item');
        $this->dropCollection('auth_rule');
    }
}
