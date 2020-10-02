<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%timeline_posts}}`.
 */
class m200930_081027_create_timeline_posts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%timeline_posts}}', [
            'id' => $this->primaryKey(),
            'description' => $this->text(),
            'image_path' => $this->string(1024),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer()
        ]);

        $this->createIndex(
            'idx-timeline_posts-created_by',
            '{{%timeline_posts}}',
            'created_by'
        );
        $this->addForeignKey(
            'fk-timeline_posts-created_by',
            '{{%timeline_posts}}',
            'created_by',
            '{{%users}}',
            'id'
        );

        $this->createIndex(
            'idx-timeline_posts-updated_by',
            '{{%timeline_posts}}',
            'updated_by'
        );
        $this->addForeignKey(
            'fk-timeline_posts-updated_by',
            '{{%timeline_posts}}',
            'updated_by',
            '{{%users}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-timeline_posts-created_by',
            '{{%timeline_posts}}'
        );
        $this->dropIndex(
            'idx-timeline_posts-created_by',
            '{{%timeline_posts}}'
        );

        $this->dropForeignKey(
            'fk-timeline_posts-updated_by',
            '{{%timeline_posts}}'
        );
        $this->dropIndex(
            'idx-timeline_posts-updated_by',
            '{{%timeline_posts}}'
        );

        $this->dropTable('{{%timeline_posts}}');
    }
}
