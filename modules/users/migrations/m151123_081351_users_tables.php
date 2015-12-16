<?php

use yii\db\Migration;

class m151123_081351_users_tables extends Migration
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
            'code' => $this->string(32)->notNull(),
            'created_at' => $this->integer()->notNull(),
            'type' => $this->smallInteger()->notNull(),
        ]);

        $this->createIndex('token_unique', '{{%users_token}}', ['user_id', 'code', 'type'], true);
        $this->addForeignKey('fk_users_token', '{{%users_token}}', 'user_id', '{{%users_user}}', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('{{%users_field}}', [
            'id' => $this->primaryKey(),
            'alias' => $this->string(32)->notNull(),
            'title' => $this->string(255)->notNull(),
            'visible' => $this->smallInteger(1)->notNull()->defaultValue(0),
            'position' => $this->smallInteger(3)->notNull()->defaultValue(0),
            'field_type' => $this->string(32)->notNull()->defaultValue('string'),
            'field_size_min' => $this->integer(),
            'field_size_max' => $this->integer(),
            'required' => $this->smallInteger(1)->notNull()->defaultValue(0),
            'default' => $this->string(255),
            'range' => $this->string(255),
            'other_validator' => $this->string(255),
        ]);

        $this->createTable('{{%users_profile_field}}', [
            'user_id' => $this->integer()->notNull(),
            'field_id' => $this->integer()->notNull(),
            'value' => $this->text()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('fk1_users_profile_field', '{{%users_profile_field}}', 'user_id', '{{%users_user}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk2_users_profile_field', '{{%users_profile_field}}', 'field_id', '{{%users_field}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%users_user}}');
        $this->dropTable('{{%users_token}}');
        $this->dropTable('{{%users_field}}');
        $this->dropTable('{{%users_profile_field}}');
    }
}
