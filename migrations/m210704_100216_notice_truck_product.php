<?php

use yii\db\Migration;

/**
 * Class m210704_100216_notice_truck_product
 */
class m210704_100216_notice_truck_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('notice_truck_product', [
            'id' => $this->primaryKey(),
            'notice_truck_id' => $this->integer(),
            'product_id' => $this->integer(),
            'amount' => $this->double(),
            'description' => $this->text(),
            'date' => $this->timestamp(),
            'sort' => $this->integer()->notNull()->defaultValue(0),
            'status' => $this->integer()->notNull()->defaultValue(0)
        ]);

        $this->addForeignKey('notice_truck_product_n_fk', 'notice_truck_product', 'notice_truck_id', 'notice_truck', 'id', 'CASCADE');
        $this->addForeignKey('notice_truck_product_p_fk', 'notice_truck_product', 'product_id', 'product', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210704_100216_notice_truck_product cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210704_100216_notice_truck_product cannot be reverted.\n";

        return false;
    }
    */
}
