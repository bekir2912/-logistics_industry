<?php

use yii\db\Migration;

/**
 * Class m190826_042727_sms_code
 */
class m190826_042727_sms_code extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('sms_code', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'phone' => $this->string(),
            'code' => $this->string(),
            'sms_expire' => $this->integer(),
            'date' => $this->timestamp()
        ]);

        //$this->addForeignKey('sms_code_user_fk', 'sms_code', 'user_id', 'user', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190826_042727_sms_code cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190826_042727_sms_code cannot be reverted.\n";

        return false;
    }
    */
}
