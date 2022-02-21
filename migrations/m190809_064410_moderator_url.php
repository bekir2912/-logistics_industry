<?php

use yii\db\Migration;

/**
 * Class m190809_064410_moderator_url
 */
class m190809_064410_moderator_url extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('moderator_url', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'url' => $this->string()
        ]);

        $this->createIndex('id', 'moderator_url', 'id', true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190809_064410_moderator_url cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190809_064410_moderator_url cannot be reverted.\n";

        return false;
    }
    */
}
