<?php

namespace app\models;

use app\models\query\UserProfileQuery;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\Json;

/**
 * This is the model class for table "user_profiles".
 *
 * @property int $user_id
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $phone
 * @property string|null $mobile
 * @property int|null $birthday
 * @property string|null $about_me
 * @property string|null $image_path
 *
 * @property User $user
 */
class UserProfile extends ActiveRecord
{
    /**
     * @var bool|mixed|null
     */
    public $image;
    public $image_deleted = false;

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
            [['user_id', 'first_name', 'last_name'], 'required'],
            [['birthday'], 'integer'],
            [['about_me'], 'string'],
            [['first_name', 'last_name', 'phone', 'mobile'], 'string', 'max' => 255],
            [['special_tasks'], 'string', 'max' => 512],
            [['image_path'], 'string', 'max' => 1024],
            [['image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpeg, svg, jpg'],
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
            'first_name' => Yii::t('app', 'First Name'),
            'last_name' => Yii::t('app', 'Last Name'),
            'phone' => Yii::t('app', 'Phone'),
            'mobile' => Yii::t('app', 'Mobile'),
            'birthday' => Yii::t('app', 'Birthday'),
            'about_me' => Yii::t('app', 'About Me'),
            'image_path' => Yii::t('app', 'Image Path'),
        ];
    }

    /**
     * @return UserProfile|ActiveQuery
     */
    public static function find()
    {
        return new UserProfileQuery(get_called_class());
    }

    /**
     * Gets query for [[User]].
     *
     * @return ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Get full user name
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * {@inheritdoc}
     * @return bool
     */
//    public function beforeValidate()
//    {
//        // Check if array attributes
//        $this->birthday = $this->birthday ? strtotime($this->birthday) : null;
//
//        return parent::beforeValidate();
//    }

    /**
     * After find UserProfile data we need to decode
     */
//    public function afterFind()
//    {
//        $this->birthday = $this->birthday ? date("d-m-Y", $this->birthday) : null;
//    }

    /**
     * Get user avatar
     *
     * @return bool|string
     */
    public function getAvatar()
    {
        //TODO add avatar path if not exist
//        return $this->image_path ? Yii::getAlias('@storageUrl' . $this->image_path) : '';
    }
}
