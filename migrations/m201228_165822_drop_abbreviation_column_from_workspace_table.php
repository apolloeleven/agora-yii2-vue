<?php

use yii\db\Migration;

/**
 * Handles dropping columns from table `{{%workspace}}`.
 */
class m201228_165822_drop_abbreviation_column_from_workspace_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%workspaces}}', 'abbreviation');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%workspaces}}', 'abbreviation', $this->string(55)->after('name'));
    }
}
