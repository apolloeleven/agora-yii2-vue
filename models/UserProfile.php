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
 * @property string|null $hometown
 * @property string|null $special_tasks
 * @property string|null $about_me
 * @property string|null $job_title
 * @property string|null $languages
 * @property string|null $expertise
 * @property string|null $department
 * @property string|null $area_director
 * @property string|null $position
 * @property string|null $country
 * @property string|null $image_path
 * @property string|array $department_position
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
            [['first_name', 'last_name', 'phone', 'mobile', 'special_tasks', 'hometown', 'position', 'country'], 'string', 'max' => 255],
            [['special_tasks'], 'string', 'max' => 512],
            [['job_title', 'languages', 'expertise', 'department', 'area_director', 'image_path'], 'string', 'max' => 1024],
            [['department_position'], 'string', 'max' => 2048],
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
            'special_tasks' => Yii::t('app', 'Special Tasks'),
            'hometown' => Yii::t('app', 'Hometown'),
            'about_me' => Yii::t('app', 'About Me'),
            'job_title' => Yii::t('app', 'Job Title'),
            'languages' => Yii::t('app', 'Languages'),
            'expertise' => Yii::t('app', 'Expertise'),
            'department' => Yii::t('app', 'Department'),
            'area_director' => Yii::t('app', 'Area Director'),
            'position' => Yii::t('app', 'Position'),
            'image_path' => Yii::t('app', 'Image Path'),
            'department_position' => Yii::t('app', 'Department Position'),
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
    public function beforeValidate()
    {
        // Check if array attributes
        $this->department = !empty($this->department) ? json_encode($this->department) : null;
        $this->languages = !empty($this->languages) ? json_encode($this->languages) : null;
        $this->expertise = !empty($this->expertise) ? json_encode($this->expertise) : null;
        $this->position = !empty($this->position) ? json_encode($this->position) : null;
        $this->country = !empty($this->country) ? json_encode($this->country) : null;
        $this->special_tasks = !empty($this->special_tasks) ? json_encode($this->special_tasks) : null;
        $this->department_position = !empty($this->department_position) ? Json::encode($this->department_position) : null;
        $this->birthday = $this->birthday ? strtotime($this->birthday) : null;

        return parent::beforeValidate();
    }

    /**
     * After find UserProfile data we need to decode
     */
    public function afterFind()
    {
        $this->department = json_decode($this->department);
        $this->languages = json_decode($this->languages);
        $this->expertise = json_decode($this->expertise);
        $this->position = json_decode($this->position);
        $this->country = json_decode($this->country);
        $this->special_tasks = json_decode($this->special_tasks);
        $this->department_position = Json::decode($this->department_position);
        $this->birthday = $this->birthday ? date("d-m-Y", $this->birthday) : null;
    }

    /**
     * Get user avatar
     *
     * @return bool|string
     */
    public function getAvatar()
    {
        //TODO add avatar path if not exist
        return $this->image_path ? Yii::getAlias('@storageUrl' . $this->image_path) : '';
    }
}
