<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_roles}}`.
 */
class m200924_121657_create_user_roles_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_roles}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'role' => $this->string(1024),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer()
        ]);

        $this->createIndex(
            'idx-user_roles-user_id',
            '{{%user_roles}}',
            'user_id'
        );
        $this->addForeignKey(
            'fk-user_roles-user_id',
            '{{%user_roles}}',
            'user_id',
            '{{%users}}',
            'id'
        );

        $this->createIndex(
            'idx-user_roles-created_by',
            '{{%user_roles}}',
            'created_by'
        );
        $this->addForeignKey(
            'fk-user_roles-created_by',
            '{{%user_roles}}',
            'created_by',
            '{{%users}}',
            'id'
        );

        $this->createIndex(
            'idx-user_roles-updated_by',
            '{{%user_roles}}',
            'updated_by'
        );
        $this->addForeignKey(
            'fk-user_roles-updated_by',
            '{{%user_roles}}',
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
            'fk-user_roles-user_id',
            '{{%user_roles}}'
        );
        $this->dropIndex(
            'idx-user_roles-user_id',
            '{{%user_roles}}'
        );

        $this->dropForeignKey(
            'fk-user_roles-created_by',
            '{{%user_roles}}'
        );
        $this->dropIndex(
            'idx-user_roles-created_by',
            '{{%user_roles}}'
        );

        $this->dropForeignKey(
            'fk-user_roles-updated_by',
            '{{%user_roles}}'
        );
        $this->dropIndex(
            'idx-user_roles-updated_by',
            '{{%user_roles}}'
        );

        $this->dropTable('{{%user_roles}}');
    }
}
