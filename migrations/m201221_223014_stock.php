<?php

use yii\db\Migration;

/**
 * Class m201221_223014_stock
 */
class m201221_223014_stock extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('stock', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'name_ru' => $this->string(),
            'name_en' => $this->string(),
            'name_uz' => $this->string(),
            'description_ru' => $this->text(),
            'description_en' => $this->text(),
            'description_uz' => $this->text(),
            'address' => $this->string(),
            'code' => $this->string(),
            'lat' => $this->string(),
            'lng' => $this->string(),
            'status' => $this->integer()->notNull()->defaultValue(0),
            'sort' => $this->integer()->notNull()->defaultValue(0),
            'date' => $this->timestamp(),
            'ip' => $this->string()
        ]);

        $this->createIndex('id', 'stock', 'id', true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201221_223014_stock cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201221_223014_stock cannot be reverted.\n";

        return false;
    }
    */
}
