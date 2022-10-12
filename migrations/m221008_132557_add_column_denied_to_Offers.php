<?php

use yii\db\Migration;

/**
 * Class m221008_132557_add_column_denied_to_Offers
 */
class m221008_132557_add_column_denied_to_Offers extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('offers', 'denied', 'BOOLEAN');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('offers', 'denied');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221008_132557_add_column_denied_to_Offers cannot be reverted.\n";

        return false;
    }
    */
}
