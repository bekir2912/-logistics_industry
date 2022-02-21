<?php

use yii\db\Migration;

/**
 * Class m210820_212305_notice_shop_stock
 */
class m210820_212305_notice_shop_stock extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('notice_shop_stock', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'description' => $this->text(),
            'status' => $this->integer()->notNull()->defaultValue(0),
            'sort' => $this->integer()->notNull()->defaultValue(0),
            'date' => $this->timestamp()
        ]);

        $this->createIndex('id', 'notice_shop_stock', 'id', true);
        $this->addforeignKey('notice_shop_stock_u_fk', 'notice_shop_stock', 'user_id', 'user', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210820_212305_notice_shop_stock cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210820_212305_notice_shop_stock cannot be reverted.\n";

        return false;
    }
    */
}
