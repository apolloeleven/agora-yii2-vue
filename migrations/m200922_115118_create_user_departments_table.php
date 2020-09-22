<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_departments}}`.
 */
class m200922_115118_create_user_departments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_departments}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'country_id' => $this->integer(),
            'department_id' => $this->integer(),
            'position' => $this->string(1024),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        // creates index for column `created_by`
        $this->createIndex(
            '{{%idx-user_department-created_by}}',
            '{{%user_departments}}',
            'created_by'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-user_department-created_by}}',
            '{{%user_departments}}',
            'created_by',
            '{{%users}}',
            'id',
            'CASCADE'
        );

        // creates index for column `updated_by`
        $this->createIndex(
            '{{%idx-user_department-updated_by}}',
            '{{%user_departments}}',
            'updated_by'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-user_department-updated_by}}',
            '{{%user_departments}}',
            'updated_by',
            '{{%users}}',
            'id',
            'CASCADE'
        );

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-user_department-user_id}}',
            '{{%user_departments}}',
            'user_id'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-user_department-user_id}}',
            '{{%user_departments}}',
            'user_id',
            '{{%users}}',
            'id',
            'CASCADE'
        );

        // creates index for column `country_id`
        $this->createIndex(
            '{{%idx-user_department-country_id}}',
            '{{%user_departments}}',
            'country_id'
        );

        // add foreign key for table `{{%countries}}`
        $this->addForeignKey(
            '{{%fk-user_department-country_id}}',
            '{{%user_departments}}',
            'country_id',
            '{{%countries}}',
            'id',
            'CASCADE'
        );

        // creates index for column `department_id`
        $this->createIndex(
            '{{%idx-user_department-department_id}}',
            '{{%user_departments}}',
            'department_id'
        );

        // add foreign key for table `{{%departments}}`
        $this->addForeignKey(
            '{{%fk-user_department-department_id}}',
            '{{%user_departments}}',
            'department_id',
            '{{%departments}}',
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
            '{{%fk-user_department-created_by}}',
            '{{%user_departments}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            '{{%idx-user_department-created_by}}',
            '{{%user_departments}}'
        );

        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-user_department-updated_by}}',
            '{{%user_departments}}'
        );

        // drops index for column `updated_by`
        $this->dropIndex(
            '{{%idx-user_department-updated_by}}',
            '{{%user_departments}}'
        );

        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-user_department-user_id}}',
            '{{%user_departments}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-user_department-user_id}}',
            '{{%user_departments}}'
        );

        // drops foreign key for table `{{%countries}}`
        $this->dropForeignKey(
            '{{%fk-user_department-country_id}}',
            '{{%user_departments}}'
        );

        // drops index for column `country_id`
        $this->dropIndex(
            '{{%idx-user_department-country_id}}',
            '{{%user_departments}}'
        );

        // drops foreign key for table `{{%departments}}`
        $this->dropForeignKey(
            '{{%fk-user_department-department_id}}',
            '{{%user_departments}}'
        );

        // drops index for column `department_id`
        $this->dropIndex(
            '{{%idx-user_department-department_id}}',
            '{{%user_departments}}'
        );

        $this->dropTable('{{%user_departments}}');
    }
}
