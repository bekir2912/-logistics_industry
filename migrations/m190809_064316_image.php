<?php

use yii\db\Migration;

/**
 * Class m190809_064316_image
 */
class m190809_064316_image extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('image', [
            'id' => $this->primaryKey(),
            'object_id' => $this->integer(),
            'type' => $this->string(),
            'photo' => $this->string(),
            'main' => $this->integer(),
            'sort' => $this->integer()->notNull()->defaultValue(0),
            'status' => $this->integer()->notNull()->defaultValue(0)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190809_064316_image cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190809_064316_image cannot be reverted.\n";

        return false;
    }
    */
}
