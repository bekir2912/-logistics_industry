<?php

use yii\db\Migration;

/**
 * Class m190818_193757_words
 */
class m190818_193757_words extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('words', [
            'id' => $this->primaryKey(),
            'name_ru' => $this->text(),
            'name_uz' => $this->text(),
            'name_en' => $this->text(),
            'date' => $this->timestamp()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190818_193757_words cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190818_193757_words cannot be reverted.\n";

        return false;
    }
    */
}
