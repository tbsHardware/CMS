<?php

use yii\db\Migration;

class m151123_081351_create_tables extends Migration
{
    public function up()
    {
        $this->createTable('{{%users_user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'email' => $this->string()->notNull()->unique(),
            'password_hash' => $this->string(60)->notNull(),
            'auth_key' => $this->string(32)->notNull(),
            'unconfirmed_email' => $this->string(),
            'registration_ip' => $this->string(45)->notNull(),
            'confirmed_at' => $this->integer(),
            'blocked_at' => $this->integer(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('user_unique_username', '{{%users_user}}', 'username', true);
        $this->createIndex('user_unique_email', '{{%users_user}}', 'email', true);

        $this->createTable('{{%users_token}}', [
            'user_id' => $this->integer()->notNull(),
            'token' => $this->string(32)->notNull(),
            'created_at' => $this->integer()->notNull(),
            'type' => $this->smallInteger()->notNull(),
        ]);

        $this->createIndex('token_unique', '{{%users_token}}', ['user_id', 'token', 'type'], true);
        $this->addForeignKey('fk_users_token', '{{%users_token}}', 'user_id', '{{%users_user}}', 'id', 'CASCADE',
            'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%users_user}}');
        $this->dropTable('{{%users_token}}');
    }
}
