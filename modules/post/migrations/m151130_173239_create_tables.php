<?php

use yii\db\Migration;

class m151130_173239_create_tables extends Migration
{
    public function up()
    {
        $this->createTable('{{%post_page}}', [
            'id' => $this->primaryKey(),
            'page_path' => $this->string(120)->notNull(),
            'page_title' => $this->text()->notNull(),
            'page_content' => 'longtext',
            'page_parent' => $this->integer(),
            'page_template' => $this->string(20)->defaultValue('page'),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('index_post_post', '{{%post_page}}', 'page_path');

        $this->createTable('{{%post_post}}', [
            'id' => $this->primaryKey(),
            'post_path' => $this->string(120)->notNull(),
            'post_title' => $this->text()->notNull(),
            'post_description' => $this->text(),
            'post_content' => 'longtext',
            'post_date' => $this->integer()->notNull(),
            'post_status' => $this->string(20)->defaultValue('published'),
            'comment_status' => $this->string(20)->defaultValue('open'),
            'page_id' => $this->integer(),
            'user_author' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('index_post_post', '{{%post_post}}', 'user_author');
        $this->addForeignKey('fk_post_post', '{{%post_post}}', 'page_id', '{{%post_page}}', 'id', 'CASCADE',
            'RESTRICT');

    }

    public function down()
    {
        $this->dropTable('{{%post_page}}');
        $this->dropTable('{{%post_post}}');
    }
}
