<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%countries}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%users}}`
 * - `{{%users}}`
 */
class m200911_225832_create_countries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%countries}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        // creates index for column `created_by`
        $this->createIndex(
            '{{%idx-countries-created_by}}',
            '{{%countries}}',
            'created_by'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-countries-created_by}}',
            '{{%countries}}',
            'created_by',
            '{{%users}}',
            'id',
            'CASCADE'
        );

        // creates index for column `updated_by`
        $this->createIndex(
            '{{%idx-countries-updated_by}}',
            '{{%countries}}',
            'updated_by'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-countries-updated_by}}',
            '{{%countries}}',
            'updated_by',
            '{{%users}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-countries-created_by}}',
            '{{%countries}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            '{{%idx-countries-created_by}}',
            '{{%countries}}'
        );

        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-countries-updated_by}}',
            '{{%countries}}'
        );

        // drops index for column `updated_by`
        $this->dropIndex(
            '{{%idx-countries-updated_by}}',
            '{{%countries}}'
        );

        $this->dropTable('{{%countries}}');
    }
}
