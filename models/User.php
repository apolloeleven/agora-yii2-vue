<?php

namespace app\models;

use app\models\query\UserQuery;
use Yii;
use yii\base\Exception;
use yii\base\InvalidCallException;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "{{%users}}".
 *
 * @property int $id
 * @property string $username
 * @property string $email
 * @property string $password_hash
 * @property string $auth_key
 * @property string|null $password_reset_token
 * @property int|null $expire_date
 * @property string|null $access_token
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 * @property int|null $deleted_at
 * @property int|null $deleted_by
 *
 * @property UserProfile $userProfile
 * @property User $updatedBy
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;

    /**
     * Password reset link is valid 48 hours
     */
    const EXPIRE_DATE = 3600 * 48;

    public static function tableName()
    {
        return '{{%users}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            BlameableBehavior::class,
            'access_token' => [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'access_token'
                ],
                'value' => function () {
                    return Yii::$app->getSecurity()->generateRandomString(40);
                }
            ],
        ];
    }

    public function rules()
    {
        return [
            [['username', 'email', 'password_hash'], 'required'],
            [['status', 'created_at', 'updated_at', 'expire_date', 'deleted_at'], 'integer'],
            [['username'], 'string', 'max' => 255],
            [['email', 'access_token'], 'string', 'max' => 512],
            [['password_hash', 'password_reset_token'], 'string', 'max' => 1024],
            [['username'], 'unique'],
            [['email'], 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'email' => Yii::t('app', 'Email'),
            'password_hash' => Yii::t('app', 'Password Hash'),
            'password_reset_token' => Yii::t('app', 'Password Reset Token'),
            'expire_date' => Yii::t('app', 'Expire Date'),
            'access_token' => Yii::t('app', 'Access Token'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
        ];
    }

    public static function find()
    {
        return new UserQuery(get_called_class());
    }

    /**
     * Return user data with userprofile for frontend
     *
     * @return array
     */
    public function fields()
    {
        return [
            'id',
            'username',
//            'avatar' => function () {
//                return $this->userProfile->getAvatar();
//            },
            'created_at' => function () {
                return Yii::$app->formatter->asDatetime($this->created_at);
            },
            'updated_at' => function () {
                return Yii::$app->formatter->asDatetime($this->updated_at);
            },
//            'displayName' => function () {
//                return $this->getDisplayName();
//            },
            'email',
            'status',
            'access_token',
            //TODO roles field
        ];
    }

    /**
     * Implement extra fields
     *
     * @return array|string[]
     */
    public function extraFields()
    {
        return ['userProfile', 'updatedBy', 'createdBy'];
    }

    /**
     * @return ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'updated_by']);
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
     * Get users full name
     *
     * @return string
     */
    public function getDisplayName()
    {
        return $this->userProfile->getFullName();
    }

    /**
     * Mark the user deleted, by setting deleted_at and deleted_by columns
     *
     * @return $this
     */
    public function markDeleted()
    {
        $this->username = '[DELETED_' . $this->id . ']';
        $this->email = 'DELETED_' . $this->id . '@deleted.com';
        $this->access_token = '[DELETED]';
        $this->status = User::STATUS_INACTIVE;
        $this->deleted_at = time();
        $this->deleted_by = Yii::$app->user->id;
        return $this;
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param $password
     * @throws Exception
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

}
