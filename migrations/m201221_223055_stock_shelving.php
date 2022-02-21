<?php

use yii\db\Migration;

/**
 * Class m201221_223055_stock_shelving
 */
class m201221_223055_stock_shelving extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('stock_shelving', [
            'id' => $this->primaryKey(),
            'stock_id' => $this->integer(),
            'row' => $this->string(),
            'cell' => $this->string(),
            'status' => $this->integer()->notNull()->defaultValue(0),
            'sort' => $this->integer()->notNull()->defaultValue(0),
            'date' => $this->timestamp(),
            'ip' => $this->string()
        ]);

        $this->addForeignKey('stock_shelving_s_fk', 'stock_shelving', 'stock_id', 'stock', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201221_223055_stock_shelving cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201221_223055_stock_shelving cannot be reverted.\n";

        return false;
    }
    */
}
