<?php

use yii\db\Migration;

/**
 * Class m200914_120643_add_demo_user_profiles
 */
class m200914_120643_add_demo_user_profiles extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('{{%user_profiles}}', [
            'user_id' => 1,
            'first_name' => 'admin',
            'last_name' => 'admin',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%user_profiles}}', ['user_id' => 1]);
    }
}
