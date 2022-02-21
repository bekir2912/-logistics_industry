<?php

use yii\db\Migration;

/**
 * Class m210712_091553_shop_stack
 */
class m210712_091553_shop_stack extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('shop_stack', [
            'id' => $this->primaryKey(),
            'shop_id' => $this->integer(),
            'stack_number' => $this->string(),
            'shelfs_count' => $this->string(),
            'status' => $this->integer()->notNull()->defaultValue(1),
            'sort' => $this->integer()->notNull()->defaultValue(0),
            'date' => $this->timestamp(),
        ]);

        $this->createIndex('id', 'shop_stack', 'id', true);
        $this->addForeignKey('shop_stack_s_fk', 'shop_stack', 'shop_id', 'shop', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210712_091553_shop_stack cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210712_091553_shop_stack cannot be reverted.\n";

        return false;
    }
    */
}
