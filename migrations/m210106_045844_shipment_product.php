<?php

use yii\db\Migration;

/**
 * Class m210106_045844_shipment_product
 */
class m210106_045844_shipment_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('shipment_product', [
            'id' => $this->primaryKey(),
            'type_id' => $this->integer(),
            'shipment_id' => $this->integer(),
            'product_id' => $this->integer(),
            'article' => $this->string(),
            'qr' => $this->string(),
            'amount' => $this->integer(),
            'status' => $this->integer()->notNull()->defaultValue(1),
            'sort' => $this->integer()->notNull()->defaultValue(0),
            'date' => $this->timestamp(),
        ]);

        $this->addForeignKey('shipment_product_c_fk', 'shipment_product', 'shipment_id', 'shipment', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210106_045844_shipment_product cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210106_045844_shipment_product cannot be reverted.\n";

        return false;
    }
    */
}
