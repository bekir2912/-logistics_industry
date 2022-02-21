<?php

use yii\db\Migration;

/**
 * Class m210704_085126_notice_waybill
 */
class m210704_085126_notice_waybill extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('notice_waybill', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'date_notice' => $this->string(),
            'truck_number' => $this->string(),
            'truck_number_reg' => $this->string(),
            'invoice_number' => $this->string(),
            'provider_id' => $this->integer(),
            'article' => $this->string(),
            'description' => $this->text(),
            'date' => $this->timestamp(),
            'sort' => $this->integer()->notNull()->defaultValue(0),
            'status' => $this->integer()->notNull()->defaultValue(0)
        ]);

        $this->createIndex('id', 'notice_waybill', 'id', true);
        $this->addForeignKey('notice_waybill_u_fk', 'notice_waybill', 'user_id', 'user', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210704_085126_notice_waybill cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210704_085126_notice_waybill cannot be reverted.\n";

        return false;
    }
    */
}
