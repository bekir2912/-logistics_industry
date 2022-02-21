<?php

use yii\db\Migration;

/**
 * Class m210704_085225_notice_act
 */
class m210704_085225_notice_act extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('notice_act', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'notice_control_id' => $this->integer(),
            'date_notice' => $this->string(),
            'description' => $this->text(),
            'date' => $this->timestamp(),
            'sort' => $this->integer()->notNull()->defaultValue(0),
            'status' => $this->integer()->notNull()->defaultValue(0)
        ]);

        $this->createIndex('id', 'notice_act', 'id', true);
        $this->addForeignKey('notice_act_u_fk', 'notice_act', 'user_id', 'user', 'id', 'CASCADE');
        $this->addForeignKey('notice_act_n_fk', 'notice_act', 'notice_control_id', 'notice_control', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210704_085225_notice_act cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210704_085225_notice_act cannot be reverted.\n";

        return false;
    }
    */
}
