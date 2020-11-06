<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_comments}}`.
 */
class m201015_125617_create_user_comments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_comments}}', [
            'id' => $this->primaryKey(),
            'parent_id' => $this->integer(),
            'article_id' => $this->integer(),
            'timeline_post_id' => $this->integer(),
            'comment' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        // creates index for column `parent_id`
        $this->createIndex(
            '{{%idx-user_comments-parent_id}}',
            '{{%user_comments}}',
            'parent_id'
        );

        // add foreign key for table `{{%user_comments}}`
        $this->addForeignKey(
            '{{%fk-user_comments-parent_id}}',
            '{{%user_comments}}',
            'parent_id',
            '{{%user_comments}}',
            'id',
            'CASCADE'
        );

        // creates index for column `article_id`
        $this->createIndex(
            '{{%idx-user_comments-article_id}}',
            '{{%user_comments}}',
            'article_id'
        );

        // add foreign key for table `{{%user_comments}}`
        $this->addForeignKey(
            '{{%fk-user_comments-article_id}}',
            '{{%user_comments}}',
            'article_id',
            '{{%articles}}',
            'id',
            'CASCADE'
        );

        // creates index for column `timeline_post_id`
        $this->createIndex(
            '{{%idx-user_comments-timeline_post_id}}',
            '{{%user_comments}}',
            'timeline_post_id'
        );

        // add foreign key for table `{{%user_comments}}`
        $this->addForeignKey(
            '{{%fk-user_comments-timeline_post_id}}',
            '{{%user_comments}}',
            'timeline_post_id',
            '{{%timeline_posts}}',
            'id',
            'CASCADE'
        );

        // creates index for column `created_by`
        $this->createIndex(
            '{{%idx-user_comments-created_by}}',
            '{{%user_comments}}',
            'created_by'
        );

        // add foreign key for table `{{%user_comments}}`
        $this->addForeignKey(
            '{{%fk-user_comments-created_by}}',
            '{{%user_comments}}',
            'created_by',
            '{{%users}}',
            'id',
            'CASCADE'
        );

        // creates index for column `updated_by`
        $this->createIndex(
            '{{%idx-user_comments-updated_by}}',
            '{{%user_comments}}',
            'updated_by'
        );

        // add foreign key for table `{{%user_comments}}`
        $this->addForeignKey(
            '{{%fk-user_comments-updated_by}}',
            '{{%user_comments}}',
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
        // drops foreign key for table `{{%user_comments}}`
        $this->dropForeignKey(
            '{{%fk-user_comments-parent_id}}',
            '{{%user_comments}}'
        );

        // drops index for column `parent_id`
        $this->dropIndex(
            '{{%idx-user_comments-parent_id}}',
            '{{%user_comments}}'
        );

        // drops foreign key for table `{{%user_comments}}`
        $this->dropForeignKey(
            '{{%fk-user_comments-article_id}}',
            '{{%user_comments}}'
        );

        // drops index for column `article_id`
        $this->dropIndex(
            '{{%idx-user_comments-article_id}}',
            '{{%user_comments}}'
        );

        // drops foreign key for table `{{%user_comments}}`
        $this->dropForeignKey(
            '{{%fk-user_comments-created_by}}',
            '{{%user_comments}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            '{{%idx-user_comments-created_by}}',
            '{{%user_comments}}'
        );

        // drops foreign key for table `{{%user_comments}}`
        $this->dropForeignKey(
            '{{%fk-user_comments-updated_by}}',
            '{{%user_comments}}'
        );

        // drops index for column `updated_by`
        $this->dropIndex(
            '{{%idx-user_comments-updated_by}}',
            '{{%user_comments}}'
        );

        $this->dropTable('{{%user_comments}}');
    }
}
