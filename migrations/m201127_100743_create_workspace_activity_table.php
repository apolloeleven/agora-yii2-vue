<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%workspace_activity}}`.
 */
class m201127_100743_create_workspace_activity_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%workspace_activity}}', [
            'id' => $this->primaryKey(),
            'workspace_id' => $this->integer(),
            'table_name' => $this->string(128),
            'content_id' => $this->integer(),
            'action' => $this->string(128),
            'description' => 'LONGTEXT',
            'data' => $this->text(),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
        ]);
        $this->addForeignKey('FK_workspace_activity_users_created_by', '{{%workspace_activity}}', 'created_by', '{{%users}}', 'id');
        $this->addForeignKey('FK_workspace_activity_workspaces_workspaces_id', '{{%workspace_activity}}', 'workspace_id', '{{%workspaces}}', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('FK_workspace_activity_users_created_by', '{{%workspace_activity}}');
        $this->dropForeignKey('FK_workspace_activity_workspaces_workspaces_id', '{{%workspace_activity}}');
        $this->dropTable('{{%workspace_activity}}');
    }
}
