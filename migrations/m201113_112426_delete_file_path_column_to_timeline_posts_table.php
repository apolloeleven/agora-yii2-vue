<?php

use yii\db\Migration;

/**
 * Class m201113_112426_delete_file_path_column_to_timeline_posts_table
 */
class m201113_112426_delete_file_path_column_to_timeline_posts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%timeline_posts}}', 'file_path');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%timeline_posts}}', 'file_path', $this->string(1024)->after('description'));
    }
}
