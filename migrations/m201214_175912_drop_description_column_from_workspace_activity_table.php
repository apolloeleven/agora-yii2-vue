<?php

use yii\db\Migration;

/**
 * Handles dropping columns from table `{{%workspace_activity}}`.
 */
class m201214_175912_drop_description_column_from_workspace_activity_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%workspace_activity}}', 'description');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%workspace_activity}}', 'description', 'LONGTEXT AFTER `action`');
    }
}
