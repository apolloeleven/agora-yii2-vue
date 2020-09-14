<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%users}}`.
 */
class m200914_120602_add_deleted_at_and_deleted_by_columns_to_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%users}}', 'deleted_at', $this->string(11));
        $this->addColumn('{{%users}}', 'deleted_by', $this->string(11));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%users}}', 'deleted_by');
        $this->dropColumn('{{%users}}', 'deleted_at');
    }
}
