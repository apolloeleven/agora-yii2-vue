<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_profiles}}`.
 */
class m200922_072549_add_profile_fields_to_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%users}}', 'first_name', $this->string(255)->after('password_hash'));
        $this->addColumn('{{%users}}', 'last_name', $this->string(255)->after('first_name'));
        $this->addColumn('{{%users}}', 'mobile', $this->string(255)->after('last_name'));
        $this->addColumn('{{%users}}', 'phone', $this->string(255)->after('mobile'));
        $this->addColumn('{{%users}}', 'birthday', $this->integer(11)->after('phone'));
        $this->addColumn('{{%users}}', 'about_me', $this->text()->after('birthday'));
        $this->addColumn('{{%users}}', 'hobbies', $this->string(255)->after('about_me'));
        $this->addColumn('{{%users}}', 'image_path', $this->string(255)->after('hobbies'));

        $this->update('{{%users}}', [
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
        ], ['id' => 1]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%users}}', 'first_name');
        $this->dropColumn('{{%users}}', 'last_name');
        $this->dropColumn('{{%users}}', 'mobile');
        $this->dropColumn('{{%users}}', 'phone');
        $this->dropColumn('{{%users}}', 'birthday');
        $this->dropColumn('{{%users}}', 'about_me');
        $this->dropColumn('{{%users}}', 'hobbies');
        $this->dropColumn('{{%users}}', 'image_path');

        $this->dropTable('{{%user_profiles}}');
    }
}
