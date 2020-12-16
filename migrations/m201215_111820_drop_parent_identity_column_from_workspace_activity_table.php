<?php

use yii\db\Migration;

/**
 * Handles dropping columns from table `{{%workspace_activity}}`.
 */
class m201215_111820_drop_parent_identity_column_from_workspace_activity_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%workspace_activity}}', 'parent_identity');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%workspace_activity}}', 'parent_identity', $this->text()->after('data'));
    }
}
