<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%workspace_activity}}`.
 */
class m201201_110159_add_parent_identity_column_to_workspace_activity_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%workspace_activity}}', 'parent_identity', $this->text()->after('data'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%workspace_activity}}', 'parent_identity');
    }
}
