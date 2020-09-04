<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%users}}`.
 */
class m200903_103010_create_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%users}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string(255)->notNull(),
            'email' => $this->string(512)->notNull(),
            'password_hash' => $this->string(1024)->notNull(),
            'password_reset_token' => $this->string(1024),
            'access_token' => $this->string(512),
            'status' => $this->tinyInteger(),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);


        // creates index for column `created_by`
        $this->createIndex(
            '{{%idx-users-created_by}}',
            '{{%users}}',
            'created_by'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-users-created_by}}',
            '{{%users}}',
            'created_by',
            '{{%users}}',
            'id',
            'CASCADE'
        );

        // creates index for column `updated_by`
        $this->createIndex(
            '{{%idx-users-updated_by}}',
            '{{%users}}',
            'updated_by'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-users-updated_by}}',
            '{{%users}}',
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
            '{{%fk-users-created_by}}',
            '{{%users}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            '{{%idx-users-created_by}}',
            '{{%users}}'
        );

        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-users-updated_by}}',
            '{{%users}}'
        );

        // drops index for column `updated_by`
        $this->dropIndex(
            '{{%idx-users-updated_by}}',
            '{{%users}}'
        );

        $this->dropTable('{{%users}}');
    }
}
