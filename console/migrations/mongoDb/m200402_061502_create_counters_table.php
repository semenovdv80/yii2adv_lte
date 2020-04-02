<?php

class m200402_061502_create_counters_table extends \yii\mongodb\Migration
{
    private $collection = 'counters';

    public function up()
    {
        $this->createCollection($this->collection);
        $this->insert($this->collection, [
            '_id' => 'user_id',
            'seq' => 1,
        ]);
    }

    public function down()
    {
        $this->dropCollection($this->collection);
    }
}
