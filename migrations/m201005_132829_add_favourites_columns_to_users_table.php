<?php

use yii\base\NotSupportedException;
use yii\db\Migration;

/**
 * Handles adding columns to table `{{%users}}`.
 */
class m201005_132829_add_favourites_columns_to_users_table extends Migration
{
    /**
     * {@inheritdoc}
     * @throws NotSupportedException
     */
    public function safeUp()
    {
        $this->addColumn('{{%users}}', 'favourites', $this->getDb()->getSchema()->createColumnSchemaBuilder('longtext')->after('status'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%users}}', 'favourites');
    }
}
