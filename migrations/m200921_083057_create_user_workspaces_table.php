<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_workspaces}}`.
 */
class m200921_083057_create_user_workspaces_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_workspaces}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'role' => $this->string(64),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        // creates index for column `created_by`
        $this->createIndex(
            '{{%idx-user_workspace-created_by}}',
            '{{%user_workspaces}}',
            'created_by'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-user_workspace-created_by}}',
            '{{%user_workspaces}}',
            'created_by',
            '{{%users}}',
            'id',
            'CASCADE'
        );

        // creates index for column `updated_by`
        $this->createIndex(
            '{{%idx-user_workspace-updated_by}}',
            '{{%user_workspaces}}',
            'updated_by'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-user_workspace-updated_by}}',
            '{{%user_workspaces}}',
            'updated_by',
            '{{%users}}',
            'id',
            'CASCADE'
        );

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-user_workspace-user_id}}',
            '{{%user_workspaces}}',
            'user_id'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-user_workspace-user_id}}',
            '{{%user_workspaces}}',
            'user_id',
            '{{%users}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-user_workspace-created_by}}',
            '{{%user_workspaces}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            '{{%idx-user_workspace-created_by}}',
            '{{%user_workspaces}}'
        );

        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-user_workspace-updated_by}}',
            '{{%user_workspaces}}'
        );

        // drops index for column `updated_by`
        $this->dropIndex(
            '{{%idx-user_workspace-updated_by}}',
            '{{%user_workspaces}}'
        );

        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-user_workspace-user_id}}',
            '{{%user_workspaces}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-user_workspace-user_id}}',
            '{{%user_workspaces}}'
        );

        $this->dropTable('{{%user_workspaces}}');
    }
}
