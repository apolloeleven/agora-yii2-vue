<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%timeline_posts}}`.
 */
class m201113_075837_add_name_and_size_columns_to_timeline_posts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%timeline_posts}}', 'name', $this->string(1024)->after('action'));
        $this->addColumn('{{%timeline_posts}}', 'size', $this->integer()->after('name'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%timeline_posts}}', 'name');
        $this->dropColumn('{{%timeline_posts}}', 'size');
    }
}
