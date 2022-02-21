<?php

use yii\db\Migration;

/**
 * Class m210704_100238_notice_act_product
 */
class m210704_100238_notice_act_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('notice_act_product', [
            'id' => $this->primaryKey(),
            'notice_act_id' => $this->integer(),
            'product_id' => $this->integer(),
            'amount' => $this->double(),
            'percentage' => $this->double(),
            'amount_passed' => $this->double(),
            'description' => $this->text(),
            'date' => $this->timestamp(),
            'sort' => $this->integer()->notNull()->defaultValue(0),
            'status' => $this->integer()->notNull()->defaultValue(0)
        ]);

        $this->addForeignKey('notice_act_product_n_fk', 'notice_act_product', 'notice_act_id', 'notice_act', 'id', 'CASCADE');
        $this->addForeignKey('notice_act_product_p_fk', 'notice_act_product', 'product_id', 'product', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210704_100238_notice_act_product cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210704_100238_notice_act_product cannot be reverted.\n";

        return false;
    }
    */
}
