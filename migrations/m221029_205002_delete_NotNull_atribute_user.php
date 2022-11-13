<?php

use yii\db\Migration;

/**
 * Class m221029_205002_delete_NotNull_atribute_user
 */
class m221029_205002_delete_NotNull_atribute_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('users', 'password', 'varchar(128) NULL');
        $this->addColumn('users', 'vk_id', 'varchar(128) NULL');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221029_205002_delete_NotNull_atribute_user cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221029_205002_delete_NotNull_atribute_user cannot be reverted.\n";

        return false;
    }
    */
}
