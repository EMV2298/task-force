<?php

use yii\db\Migration;

/**
 * Class m221112_133234_add_null_to_price_OFFER
 */
class m221112_133234_add_null_to_price_OFFER extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('offers', 'price', 'int NULL');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221112_133234_add_null_to_price_OFFER cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221112_133234_add_null_to_price_OFFER cannot be reverted.\n";

        return false;
    }
    */
}
