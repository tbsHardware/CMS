<?php

use yii\db\Migration;

class m151130_173239_create_tables extends Migration
{
    public function up()
    {
        $this->createTable('{{%posts_page}}', [
            'id' => $this->primaryKey(),
            'page_path' => $this->string(120)->notNull(),
            'page_title' => $this->text()->notNull(),
            'page_content' => 'longtext',
            'page_parent' => $this->integer(),
            'page_template' => $this->string(20)->defaultValue('page'),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('index_posts_post', '{{%posts_page}}', 'page_path');

        $this->createTable('{{%posts_post}}', [
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

        $this->createIndex('index_posts_post', '{{%posts_post}}', 'user_author');
        $this->addForeignKey('fk_posts_post', '{{%posts_post}}', 'page_id', '{{%posts_page}}', 'id', 'CASCADE',
            'RESTRICT');

    }

    public function down()
    {
        $this->dropTable('{{%posts_page}}');
        $this->dropTable('{{%posts_post}}');
    }
}
