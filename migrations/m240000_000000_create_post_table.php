<?php

use yii\db\Migration;

class m240000_000000_create_post_table extends Migration
{
    public function up()
    {
        $this->createTable('post', [
            'id' => $this->primaryKey(),
            'id_user' => $this->integer()->notNull(),
            'massege' => $this->text()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->addForeignKey(
            'fk-post-user',
            'post',
            'id_user',
            'user',
            'id',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropForeignKey('fk-post-user', 'post');
        $this->dropTable('post');
    }
} 