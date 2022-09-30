<?php

use app\models\Users;
use yii\db\Migration;

/**
 * Class m220929_230924_create_column_rank
 */
class m220929_230924_create_column_rank extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('users', 'rating', 'varchar(10)');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('users', 'rating');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220929_230924_create_column_rank cannot be reverted.\n";

        return false;
    }
    */
}
