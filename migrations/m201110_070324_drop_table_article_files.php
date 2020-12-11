<?php

use yii\db\Migration;

/**
 * Class m201110_070324_drop_table_article_files
 */
class m201110_070324_drop_table_article_files extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('{{%fk-article_file-updated_by}}', '{{%article_files}}');
        $this->dropIndex('{{%idx-article_file-updated_by}}', '{{%article_files}}');
        $this->dropForeignKey('{{%fk-article_file-created_by}}', '{{%article_files}}');
        $this->dropIndex('{{%idx-article_file-created_by}}', '{{%article_files}}');
        $this->dropForeignKey('{{%fk-article_file-article_id}}', '{{%article_files}}');
        $this->dropIndex('{{%idx-article_file-article_id}}', '{{%article_files}}');
        $this->dropTable('{{%article_files}}');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->createTable('{{%article_files}}', [
            'id' => $this->primaryKey(),
            'article_id' => $this->integer(),
            'name' => $this->string(255),
            'label' => $this->string(255),
            'path' => $this->string(512),
            'mime' => $this->string(255),
            'content' => 'longText',
            'size' => $this->integer(),
            'to_process' => $this->tinyInteger()->defaultValue(0),
            'processing' => $this->tinyInteger()->defaultValue(0),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        // creates index for column `article_id`
        $this->createIndex(
            '{{%idx-article_file-article_id}}',
            '{{%article_files}}',
            'article_id'
        );

        // add foreign key for table `{{%articles}}`
        $this->addForeignKey(
            '{{%fk-article_file-article_id}}',
            '{{%article_files}}',
            'article_id',
            '{{%articles}}',
            'id',
            'CASCADE'
        );

        // creates index for column `created_by`
        $this->createIndex(
            '{{%idx-article_file-created_by}}',
            '{{%article_files}}',
            'created_by'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-article_file-created_by}}',
            '{{%article_files}}',
            'created_by',
            '{{%users}}',
            'id',
            'CASCADE'
        );

        // creates index for column `updated_by`
        $this->createIndex(
            '{{%idx-article_file-updated_by}}',
            '{{%article_files}}',
            'updated_by'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-article_file-updated_by}}',
            '{{%article_files}}',
            'updated_by',
            '{{%users}}',
            'id',
            'CASCADE'
        );
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201110_070324_drop_table_article_files cannot be reverted.\n";

        return false;
    }
    */
}
