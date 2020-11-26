<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%poll_answers}}`.
 */
class m201126_111030_create_poll_answers_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%poll_answers}}', [
            'id' => $this->primaryKey(),
            'poll_id' => $this->integer()->notNull(),
            'answer' => $this->string(1024),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        // creates index for column `poll_id`
        $this->createIndex(
            '{{%idx-poll_answer-poll_id}}',
            '{{%poll_answers}}',
            'poll_id'
        );

        // add foreign key for table `{{%polls}}`
        $this->addForeignKey(
            '{{%fk-poll_answer-poll_id}}',
            '{{%poll_answers}}',
            'poll_id',
            '{{%polls}}',
            'id',
            'CASCADE'
        );

        // creates index for column `created_by`
        $this->createIndex(
            '{{%idx-poll_answer-created_by}}',
            '{{%poll_answers}}',
            'created_by'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-poll_answer-created_by}}',
            '{{%poll_answers}}',
            'created_by',
            '{{%users}}',
            'id',
            'CASCADE'
        );

        // creates index for column `updated_by`
        $this->createIndex(
            '{{%idx-poll_answer-updated_by}}',
            '{{%poll_answers}}',
            'updated_by'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-poll_answer-updated_by}}',
            '{{%poll_answers}}',
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
            '{{%fk-poll_answer-poll_id}}',
            '{{%poll_answers}}'
        );

        // drops index for column `poll_id`
        $this->dropIndex(
            '{{%idx-poll_answer-poll_id}}',
            '{{%poll_answers}}'
        );

        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-poll_answer-created_by}}',
            '{{%poll_answers}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            '{{%idx-poll_answer-created_by}}',
            '{{%poll_answers}}'
        );

        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-poll_answer-updated_by}}',
            '{{%poll_answers}}'
        );

        // drops index for column `updated_by`
        $this->dropIndex(
            '{{%idx-poll_answer-updated_by}}',
            '{{%poll_answers}}'
        );

        $this->dropTable('{{%poll_answers}}');
    }
}
