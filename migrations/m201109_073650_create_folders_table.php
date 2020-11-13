<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%folders}}`.
 */
class m201109_073650_create_folders_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%folders}}', [
            'id' => $this->primaryKey(),
            'parent_id' => $this->integer(),
            'workspace_id' => $this->integer()->notNull(),
            'timeline_id' => $this->integer(),
            'is_default_folder' => $this->integer(1)->defaultValue(0),
            'is_file' => $this->integer(1)->defaultValue(0),
            'name' => $this->string(1024),
            'label' => $this->string(1024),
            'body' => $this->text(),
            'file_path' => $this->string(1024),
            'mime' => $this->string(255),
            'content' => 'longText',
            'size' => $this->integer(),
            'lft' => $this->integer(),
            'rgt' => $this->integer(),
            'depth' => $this->integer(),
            'tree' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        // creates index for column `parent_id`
        $this->createIndex(
            '{{%idx-folder-parent_id}}',
            '{{%folders}}',
            'parent_id'
        );

        // add foreign key for table `{{%folders}}`
        $this->addForeignKey(
            '{{%fk-folder-parent_id}}',
            '{{%folders}}',
            'parent_id',
            '{{%folders}}',
            'id',
            'CASCADE'
        );

        // creates index for column `workspace_id`
        $this->createIndex(
            '{{%idx-folder-workspace_id}}',
            '{{%folders}}',
            'workspace_id'
        );

        // add foreign key for table `{{%workspaces}}`
        $this->addForeignKey(
            '{{%fk-folder-workspace_id}}',
            '{{%folders}}',
            'workspace_id',
            '{{%workspaces}}',
            'id',
            'CASCADE'
        );

        // creates index for column `timeline_id`
        $this->createIndex(
            '{{%idx-folder-timeline_id}}',
            '{{%folders}}',
            'timeline_id'
        );

        // add foreign key for table `{{%timeline_posts}}`
        $this->addForeignKey(
            '{{%fk-folder-timeline_id}}',
            '{{%folders}}',
            'timeline_id',
            '{{%timeline_posts}}',
            'id',
            'CASCADE'
        );

        // creates index for column `created_by`
        $this->createIndex(
            '{{%idx-folder-created_by}}',
            '{{%folders}}',
            'created_by'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-folder-created_by}}',
            '{{%folders}}',
            'created_by',
            '{{%users}}',
            'id',
            'CASCADE'
        );

        // creates index for column `updated_by`
        $this->createIndex(
            '{{%idx-folder-updated_by}}',
            '{{%folders}}',
            'updated_by'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-folder-updated_by}}',
            '{{%folders}}',
            'updated_by',
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
        // drops foreign key for table `{{%folders}}`
        $this->dropForeignKey(
            '{{%fk-folder-parent_id}}',
            '{{%folders}}'
        );

        // drops index for column `parent_id`
        $this->dropIndex(
            '{{%idx-folder-parent_id}}',
            '{{%folders}}'
        );

        // drops foreign key for table `{{%workspaces}}`
        $this->dropForeignKey(
            '{{%fk-folder-workspace_id}}',
            '{{%folders}}'
        );

        // drops index for column `workspace_id`
        $this->dropIndex(
            '{{%idx-folder-workspace_id}}',
            '{{%folders}}'
        );

        // drops foreign key for table `{{%timeline_posts}}`
        $this->dropForeignKey(
            '{{%fk-folder-timeline_id}}',
            '{{%folders}}'
        );

        // drops index for column `timeline_id`
        $this->dropIndex(
            '{{%idx-folder-timeline_id}}',
            '{{%folders}}'
        );

        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-folder-created_by}}',
            '{{%folders}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            '{{%idx-folder-created_by}}',
            '{{%folders}}'
        );

        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-folder-updated_by}}',
            '{{%folders}}'
        );

        // drops index for column `updated_by`
        $this->dropIndex(
            '{{%idx-folder-updated_by}}',
            '{{%folders}}'
        );

        $this->dropTable('{{%folders}}');
    }
}
