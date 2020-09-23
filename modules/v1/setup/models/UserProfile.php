<?php

namespace app\modules\v1\setup\models;

use app\models\User;
use Yii;

/**
 * This is the model class for table "user_profile".
 *
 * @property int $user_id
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $mobile
 * @property string|null $phone
 * @property int|null $birthday
 * @property string|null $about_me
 * @property string|null $hobbies
 * @property string|null $image_path
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 *
 * @property User $user
 */
class UserProfile extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_profiles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['birthday', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['about_me'], 'string'],
            [['first_name', 'last_name', 'mobile', 'phone'], 'string', 'max' => 255],
            [['hobbies', 'image_path'], 'string', 'max' => 1024],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'mobile' => 'Mobile',
            'phone' => 'Phone',
            'birthday' => 'Birthday',
            'about_me' => 'About Me',
            'hobbies' => 'Hobbies',
            'image_path' => 'Image Path',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }
    /**
     * Find user profile with id
     *
     * @return UserProfile
     * @author Levan Gogoladze <levanma98@gmail.com>
     */
    public function getProfile($id)
    {
        return UserProfile::findOne($id);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
