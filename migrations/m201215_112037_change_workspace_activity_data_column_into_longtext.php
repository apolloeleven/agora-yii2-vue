<?php

use yii\db\Migration;

/**
 * Class m201215_112037_change_workspace_activity_data_column_into_longtext
 */
class m201215_112037_change_workspace_activity_data_column_into_longtext extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%workspace_activity}}', 'data', 'LONGTEXT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%workspace_activity}}', 'data', $this->text());
    }
}
