<?php

use yii\db\Migration;

/**
 * Class m201109_113741_alter_table_timeline_posts_add_column_workspace_id
 */
class m201109_113741_alter_table_timeline_posts_add_column_workspace_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%timeline_posts}}', 'workspace_id', $this->integer(11)->after('id'));
        $this->addForeignKey(
            'fk_timeline_posts_workspace',
            '{{%timeline_posts}}',
            'workspace_id',
            '{{%workspaces}}',
            "id",
            "CASCADE"
        );
        $this->createIndex(
            '{{%idx-timeline_posts_workspace_id}}',
            '{{%timeline_posts}}',
            'workspace_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_timeline_posts_workspace', '{{%timeline_posts}}');
        $this->dropIndex('{{%idx-timeline_posts_workspace_id}}', '{{%timeline_posts}}');
        $this->dropColumn('{{%timeline_posts}}', 'workspace_id');
    }

}
