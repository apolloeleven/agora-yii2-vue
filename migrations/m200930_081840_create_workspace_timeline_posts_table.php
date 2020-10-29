<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%workspace_timeline_posts}}`.
 */
class m200930_081840_create_workspace_timeline_posts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%workspace_timeline_posts}}', [
            'id' => $this->primaryKey(),
            'workspace_id' => $this->integer(),
            'timeline_post_id' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer()
        ]);
        $this->createIndex(
            'idx-workspace_timeline_posts-workspace_id',
            '{{%workspace_timeline_posts}}',
            'workspace_id'
        );
        $this->addForeignKey(
            'fk-workspace_timeline_posts-workspace_id',
            '{{%workspace_timeline_posts}}',
            'workspace_id',
            '{{%workspaces}}',
            'id'
        );

        $this->createIndex(
            'idx-workspace_timeline_posts-timeline_post_id',
            '{{%workspace_timeline_posts}}',
            'timeline_post_id'
        );
        $this->addForeignKey(
            'fk-workspace_timeline_posts-timeline_post_id',
            '{{%workspace_timeline_posts}}',
            'timeline_post_id',
            '{{%timeline_posts}}',
            'id'
        );

        $this->createIndex(
            'idx-workspace_timeline_posts-created_by',
            '{{%workspace_timeline_posts}}',
            'created_by'
        );
        $this->addForeignKey(
            'fk-workspace_timeline_posts-created_by',
            '{{%workspace_timeline_posts}}',
            'created_by',
            '{{%users}}',
            'id'
        );

        $this->createIndex(
            'idx-workspace_timeline_posts-updated_by',
            '{{%workspace_timeline_posts}}',
            'updated_by'
        );
        $this->addForeignKey(
            'fk-workspace_timeline_posts-updated_by',
            '{{%workspace_timeline_posts}}',
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
        $this->dropTable('{{%workspace_timeline_posts}}');
    }
}
