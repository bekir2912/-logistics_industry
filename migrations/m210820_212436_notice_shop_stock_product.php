<?php

use yii\db\Migration;

/**
 * Class m210820_212436_notice_shop_stock_product
 */
class m210820_212436_notice_shop_stock_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('notice_shop_stock_product', [
            'id' => $this->primaryKey(),
            'notice_shop_stock_id' => $this->integer(),
            'product_id' => $this->integer(),
            'article' => $this->string(),
            'amount' => $this->double(),
            'status' => $this->integer()->notNull()->defaultValue(0),
            'sort' => $this->integer()->notNull()->defaultValue(0),
            'date' => $this->timestamp()
        ]);

        $this->addforeignKey('notice_shop_stock_product_n_fk', 'notice_shop_stock_product', 'notice_shop_stock_id', 'notice_shop_stock', 'id', 'CASCADE');
        $this->addforeignKey('notice_shop_stock_p_fk', 'notice_shop_stock_product', 'product_id', 'product', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210820_212436_notice_shop_stock_product cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210820_212436_notice_shop_stock_product cannot be reverted.\n";

        return false;
    }
    */
}
