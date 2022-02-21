<?php

use yii\db\Migration;

/**
 * Class m201228_162146_stack_shelving
 */
class m201228_162146_stack_shelving extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('stack_shelving', [
            'id' => $this->primaryKey(),
            'stack_id' => $this->integer(),
            'shelf_number' => $this->string(),
            'status' => $this->integer()->notNull()->defaultValue(1),
            'sort' => $this->integer()->notNull()->defaultValue(0),
            'date' => $this->timestamp()
        ]);

        $this->addForeignKey('stack_shelving_fk', 'stack_shelving', 'stack_id', 'stack', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201228_162146_stack_shelving cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201228_162146_stack_shelving cannot be reverted.\n";

        return false;
    }
    */
}
