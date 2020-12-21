<?php

use yii\db\Migration;

/**
 * Class m201220_195317_alter_body_column_on_folders_table
 */
class m201220_195317_alter_body_column_on_folders_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('{{%folders}}', 'body', 'data');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn('{{%folders}}', 'data', 'body');
    }
}
