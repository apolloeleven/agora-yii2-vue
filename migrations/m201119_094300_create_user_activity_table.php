<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_activity}}`.
 */
class m201119_094300_create_user_activity_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_activity}}', [
            'id' => $this->primaryKey(),
            'workspace_id' => $this->integer(),
            'table_name' => $this->string(128),
            'content_id' => $this->integer(),
            'action' => $this->string(128),
            'description' => 'LONGTEXT',
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
        ]);
        $this->addForeignKey('FK_user_activity_users_created_by', '{{%user_activity}}', 'created_by', '{{%users}}', 'id');
        $this->addForeignKey('FK_user_activity_workspaces_workspaces_id', '{{%user_activity}}', 'workspace_id', '{{%workspaces}}', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('FK_user_activity_users_created_by', '{{%user_activity}}');
        $this->dropForeignKey('FK_user_activity_workspaces_workspaces_id', '{{%user_activity}}');
        $this->dropTable('{{%user_activity}}');
    }
}
