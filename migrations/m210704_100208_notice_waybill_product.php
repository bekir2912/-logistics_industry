<?php

use yii\db\Migration;

/**
 * Class m210704_100208_notice_waybill_product
 */
class m210704_100208_notice_waybill_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('notice_waybill_product', [
            'id' => $this->primaryKey(),
            'notice_waybill_id' => $this->integer(),
            'product_id' => $this->integer(),
            'amount' => $this->double(),
            'description' => $this->text(),
            'date' => $this->timestamp(),
            'sort' => $this->integer()->notNull()->defaultValue(0),
            'status' => $this->integer()->notNull()->defaultValue(0)
        ]);

        $this->addForeignKey('notice_waybill_product_n_fk', 'notice_waybill_product', 'notice_waybill_id', 'notice_waybill', 'id', 'CASCADE');
        $this->addForeignKey('notice_waybill_product_p_fk', 'notice_waybill_product', 'product_id', 'product', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210704_100208_notice_waybill_product cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210704_100208_notice_waybill_product cannot be reverted.\n";

        return false;
    }
    */
}
