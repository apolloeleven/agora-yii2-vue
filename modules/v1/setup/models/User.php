<?php

namespace app\modules\v1\setup\models;

use app\modules\v1\setup\models\Invitation;
use app\modules\v1\setup\models\UserDepartment;
use app\modules\v1\setup\models\query\UserQuery;
use app\modules\v1\workspaces\models\UserWorkspace;
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
 * @property int              $id
 * @property string           $username
 * @property string           $email
 * @property string           $password_hash
 * @property string           $first_name
 * @property string           $last_name
 * @property string           $mobile
 * @property string           $phone
 * @property string           $birthday
 * @property string           $about_me
 * @property string           $hobbies
 * @property string           $image_path
 * @property string|null      $password_reset_token
 * @property int|null         $expire_date
 * @property string|null      $access_token
 * @property int|null         $access_token_expire_date
 * @property int|null         $status
 * @property string           $favourites
 * @property int|null         $created_at
 * @property int|null         $updated_at
 *
 * @property UserDepartment[] $userDepartments
 * @property UserWorkspace[]  $userWorkspaces
 * @property Invitation       $invitation
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;

    const ROLE_USER = 'user';
    const ROLE_ADMIN = 'admin';
    const ROLE_WORKSPACE_ADMIN = 'workspaceAdmin';


    const ACCESS_TOKEN_LIFETIME = 60 * 60 * 24; // 1 day

    /**
     * Password reset link is valid 48 hours
     */
    const EXPIRE_DATE = 3600 * 48;

    /**
     * @var \yii\web\UploadedFile
     */
    public $image;
    public $imageRemoved;

    public static function tableName()
    {
        return '{{%users}}';
    }

    public function rules()
    {
        return [
            [['username', 'email', 'password_hash'], 'required'],
            ['imageRemoved', 'boolean'],
            [['status', 'access_token_expire_date', 'created_at', 'updated_at', 'expire_date'], 'integer'],
            [['username'], 'string', 'max' => 255],
            [['email', 'access_token'], 'string', 'max' => 512],
            [['password_hash', 'password_reset_token'], 'string', 'max' => 1024],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['about_me', 'favourites'], 'string'],
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
            'favourites' => Yii::t('app', 'Favourites'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function fields()
    {
        return [
            'id',
            'username',
            'email',
            'first_name',
            'last_name',
            'display_name' => function () {
                return $this->getDisplayName();
            },
            'image_url' => function () {
                return $this->getImageUrl();
            },
            'status' => function () {
                return $this->status === 1;
            },
            'created_at' => function () {
                return Yii::$app->formatter->asDatetime($this->created_at);
            },
            'updated_at' => function () {
                return Yii::$app->formatter->asDatetime($this->updated_at);
            },
        ];
    }

    public function extraFields()
    {
        return ['userDepartments', 'userWorkspaces'];
    }

    public static function find()
    {
        return new UserQuery(get_called_class());
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
        if (!$this->access_token || $this->access_token_expire_date < time()) {
            $this->access_token = Yii::$app->security->generateRandomString(256);
            $this->access_token_expire_date = time() + self::ACCESS_TOKEN_LIFETIME;
        }
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
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * @return string|null
     * @author Saiat Kalbiev <kalbievich11@gmail.com>
     */
    public function getImageUrl()
    {
        return $this->image_path ? env('API_HOST') . $this->image_path : null;
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

    /**
     * @param bool $runValidation
     * @param null $attributeNames
     * @return bool
     * @throws Exception
     * @throws \yii\base\ErrorException
     * @author Saiat Kalbiev <kalbievich11@gmail.com>
     */
    public function save($runValidation = true, $attributeNames = null)
    {
        $this->hobbies = $this->hobbies ? Json::encode($this->hobbies) : null;
        $this->birthday = $this->birthday ? strtotime($this->birthday) : null; //@Todo format might be dynamic
        $oldPath = $this->image_path;

        if ($this->image) {
            $this->image_path = "/storage/user/" . Yii::$app->security->generateRandomString(25) . '/' . $this->image->name;

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
            $this->image->saveAs($path, false);
        } else if ($this->imageRemoved && $oldPath) {
            $oldPath = Yii::getAlias("@webroot" . $oldPath);
            if (file_exists($oldPath)) {
                FileHelper::removeDirectory(dirname($oldPath));
            }
            $this->image_path = null;
        }

        return parent::save($runValidation, $attributeNames);
    }

    /**
     * @param bool  $insert
     * @param array $changedAttributes
     * @author Saiat Kalbiev <kalbievich11@gmail.com>
     */
    public function afterSave($insert, $changedAttributes)
    {
        $this->hobbies = $this->hobbies ? Json::decode($this->hobbies) : [];
        $this->birthday = $this->birthday ? date("d-m-Y", $this->birthday) : null; // @todo must be reviewed

        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }

    /**
     * @return ActiveQuery
     */
    public function getUserDepartments()
    {
        return $this->hasMany(UserDepartment::class, ['user_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUserWorkspaces()
    {
        return $this->hasMany(UserWorkspace::class, ['user_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getInvitation()
    {
        return $this->hasOne(Invitation::class, ['user_id' => 'id']);
    }

    /**
     * @param $workspaceId
     * @return bool
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     */
    public function isInWorkspace($workspaceId): bool
    {
        return UserWorkspace::find()->byUserId($this->id)->byWorkspaceId($workspaceId)->exists();
    }

    /**
     * @return string[][]
     */
    public static function getUserRoles()
    {
        return [
            [
                'value' => self::ROLE_USER,
                'text' => Yii::t('app', 'User')
            ],
//            [
//                'value' => self::ROLE_ADMIN,
//                'text' => Yii::t('app', 'Admin')
//            ],
            [
                'value' => self::ROLE_WORKSPACE_ADMIN,
                'text' => Yii::t('app', 'Admin')
            ]
        ];
    }

    /**
     * Check if given role is valid or not
     *
     * @param $role
     * @return bool
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     */
    public static function isValidRole($role): bool
    {
        return in_array($role, [self::ROLE_ADMIN, self::ROLE_USER, self::ROLE_WORKSPACE_ADMIN]);
    }
}
