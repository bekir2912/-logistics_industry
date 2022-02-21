<?php

use yii\db\Migration;

/**
 * Class m210704_085219_notice_control
 */
class m210704_085219_notice_control extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('notice_control', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'notice_truck_id' => $this->integer(),
            'date_notice' => $this->string(),
            'description' => $this->text(),
            'date' => $this->timestamp(),
            'sort' => $this->integer()->notNull()->defaultValue(0),
            'status' => $this->integer()->notNull()->defaultValue(0)
        ]);

        $this->createIndex('id', 'notice_control', 'id', true);
        $this->addForeignKey('notice_control_u_fk', 'notice_control', 'user_id', 'user', 'id', 'CASCADE');
        $this->addForeignKey('notice_control_n_fk', 'notice_control', 'notice_truck_id', 'notice_truck', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210704_085219_notice_control cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210704_085219_notice_control cannot be reverted.\n";

        return false;
    }
    */
}
