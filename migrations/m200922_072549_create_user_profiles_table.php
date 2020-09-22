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
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            '{{%fk-user_profile-user_id}}',
            '{{%user_profiles}}'
        );

        $this->dropTable('{{%user_profiles}}');
    }
}
