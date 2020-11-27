<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%polls}}`.
 */
class m201126_111006_create_polls_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%polls}}', [
            'id' => $this->primaryKey(),
            'workspace_id' => $this->integer()->notNull(),
            'question' => $this->string(1024),
            'description' => $this->text(),
            'is_multiple' => $this->tinyInteger()->defaultValue(0),
            'is_for_timeline' => $this->tinyInteger()->defaultValue(0),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        // creates index for column `workspace_id`
        $this->createIndex(
            '{{%idx-poll-workspace_id}}',
            '{{%polls}}',
            'workspace_id'
        );

        // add foreign key for table `{{%workspaces}}`
        $this->addForeignKey(
            '{{%fk-poll-workspace_id}}',
            '{{%polls}}',
            'workspace_id',
            '{{%workspaces}}',
            'id',
            'CASCADE'
        );

        // creates index for column `created_by`
        $this->createIndex(
            '{{%idx-poll-created_by}}',
            '{{%polls}}',
            'created_by'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-poll-created_by}}',
            '{{%polls}}',
            'created_by',
            '{{%users}}',
            'id',
            'CASCADE'
        );

        // creates index for column `updated_by`
        $this->createIndex(
            '{{%idx-poll-updated_by}}',
            '{{%polls}}',
            'updated_by'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-poll-updated_by}}',
            '{{%polls}}',
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

        // drops foreign key for table `{{%workspaces}}`
        $this->dropForeignKey(
            '{{%fk-poll-workspace_id}}',
            '{{%polls}}'
        );

        // drops index for column `workspace_id`
        $this->dropIndex(
            '{{%idx-poll-workspace_id}}',
            '{{%polls}}'
        );

        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-poll-created_by}}',
            '{{%polls}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            '{{%idx-poll-created_by}}',
            '{{%polls}}'
        );

        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-poll-updated_by}}',
            '{{%polls}}'
        );

        // drops index for column `updated_by`
        $this->dropIndex(
            '{{%idx-poll-updated_by}}',
            '{{%polls}}'
        );

        $this->dropTable('{{%polls}}');
    }
}
