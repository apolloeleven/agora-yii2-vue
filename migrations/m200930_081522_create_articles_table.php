<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%articles}}`.
 */
class m200930_081522_create_articles_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%articles}}', [
            'id' => $this->primaryKey(),
            'parent_id' => $this->integer(),
            'workspace_id' => $this->integer()->notNull(),
            'title' => $this->string(1024),
            'body' => $this->text(),
            'is_folder' => $this->integer(1)->defaultValue(0),
            'image_path' => $this->string(1024),
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
            '{{%idx-article-parent_id}}',
            '{{%articles}}',
            'parent_id'
        );

        // add foreign key for table `{{%articles}}`
        $this->addForeignKey(
            '{{%fk-article-parent_id}}',
            '{{%articles}}',
            'parent_id',
            '{{%articles}}',
            'id',
            'CASCADE'
        );

        // creates index for column `workspace_id`
        $this->createIndex(
            '{{%idx-article-workspace_id}}',
            '{{%articles}}',
            'workspace_id'
        );

        // add foreign key for table `{{%workspaces}}`
        $this->addForeignKey(
            '{{%fk-article-workspace_id}}',
            '{{%articles}}',
            'workspace_id',
            '{{%workspaces}}',
            'id',
            'CASCADE'
        );

        // creates index for column `created_by`
        $this->createIndex(
            '{{%idx-article-created_by}}',
            '{{%articles}}',
            'created_by'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-article-created_by}}',
            '{{%articles}}',
            'created_by',
            '{{%users}}',
            'id',
            'CASCADE'
        );

        // creates index for column `updated_by`
        $this->createIndex(
            '{{%idx-article-updated_by}}',
            '{{%articles}}',
            'updated_by'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-article-updated_by}}',
            '{{%articles}}',
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
        // drops foreign key for table `{{%articles}}`
        $this->dropForeignKey(
            '{{%fk-article-parent_id}}',
            '{{%articles}}'
        );

        // drops index for column `workspace_id`
        $this->dropIndex(
            '{{%idx-article-parent_id}}',
            '{{%articles}}'
        );

        // drops foreign key for table `{{%workspaces}}`
        $this->dropForeignKey(
            '{{%fk-article-workspace_id}}',
            '{{%articles}}'
        );

        // drops index for column `workspace_id`
        $this->dropIndex(
            '{{%idx-article-workspace_id}}',
            '{{%articles}}'
        );

        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-article-created_by}}',
            '{{%articles}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            '{{%idx-article-created_by}}',
            '{{%articles}}'
        );

        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-article-updated_by}}',
            '{{%articles}}'
        );

        // drops index for column `updated_by`
        $this->dropIndex(
            '{{%idx-article-updated_by}}',
            '{{%articles}}'
        );

        $this->dropTable('{{%articles}}');
    }
}
