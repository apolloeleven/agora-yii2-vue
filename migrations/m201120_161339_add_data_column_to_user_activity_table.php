<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%user_activity}}`.
 */
class m201120_161339_add_data_column_to_user_activity_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user_activity}}', 'data', $this->text()->after('description'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user_activity}}', 'data');
    }
}
