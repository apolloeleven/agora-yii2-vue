<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_departments}}`.
 */
class m200923_134202_create_user_departments_table extends Migration
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
            'updated_by' => $this->integer()
        ]);

        $this->createIndex(
            'idx-user_departments-user_id',
            '{{%user_departments}}',
            'user_id'
        );
        $this->addForeignKey(
            'fk-user_departments-user_id',
            '{{%user_departments}}',
            'user_id',
            '{{%users}}',
            'id'
        );

        $this->createIndex(
            'idx-user_departments-country_id',
            '{{%user_departments}}',
            'country_id'
        );
        $this->addForeignKey(
            'fk-user_departments-country_id',
            '{{%user_departments}}',
            'country_id',
            '{{%countries}}',
            'id'
        );

        $this->createIndex(
            'idx-user_departments-department_id',
            '{{%user_departments}}',
            'department_id'
        );
        $this->addForeignKey(
            'fk-user_departments-department_id',
            '{{%user_departments}}',
            'department_id',
            '{{%departments}}',
            'id'
        );

        $this->createIndex(
            'idx-user_departments-created_by',
            '{{%user_departments}}',
            'created_by'
        );
        $this->addForeignKey(
            'fk-user_departments-created_by',
            '{{%user_departments}}',
            'created_by',
            '{{%users}}',
            'id'
        );

        $this->createIndex(
            'idx-user_departments-updated_by',
            '{{%user_departments}}',
            'updated_by'
        );
        $this->addForeignKey(
            'fk-user_departments-updated_by',
            '{{%user_departments}}',
            'updated_by',
            '{{%users}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-user_departments-user_id',
            '{{%user_departments}}'
        );
        $this->dropIndex(
            'idx-user_departments-user_id',
            '{{%user_departments}}'
        );

        $this->dropForeignKey(
            'fk-user_departments-country_id',
            '{{%user_departments}}'
        );
        $this->dropIndex(
            'idx-user_departments-country_id',
            '{{%user_departments}}'
        );

        $this->dropForeignKey(
            'fk-user_departments-department_id',
            '{{%user_departments}}'
        );
        $this->dropIndex(
            'idx-user_departments-department_id',
            '{{%user_departments}}'
        );

        $this->dropForeignKey(
            'fk-user_departments-created_by',
            '{{%user_departments}}'
        );
        $this->dropIndex(
            'idx-user_departments-created_by',
            '{{%user_departments}}'
        );

        $this->dropForeignKey(
            'fk-user_departments-updated_by',
            '{{%user_departments}}'
        );
        $this->dropIndex(
            'idx-user_departments-updated_by',
            '{{%user_departments}}'
        );
        $this->dropTable('{{%user_departments}}');
    }
}
