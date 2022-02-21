<?php

use yii\db\Migration;

/**
 * Class m201221_223211_product
 */
class m201221_223211_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('product', [
            'id' => $this->primaryKey(),
            'stock_id' => $this->integer(),
            'name_ru' => $this->string(),
            'name_en' => $this->string(),
            'name_uz' => $this->string(),
            'description_ru' => $this->text(),
            'description_en' => $this->text(),
            'description_uz' => $this->text(),
            'article' => $this->string(),
            'model' => $this->string(),
            'price_buy' => $this->double(),
            'price_sale' => $this->double(),
            'amount' => $this->integer(),
            'manufacturer' => $this->string(),
            'qr' => $this->string(),
            'status' => $this->integer()->notNull()->defaultValue(0),
            'sort' => $this->integer()->notNull()->defaultValue(0),
            'date' => $this->timestamp(),
            'ip' => $this->string()
        ]);

        $this->createIndex('id', 'product', 'id', true);
        $this->addForeignKey('product_stock_fk', 'product', 'stock_id', 'stock', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201221_223211_product cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201221_223211_product cannot be reverted.\n";

        return false;
    }
    */
}
