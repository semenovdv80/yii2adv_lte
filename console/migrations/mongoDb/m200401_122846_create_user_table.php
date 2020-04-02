<?php

class m200401_122846_create_user_table extends \yii\mongodb\Migration
{
    private $collection = 'user';

    public function up()
    {
        $this->createCollection($this->collection);
        $this->createIndex($this->collection, '_id');

//        $this->insert($this->collection, [
//            '_id' => 'userid',
//            'username' => 'admin',
//            'auth_key' => 'hlD6-u4raGiT0iuTja650xF82YYJ6lfP',
//            'password_hash' => '$2y$13$AQGvigkiqVjC0IWjBniZDueM6kU8w7MIWhcRVafnt4KaHBK0pIDXW',
//            'password_reset_token' => '',
//            'email' => 'admin@admin.com',
//            'status' => 10,
//            'created_at' => time(),
//            'updated_at' => time(),
//        ]);
    }

    public function down()
    {
        $this->dropCollection($this->collection);
    }
}
