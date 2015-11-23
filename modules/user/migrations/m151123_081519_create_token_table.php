<?php

use yii\db\Schema;
use yii\db\Migration;

class m151113_093354_create_user_token_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%user_token}}', [
            'user_id' => $this->integer()->notNull(),
            'token' => $this->string(32)->notNull(),
            'created_at' => $this->integer()->notNull(),
            'type' => $this->smallInteger()->notNull(),
        ]);

        $this->createIndex('token_unique', '{{%user_token}}', ['user_id', 'token', 'type'], true);
        $this->addForeignKey('fk_user_token', '{{%user_token}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%user_token}}');
    }
}
