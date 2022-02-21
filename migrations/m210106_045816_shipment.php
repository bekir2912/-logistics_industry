<?php

use yii\db\Migration;

/**
 * Class m210106_045816_shipment
 */
class m210106_045816_shipment extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('shipment', [
            'id' => $this->primaryKey(),
            'date_shipment' => $this->string(),
            'fio' => $this->string(),
            'status' => $this->integer()->notNull()->defaultValue(1),
            'sort' => $this->integer()->notNull()->defaultValue(0),
            'date' => $this->timestamp(),
        ]);

        $this->createIndex('id', 'shipment', 'id', true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210106_045816_shipment cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210106_045816_shipment cannot be reverted.\n";

        return false;
    }
    */
}
