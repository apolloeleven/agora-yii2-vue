<?php

namespace app\models;

use app\models\query\UserQuery;
use app\modules\v1\users\models\UserDepartment;
use Yii;
use yii\base\Exception;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;
use yii\helpers\Json;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "{{%users}}".
 *
 * @property int         $id
 * @property string      $username
 * @property string      $email
 * @property string      $password_hash
 * @property string      $first_name
 * @property string      $last_name
 * @property string      $mobile
 * @property string      $phone
 * @property string      $birthday
 * @property string      $about_me
 * @property string      $hobbies
 * @property string      $image_path
 * @property string|null $password_reset_token
 * @property int|null    $expire_date
 * @property string|null $access_token
 * @property int|null    $status
 * @property int|null    $created_at
 * @property int|null    $updated_at
 * @property UserDepartment[] $userDepartments
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;

    /**
     * Password reset link is valid 48 hours
     */
    const EXPIRE_DATE = 3600 * 48;

    /**
     * @var \yii\web\UploadedFile
     */
    public $image;

    public static function tableName()
    {
        return '{{%users}}';
    }

    public function rules()
    {
        return [
            [['username', 'email', 'password_hash'], 'required'],
            [['status', 'created_at', 'updated_at', 'expire_date'], 'integer'],
            [['username'], 'string', 'max' => 255],
            [['email', 'access_token'], 'string', 'max' => 512],
            [['password_hash', 'password_reset_token'], 'string', 'max' => 1024],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['about_me'], 'string'],
            [['first_name', 'last_name', 'mobile', 'phone'], 'string', 'max' => 255],
            [['hobbies', 'image_path'], 'string', 'max' => 1024],
            [['image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpeg, svg, jpg'],
            ['birthday', 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'email' => Yii::t('app', 'Email'),
            'password_hash' => Yii::t('app', 'Password Hash'),
            'first_name' => Yii::t('app', 'First Name'),
            'last_name' => Yii::t('app', 'Last Name'),
            'mobile' => Yii::t('app', 'Mobile'),
            'phone' => Yii::t('app', 'Phone'),
            'birthday' => Yii::t('app', 'Birthday'),
            'about_me' => Yii::t('app', 'About Me'),
            'hobbies' => Yii::t('app', 'Hobbies'),
            'image_path' => Yii::t('app', 'Image Path'),
            'password_reset_token' => Yii::t('app', 'Password Reset Token'),
            'expire_date' => Yii::t('app', 'Expire Date'),
            'access_token' => Yii::t('app', 'Access Token'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public static function find()
    {
        return new UserQuery(get_called_class());
    }

    /**
     * @return ActiveQuery
     */
    public function getUserProfile()
    {
        return $this->hasOne(UserProfile::class, ['user_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return self::findOne(['access_token' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername(string $username)
    {
        return self::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return null;
//        throw new InvalidCallException('Method is not implemented');
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return false;
//        throw new InvalidCallException('Method is not implemented');
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword(string $password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generate Access Token
     *
     * @throws Exception
     */
    public function generateAccessToken()
    {
        $this->access_token = Yii::$app->security->generateRandomString(256);
    }

    /**
     * Get user API data
     *
     * @return array
     */
    public function getApiData()
    {
        return $this->toArray(['id', 'username', 'email', 'access_token', 'status', 'created_at', 'updated_at']);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken(string $token)
    {
        $user = static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);

        if (!$user || !$user->isPasswordResetTokenValid($user->expire_date)) {
            return null;
        }

        return $user;
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param int $expireDate
     * @return bool
     */
    public function isPasswordResetTokenValid(int $expireDate)
    {
        return self::EXPIRE_DATE + $expireDate >= time();
    }

    /**
     * @return bool
     */
    public function isInactive()
    {
        return $this->status == self::STATUS_INACTIVE;
    }

    /**
     * @return mixed
     */
    public function getDisplayName()
    {
        return $this->userProfile->getFullName();
    }

    public function getRoles()
    {
        $auth = Yii::$app->authManager;
        return $auth->getRolesByUser($this->id);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     * @throws Exception
     */
    public function setPassword(string $password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->hobbies = $this->hobbies ? Json::decode($this->hobbies) : [];
        $this->birthday = $this->birthday ? date("d-m-Y", $this->birthday) : null; // @todo must be reviewed
    }

    public function save($runValidation = true, $attributeNames = null)
    {
        $this->hobbies = $this->hobbies ? Json::encode($this->hobbies) : null;
        $this->birthday = $this->birthday ? strtotime($this->birthday) : null; //@Todo format might be dynamic
        $oldPath = $this->image_path;
        if ($this->image) {
            $this->image_path = "/storage/user/" . Yii::$app->security->generateRandomString(25) . '/' . $this->image->name;
        }
        $check = parent::save($runValidation, $attributeNames);
        $this->hobbies = $this->hobbies ? Json::decode($this->hobbies) : [];
        $this->birthday = $this->birthday ? date("d-m-Y", $this->birthday) : null; // @todo must be reviewed
        if (!$check) {
            return $check;
        }

        if ($this->image) {
            // Delete old image if it exists
            if ($oldPath) {
                $oldPath = Yii::getAlias("@webroot" . $oldPath);
                if (file_exists($oldPath)) {
                    FileHelper::removeDirectory(dirname($oldPath));
                }
            }

            $path = Yii::getAlias("@webroot") . $this->image_path;
            if (!is_dir(dirname($path))) {
                FileHelper::createDirectory(dirname($path));
            }
            $this->image->saveAs($path);
        }

        return $check;
    }

    /**
     * @return ActiveQuery
     */
    public function getUserDepartments()
    {
        return $this->hasMany(UserDepartment::class, ['user_id' => 'id']);
    }
}
