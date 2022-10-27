<?php

use yii\db\Migration;

/**
 * Class m221023_203026_Add_column_lat_lng_address_to_tasks
 */
class m221023_203026_Add_column_lat_lng_address_to_tasks extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('tasks', 'lat', 'varchar(15)');
        $this->addColumn('tasks', 'long', 'varchar(15)');
        $this->addColumn('tasks', 'address', 'varchar(255)');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221023_203026_Add_column_lat_lng_address_to_tasks cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221023_203026_Add_column_lat_lng_address_to_tasks cannot be reverted.\n";

        return false;
    }
    */
}
