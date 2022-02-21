<?php

use yii\db\Migration;

/**
 * Class m210704_085203_notice_truck
 */
class m210704_085203_notice_truck extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('notice_truck', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'notice_waybill_id' => $this->integer(),
            'notice_number' => $this->string(),
            'description' => $this->text(),
            'date' => $this->timestamp(),
            'sort' => $this->integer()->notNull()->defaultValue(0),
            'status' => $this->integer()->notNull()->defaultValue(0)
        ]);

        $this->createIndex('id', 'notice_truck', 'id', true);
        $this->addForeignKey('notice_truck_u_fk', 'notice_truck', 'user_id', 'user', 'id', 'CASCADE');
        $this->addForeignKey('notice_truck_n_fk', 'notice_truck', 'notice_waybill_id', 'notice_waybill', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210704_085203_notice_truck cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210704_085203_notice_truck cannot be reverted.\n";

        return false;
    }
    */
}
