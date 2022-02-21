<?php

use yii\db\Migration;

/**
 * Class m210712_073327_gp
 */
class m210712_073327_gp extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('gp', [
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
            'status' => $this->integer()->notNull()->defaultValue(1),
            'sort' => $this->integer()->notNull()->defaultValue(0),
            'date' => $this->timestamp(),
            'ip' => $this->string()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210712_073327_gp cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210712_073327_gp cannot be reverted.\n";

        return false;
    }
    */
}
