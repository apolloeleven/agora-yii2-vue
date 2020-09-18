<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%departments}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%countries}}`
 * - `{{%departments}}`
 * - `{{%users}}`
 * - `{{%users}}`
 */
class m200918_105312_create_departments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%departments}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'country_id' => $this->integer()->notNull(),
            'parent_id' => $this->integer(),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        // creates index for column `country_id`
        $this->createIndex(
            '{{%idx-departments-country_id}}',
            '{{%departments}}',
            'country_id'
        );

        // add foreign key for table `{{%countries}}`
        $this->addForeignKey(
            '{{%fk-departments-country_id}}',
            '{{%departments}}',
            'country_id',
            '{{%countries}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `parent_id`
        $this->createIndex(
            '{{%idx-departments-parent_id}}',
            '{{%departments}}',
            'parent_id'
        );

        // add foreign key for table `{{%departments}}`
        $this->addForeignKey(
            '{{%fk-departments-parent_id}}',
            '{{%departments}}',
            'parent_id',
            '{{%departments}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `created_by`
        $this->createIndex(
            '{{%idx-departments-created_by}}',
            '{{%departments}}',
            'created_by'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-departments-created_by}}',
            '{{%departments}}',
            'created_by',
            '{{%users}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `updated_by`
        $this->createIndex(
            '{{%idx-departments-updated_by}}',
            '{{%departments}}',
            'updated_by'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-departments-updated_by}}',
            '{{%departments}}',
            'updated_by',
            '{{%users}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%countries}}`
        $this->dropForeignKey(
            '{{%fk-departments-country_id}}',
            '{{%departments}}'
        );

        // drops index for column `country_id`
        $this->dropIndex(
            '{{%idx-departments-country_id}}',
            '{{%departments}}'
        );

        // drops foreign key for table `{{%departments}}`
        $this->dropForeignKey(
            '{{%fk-departments-parent_id}}',
            '{{%departments}}'
        );

        // drops index for column `parent_id`
        $this->dropIndex(
            '{{%idx-departments-parent_id}}',
            '{{%departments}}'
        );

        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-departments-created_by}}',
            '{{%departments}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            '{{%idx-departments-created_by}}',
            '{{%departments}}'
        );

        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-departments-updated_by}}',
            '{{%departments}}'
        );

        // drops index for column `updated_by`
        $this->dropIndex(
            '{{%idx-departments-updated_by}}',
            '{{%departments}}'
        );

        $this->dropTable('{{%departments}}');
    }
}
