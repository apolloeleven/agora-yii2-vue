<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%user_comments}} and {{%user_likes}}`.
 */
class m201110_083017_add_folder_id_column_to_user_comments_and_user_likes_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user_comments', 'folder_id', $this->integer()->after('timeline_post_id'));
        $this->addColumn('user_likes', 'folder_id', $this->integer()->after('timeline_post_id'));

        // creates index for column `folder_id`
        $this->createIndex(
            '{{%idx-user_comment-folder_id}}',
            '{{%user_comments}}',
            'folder_id'
        );

        // add foreign key for table `{{%user_comments}}`
        $this->addForeignKey(
            '{{%fk-user_comment-folder_id}}',
            '{{%user_comments}}',
            'folder_id',
            '{{%folders}}',
            'id',
            'CASCADE'
        );

        // creates index for column `folder_id`
        $this->createIndex(
            '{{%idx-user_like-folder_id}}',
            '{{%user_likes}}',
            'folder_id'
        );

        // add foreign key for table `{{%user_likes}}`
        $this->addForeignKey(
            '{{%fk-user_like-folder_id}}',
            '{{%user_likes}}',
            'folder_id',
            '{{%folders}}',
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
            '{{%fk-user_comment-folder_id}}',
            '{{%user_comments}}'
        );

        // drops index for column `folder_id`
        $this->dropIndex(
            '{{%idx-user_comment-folder_id}}',
            '{{%user_comments}}'
        );

        // drops foreign key for table `{{%user_likes}}`
        $this->dropForeignKey(
            '{{%fk-user_like-folder_id}}',
            '{{%user_likes}}'
        );

        // drops index for column `folder_id`
        $this->dropIndex(
            '{{%idx-user_like-folder_id}}',
            '{{%user_likes}}'
        );

        $this->dropColumn('user_comments', 'folder_id');
        $this->dropColumn('user_likes', 'folder_id');
    }
}
