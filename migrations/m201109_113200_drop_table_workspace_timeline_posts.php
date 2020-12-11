<?php

use yii\db\Migration;

/**
 * Class m201109_113200_drop_table_workspace_timeline_posts
 */
class m201109_113200_drop_table_workspace_timeline_posts extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('fk-workspace_timeline_posts-updated_by', '{{%workspace_timeline_posts}}');
        $this->dropIndex('idx-workspace_timeline_posts-updated_by', '{{%workspace_timeline_posts}}');
        $this->dropForeignKey('fk-workspace_timeline_posts-created_by', '{{%workspace_timeline_posts}}');
        $this->dropIndex('idx-workspace_timeline_posts-created_by', '{{%workspace_timeline_posts}}');
        $this->dropForeignKey('fk-workspace_timeline_posts-timeline_post_id', '{{%workspace_timeline_posts}}');
        $this->dropIndex('idx-workspace_timeline_posts-timeline_post_id', '{{%workspace_timeline_posts}}');
        $this->dropForeignKey('fk-workspace_timeline_posts-workspace_id', '{{%workspace_timeline_posts}}');
        $this->dropIndex('idx-workspace_timeline_posts-workspace_id', '{{%workspace_timeline_posts}}');
        $this->dropTable('{{%workspace_timeline_posts}}');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
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
}
