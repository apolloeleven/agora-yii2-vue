<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_poll_answers}}`.
 */
class m201126_111045_create_user_poll_answers_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_poll_answers}}', [
            'id' => $this->primaryKey(),
            'poll_id' => $this->integer()->notNull(),
            'poll_answer_id' => $this->integer()->notNull(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        // creates index for column `poll_answer_id`
        $this->createIndex(
            '{{%idx-user_poll-poll_answer_id}}',
            '{{%user_poll_answers}}',
            'poll_answer_id'
        );

        // add foreign key for table `{{%poll_answers}}`
        $this->addForeignKey(
            '{{%fk-user_poll-poll_answer_id}}',
            '{{%user_poll_answers}}',
            'poll_answer_id',
            '{{%poll_answers}}',
            'id',
            'CASCADE'
        );

        // creates index for column `poll_id`
        $this->createIndex(
            '{{%idx-user_poll-poll_id}}',
            '{{%user_poll_answers}}',
            'poll_id'
        );

        // add foreign key for table `{{%polls}}`
        $this->addForeignKey(
            '{{%fk-user_poll-poll_id}}',
            '{{%user_poll_answers}}',
            'poll_id',
            '{{%polls}}',
            'id',
            'CASCADE'
        );

        // creates index for column `created_by`
        $this->createIndex(
            '{{%idx-user_poll-created_by}}',
            '{{%user_poll_answers}}',
            'created_by'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-user_poll-created_by}}',
            '{{%user_poll_answers}}',
            'created_by',
            '{{%users}}',
            'id',
            'CASCADE'
        );

        // creates index for column `updated_by`
        $this->createIndex(
            '{{%idx-user_poll-updated_by}}',
            '{{%user_poll_answers}}',
            'updated_by'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-user_poll-updated_by}}',
            '{{%user_poll_answers}}',
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
        // drops foreign key for table `{{%polls}}`
        $this->dropForeignKey(
            '{{%fk-user_poll-poll_id}}',
            '{{%user_poll_answers}}'
        );

        // drops index for column `poll_id`
        $this->dropIndex(
            '{{%idx-user_poll-poll_id}}',
            '{{%user_poll_answers}}'
        );

        // drops foreign key for table `{{%poll_answers}}`
        $this->dropForeignKey(
            '{{%fk-user_poll-poll_answer_id}}',
            '{{%user_poll_answers}}'
        );

        // drops index for column `poll_answer_id`
        $this->dropIndex(
            '{{%idx-user_poll-poll_answer_id}}',
            '{{%user_poll_answers}}'
        );

        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-user_poll-created_by}}',
            '{{%user_poll_answers}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            '{{%idx-user_poll-created_by}}',
            '{{%user_poll_answers}}'
        );

        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-user_poll-updated_by}}',
            '{{%user_poll_answers}}'
        );

        // drops index for column `updated_by`
        $this->dropIndex(
            '{{%idx-user_poll-updated_by}}',
            '{{%user_poll_answers}}'
        );

        $this->dropTable('{{%user_poll_answers}}');
    }
}
