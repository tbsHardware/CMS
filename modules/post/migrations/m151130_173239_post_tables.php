<?php

use yii\db\Migration;

class m151130_173239_post_tables extends Migration
{
    public function up()
    {
        $this->createTable('{{%post_page}}', [
            'id' => $this->primaryKey(),
            'path' => $this->string(120)->notNull(),
            'title' => $this->text()->notNull(),
            'content' => 'longtext',
            'page_parent' => $this->integer(),
            'template' => $this->string(20)->defaultValue('page'),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('index_page_path', '{{%post_page}}', 'path');
        $this->addForeignKey('fk_page_parent', '{{%post_page}}', 'page_parent', '{{%post_page}}', 'id', 'CASCADE',
            'CASCADE');

        $this->createTable('{{%post_post}}', [
            'id' => $this->primaryKey(),
            'path' => $this->string(120)->notNull(),
            'title' => $this->text()->notNull(),
            'description' => $this->text(),
            'content' => 'longtext',
            'date' => $this->integer()->notNull(),
            'status' => $this->string(20)->defaultValue('published'),
            'comment_status' => $this->string(20)->defaultValue('open'),
            'page_id' => $this->integer(),
            'author_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('index_user_author', '{{%post_post}}', 'author_id');
        $this->addForeignKey('fk_page_id', '{{%post_post}}', 'page_id', '{{%post_page}}', 'id', 'CASCADE',
            'RESTRICT');

        $this->createTable('{{%post_menu}}', [
            'id' => $this->primaryKey(),
            'type' => $this->string(20)->defaultValue('page'),
            'link_id' => $this->integer()->notNull(),
            'label' => $this->string(255),
            'parent_id' => $this->integer(),
            'position' => $this->integer()->defaultValue(0),
            'visible' => $this->string(255),
        ]);

        $this->addForeignKey('fk_parent_id', '{{%post_menu}}', 'parent_id', '{{%post_menu}}', 'id', 'CASCADE',
            'CASCADE');

    }

    public function down()
    {
        $this->dropTable('{{%post_post}}');
        $this->dropTable('{{%post_page}}');
        $this->dropTable('{{%post_menu}}');
    }
}
