<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%invitations}}`.
 */
class m200917_085836_create_invitations_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%invitations}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(11),
            'email' => $this->string(255)->notNull(),
            'status' => $this->integer(2),
            'token' => $this->string(1024)->notNull(),
            'expire_date' => $this->integer(11),
            'use_date' => $this->integer(11),
            'created_at' => $this->integer(11),
            'created_by' => $this->integer(11),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-invitations-user_id}}',
            '{{%invitations}}',
            'user_id'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-invitations-user_id}}',
            '{{%invitations}}',
            'user_id',
            '{{%users}}',
            'id',
            'CASCADE'
        );

        // creates index for column `created_by`
        $this->createIndex(
            '{{%idx-invitations-created_by}}',
            '{{%invitations}}',
            'created_by'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-invitations-created_by}}',
            '{{%invitations}}',
            'created_by',
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
            '{{%fk-invitations-created_by}}',
            '{{%invitations}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            '{{%idx-invitations-created_by}}',
            '{{%invitations}}'
        );

        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-invitations-user_id}}',
            '{{%invitations}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-invitations-user_id}}',
            '{{%invitations}}'
        );

        $this->dropTable('{{%invitations}}');
    }
}
