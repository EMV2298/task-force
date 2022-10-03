<?php

use yii\db\Migration;

/**
 * Class m221002_231747_add_column_isExecutor
 */
class m221002_231747_add_column_isExecutor extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('users', 'is_executor', 'BOOLEAN');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('users', 'is_executor');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221002_231747_add_column_isExecutor cannot be reverted.\n";

        return false;
    }
    */
}
