<?php

use yii\db\Schema;
use yii\db\Migration;

class m151113_074308_create_user_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%user_user}}', [
            'id'                => $this->primaryKey(),
            'username'          => $this->string()->notNull()->unique(),
            'email'             => $this->string()->notNull()->unique(),
            'password_hash'     => $this->string(60)->notNull(),
            'auth_key'          => $this->string(32)->notNull(),
            'unconfirmed_email' => $this->string(),
            'registration_ip'   => $this->string(45)->notNull(),
            'confirmed_at'      => $this->integer(),
            'blocked_at'        => $this->integer(),
            'created_at'        => $this->integer()->notNull(),
            'updated_at'        => $this->integer()->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%user_user}}');
    }
}
