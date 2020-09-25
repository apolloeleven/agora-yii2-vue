<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_profiles}}`.
 */
class m200922_072549_create_user_profiles_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_profiles}}', [
            'user_id' => $this->primaryKey(),
            'first_name' => $this->string(255),
            'last_name' => $this->string(255),
            'mobile' => $this->string(255),
            'phone' => $this->string(255),
            'birthday' => $this->integer(11),
            'about_me' => $this->text(),
            'hobbies' => $this->string(1024),
            'image_path' => $this->string(1024),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
            'created_by' => $this->integer(11),
            'updated_by' => $this->integer(11),
        ]);

        $this->addForeignKey(
            '{{%fk-user_profile-user_id}}',
            '{{%user_profiles}}',
            'user_id',
            '{{%users}}',
            'id',
            'CASCADE'
        );

        // creates index for column `created_by`
        $this->createIndex(
            '{{%idx-user_profiles-created_by}}',
            '{{%user_profiles}}',
            'created_by'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-user_profiles-created_by}}',
            '{{%user_profiles}}',
            'created_by',
            '{{%users}}',
            'id',
            'CASCADE'
        );

        // creates index for column `updated_by`
        $this->createIndex(
            '{{%idx-user_profiles-updated_by}}',
            '{{%user_profiles}}',
            'updated_by'
        );

        // add foreign key for table `{{%users}}`
        $this->addForeignKey(
            '{{%fk-user_profiles-updated_by}}',
            '{{%user_profiles}}',
            'updated_by',
            '{{%users}}',
            'id',
            'CASCADE'
        );

        // Insert user for id 1 for user profile

        $this->insert('{{%user_profiles}}', [
            'user_id' => 1,
            'first_name' => 'Apollo',
            'last_name' => '11',
            'mobile' => null,
            'phone' => null,
            'birthday' => null,
            'about_me' => null,
            'hobbies' => null,
            'image_path' => null,
            'created_at' => time(),
            'updated_at' => time(),
            'created_by' => 1,
            'updated_by' => 1,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-user_profiles-created_by}}',
            '{{%user_profiles}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            '{{%idx-user_profiles-created_by}}',
            '{{%user_profiles}}'
        );

        // drops foreign key for table `{{%users}}`
        $this->dropForeignKey(
            '{{%fk-user_profiles-updated_by}}',
            '{{%user_profiles}}'
        );

        // drops index for column `updated_by`
        $this->dropIndex(
            '{{%idx-user_profiles-updated_by}}',
            '{{%user_profiles}}'
        );

        $this->dropForeignKey(
            '{{%fk-user_profile-user_id}}',
            '{{%user_profiles}}'
        );

        $this->dropTable('{{%user_profiles}}');
    }
}
