<?php

use yii\db\Migration;

/**
 * Class m210712_122504_shop_product
 */
class m210712_122504_shop_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('shop_product', [
            'id' => $this->primaryKey(),
            'shop_id' => $this->integer(),
            'product_id' => $this->integer(),
            'amount' => $this->double(),
            'status' => $this->integer()->notNull()->defaultValue(1),
            'sort' => $this->integer()->notNull()->defaultValue(0),
            'date' => $this->timestamp(),
        ]);

        $this->addForeignKey('shop_product_s_fk', 'shop_product', 'shop_id', 'shop', 'id', 'CASCADE');
        $this->addForeignKey('shop_product_p_fk', 'shop_product', 'product_id', 'product', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210712_122504_shop_product cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210712_122504_shop_product cannot be reverted.\n";

        return false;
    }
    */
}
