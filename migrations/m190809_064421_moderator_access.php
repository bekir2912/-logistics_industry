<?php

use yii\db\Migration;

/**
 * Class m190809_064421_moderator_access
 */
class m190809_064421_moderator_access extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('moderator_access', [
            'id' => $this->primaryKey(),
            'moderator_id' => $this->integer(),
            'user_id' => $this->integer()
        ]);

        $this->addForeignKey('moderator_url_moderid_fk', 'moderator_access', 'moderator_id', 'moderator_url', 'id', 'CASCADE');
        $this->addForeignKey('moderator_url_userid_fk', 'moderator_access', 'user_id', 'user', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190809_064421_moderator_access cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190809_064421_moderator_access cannot be reverted.\n";

        return false;
    }
    */
}
