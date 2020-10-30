<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_likes}}`.
 */
class m201019_090646_create_user_likes_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_likes}}', [
            'id' => $this->primaryKey(),
            'article_id' => $this->integer(),
            'timeline_post_id' => $this->integer(),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
        ]);

        // creates index for column `article_id`
        $this->createIndex(
            '{{%idx-user_likes-article_id}}',
            '{{%user_likes}}',
            'article_id'
        );

        // add foreign key for table `{{%user_likes}}`
        $this->addForeignKey(
            '{{%fk-user_likes-article_id}}',
            '{{%user_likes}}',
            'article_id',
            '{{%articles}}',
            'id',
            'CASCADE'
        );

        // creates index for column `timeline_post_id`
        $this->createIndex(
            '{{%idx-user_likes-timeline_post_id}}',
            '{{%user_likes}}',
            'timeline_post_id'
        );

        // add foreign key for table `{{%user_likes}}`
        $this->addForeignKey(
            '{{%fk-user_likes-timeline_post_id}}',
            '{{%user_likes}}',
            'timeline_post_id',
            '{{%timeline_posts}}',
            'id',
            'CASCADE'
        );

        // creates index for column `created_by`
        $this->createIndex(
            '{{%idx-user_likes-created_by}}',
            '{{%user_likes}}',
            'created_by'
        );

        // add foreign key for table `{{%user_likes}}`
        $this->addForeignKey(
            '{{%fk-user_likes-created_by}}',
            '{{%user_likes}}',
            'created_by',
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
        // drops foreign key for table `{{%user_likes}}`
        $this->dropForeignKey(
            '{{%fk-user_likes-article_id}}',
            '{{%user_likes}}'
        );

        // drops index for column `article_id`
        $this->dropIndex(
            '{{%idx-user_likes-article_id}}',
            '{{%user_likes}}'
        );

        // drops foreign key for table `{{%user_likes}}`
        $this->dropForeignKey(
            '{{%fk-user_likes-timeline_post_id}}',
            '{{%user_likes}}'
        );

        // drops index for column `timeline_post_id`
        $this->dropIndex(
            '{{%idx-user_likes-timeline_post_id}}',
            '{{%user_likes}}'
        );

        // drops foreign key for table `{{%user_likes}}`
        $this->dropForeignKey(
            '{{%fk-user_likes-created_by}}',
            '{{%user_likes}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            '{{%idx-user_likes-created_by}}',
            '{{%user_likes}}'
        );

        $this->dropTable('{{%user_likes}}');
    }
}
