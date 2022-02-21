<?php

use yii\db\Migration;

/**
 * Class m201228_162128_stack
 */
class m201228_162128_stack extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('stack', [
            'id' => $this->primaryKey(),
            'stock_id' => $this->integer(),
            'stack_number' => $this->string(),
            'shelfs_count' => $this->string(),
            'status' => $this->integer()->notNull()->defaultValue(1),
            'sort' => $this->integer()->notNull()->defaultValue(0),
            'date' => $this->timestamp(),
        ]);

        $this->createIndex('id', 'stack', 'id', true);
        $this->addForeignKey('stack_stock_fk', 'stack', 'stock_id', 'stock', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201228_162128_stack cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201228_162128_stack cannot be reverted.\n";

        return false;
    }
    */
}
