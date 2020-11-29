<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%timeline_posts}}`.
 */
class m201129_112437_add_poll_id_column_to_timeline_posts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%timeline_posts}}', 'poll_id', $this->integer()->after('article_id'));

        // creates index for column `poll_id`
        $this->createIndex(
            '{{%idx-timeline_posts-poll_id}}',
            '{{%timeline_posts}}',
            'poll_id'
        );

        // add foreign key for table `{{%polls}}`
        $this->addForeignKey(
            '{{%fk-timeline_posts-poll_id}}',
            '{{%timeline_posts}}',
            'poll_id',
            '{{%polls}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        // drops foreign key for table `{{%polls}}`
        $this->dropForeignKey(
            '{{%fk-timeline_posts-poll_id}}',
            '{{%timeline_posts}}'
        );

        // drops index for column `poll_id`
        $this->dropIndex(
            '{{%idx-timeline_posts-poll_id}}',
            '{{%timeline_posts}}'
        );

        $this->dropColumn('{{%timeline_posts}}', 'poll_id');
    }
}
