<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%timeline_posts}}`.
 */
class m201013_125848_add_action_column_to_timeline_posts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%timeline_posts}}', 'article_id', $this->integer()->after('id'));
        $this->addColumn('{{%timeline_posts}}', 'attachment_ids', $this->text()->after('article_id'));
        $this->addColumn('{{%timeline_posts}}', 'action', $this->string(255)->after('attachment_ids'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%timeline_posts}}', 'article_id');
        $this->dropColumn('{{%timeline_posts}}', 'attachment_ids');
        $this->dropColumn('{{%timeline_posts}}', 'action');
    }
}
