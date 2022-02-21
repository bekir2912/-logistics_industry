<?php

use yii\db\Migration;

/**
 * Class m190809_064432_notification
 */
class m190809_064432_notification extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('notification', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'object_id' => $this->integer(),
            'type' => $this->string(),
            'status' => $this->integer()->notNull()->defaultValue(0),
            'message' => $this->string(),
            'date' => $this->timestamp()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190809_064432_notification cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190809_064432_notification cannot be reverted.\n";

        return false;
    }
    */
}
