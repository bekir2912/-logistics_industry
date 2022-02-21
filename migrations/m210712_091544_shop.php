<?php

use yii\db\Migration;

/**
 * Class m210712_091544_shop
 */
class m210712_091544_shop extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('shop', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'name_ru' => $this->string(),
            'description_ru' => $this->string(),
            'status' => $this->integer()->notNull()->defaultValue(1),
            'sort' => $this->integer()->notNull()->defaultValue(0),
            'date' => $this->timestamp()
        ]);

        $this->createIndex('id', 'shop', 'id', true);
        $this->addForeignKey('shop_user_fk', 'shop', 'user_id', 'user', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210712_091544_shop cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210712_091544_shop cannot be reverted.\n";

        return false;
    }
    */
}
