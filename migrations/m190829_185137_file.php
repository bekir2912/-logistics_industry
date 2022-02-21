<?php

use yii\db\Migration;

/**
 * Class m190829_185137_file
 */
class m190829_185137_file extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('file', [
            'id' => $this->primaryKey(),
            'object_id' => $this->integer(),
            'type' => $this->string(),
            'url' => $this->string(),
            'main' => $this->integer(),
            'sort' => $this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190829_185137_file cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190829_185137_file cannot be reverted.\n";

        return false;
    }
    */
}
