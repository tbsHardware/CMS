<?php

use yii\db\Migration;

class m151130_173239_create_post_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%posts_post}}', [
            'id'               => $this->primaryKey(),
            'post_alias'       => $this->string(120),
            'post_title'       => $this->text(),
            'post_description' => $this->text(),
            'post_content'     => 'longtext',
            'post_status'      => $this->string(20)->defaultValue('publish'),
            'post_type'        => $this->string(20)->defaultValue('post'),
            'post_parent'      => $this->integer(),
            'user_author'      => $this->integer()->notNull(),
            'post_date'        => $this->integer()->notNull(),
            'created_at'       => $this->integer()->notNull(),
            'updated_at'       => $this->integer()->notNull(),
        ]);

        $this->createIndex('index_posts_post', '{{%posts_post}}', 'user_author');
    }

    public function down()
    {
        $this->dropTable('{{%posts_post}}');
    }
}
