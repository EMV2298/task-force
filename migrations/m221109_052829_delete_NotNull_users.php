<?php

use yii\db\Migration;

/**
 * Class m221109_052829_delete_NotNull_users
 */
class m221109_052829_delete_NotNull_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('users', 'dob', 'date NULL');
        $this->alterColumn('users', 'phonenumber', 'varchar(128) NULL');
        $this->alterColumn('users', 'status', 'BOOLEAN NULL');
        $this->alterColumn('users', 'show_contacts', 'BOOLEAN NULL');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221109_052829_delete_NotNull_users cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221109_052829_delete_NotNull_users cannot be reverted.\n";

        return false;
    }
    */
}
