<?php

use yii\db\Migration;

/**
 * Class m210712_091559_shop_stack_shelving
 */
class m210712_091559_shop_stack_shelving extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('shop_stack_shelving', [
            'id' => $this->primaryKey(),
            'shop_stack_id' => $this->integer(),
            'row' => $this->string(),
            'cell' => $this->string(),
            'status' => $this->integer()->notNull()->defaultValue(0),
            'sort' => $this->integer()->notNull()->defaultValue(0),
            'date' => $this->timestamp(),
            'ip' => $this->string()
        ]);

        $this->addForeignKey('shop_stack_shelving_s_fk', 'shop_stack_shelving', 'shop_stack_id', 'shop_stack', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210712_091559_shop_stack_shelving cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210712_091559_shop_stack_shelving cannot be reverted.\n";

        return false;
    }
    */
}
