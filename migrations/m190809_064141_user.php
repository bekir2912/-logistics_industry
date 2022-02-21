<?php

use yii\db\Migration;

/**
 * Class m190809_064141_user
 */
class m190809_064141_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'region_id' => $this->integer(),
            'role' => $this->integer(),
            'token' => $this->string(),
            'device_token' => $this->string(),
            'name' => $this->string(),
            'lastname' => $this->string(),
            'fname' => $this->string(),
            'birthday' => $this->date(),
            'phone' => $this->string(),
            'email' => $this->string(),
            'login' => $this->string()->unique(),
            'password' => $this->string(),
            'gender' => $this->integer()->notNull()->defaultValue(1),
            'status' => $this->integer()->notNull()->defaultValue(0),
            'date' => $this->timestamp(),
            'ip' => $this->string()
        ]);

        $this->createIndex('id', 'user', 'id', true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190809_064141_user cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190809_064141_user cannot be reverted.\n";

        return false;
    }
    */
}
