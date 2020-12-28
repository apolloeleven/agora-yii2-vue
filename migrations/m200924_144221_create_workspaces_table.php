<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%workspaces}}`.
 */
class m200924_144221_create_workspaces_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%workspaces}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'description' => $this->text(),
            'image_path' => $this->string(1024),
            'folder_in_folder' => $this->tinyInteger(1)->defaultValue(0),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        // creates index for column `created_by`
        $this->createIndex(
            '{{%idx-workspace-created_by}}',
            '{{%workspaces}}',
            'created_by'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-workspace-created_by}}',
            '{{%workspaces}}',
            'created_by',
            '{{%users}}',
            'id',
            'CASCADE'
        );

        // creates index for column `updated_by`
        $this->createIndex(
            '{{%idx-workspace-updated_by}}',
            '{{%workspaces}}',
            'updated_by'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-workspace-updated_by}}',
            '{{%workspaces}}',
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
        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-workspace-created_by}}',
            '{{%workspaces}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            '{{%idx-workspace-created_by}}',
            '{{%workspaces}}'
        );

        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-workspace-updated_by}}',
            '{{%workspaces}}'
        );

        // drops index for column `updated_by`
        $this->dropIndex(
            '{{%idx-workspace-updated_by}}',
            '{{%workspaces}}'
        );

        $this->dropTable('{{%workspaces}}');
    }
}
