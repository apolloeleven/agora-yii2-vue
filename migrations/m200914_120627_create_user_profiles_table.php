<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_profiles}}`.
 */
class m200914_120627_create_user_profiles_table extends Migration
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
            'phone' => $this->string(255),
            'mobile' => $this->string(255),
            'birthday' => $this->integer(11),
            'special_tasks' => $this->string(512),
            'hometown' => $this->string(255),
            'about_me' => $this->text(),
            'job_title' => $this->string(1024),
            'languages' => $this->string(1024),
            'expertise' => $this->string(1024),
            'department' => $this->string(1024),
            'area_director' => $this->string(1024),
            'position' => $this->string(255),
            'country' => $this->string(255),
            'image_path' => $this->string(1024),
            'department_position' => $this->string(2048),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-user_profile-user_id}}',
            '{{%user_profiles}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-user_profile-user_id}}',
            '{{%user_profiles}}',
            'user_id',
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
            '{{%fk-user_profile-user_id}}',
            '{{%user_profiles}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-user_profile-user_id}}',
            '{{%user_profiles}}'
        );

        $this->dropTable('{{%user_profiles}}');
    }
}
