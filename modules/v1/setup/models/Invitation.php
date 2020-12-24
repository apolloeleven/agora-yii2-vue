<?php


namespace app\modules\v1\setup\models;


use app\modules\v1\users\models\query\InvitationQuery;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%invitations}}".
 *
 * @property int         $id
 * @property int|null    $user_id
 * @property string|null $email
 * @property int|null    $status
 * @property string|null $token
 * @property int|null    $expire_date
 * @property int|null    $use_date
 * @property int|null    $created_at
 * @property int|null    $created_by
 *
 * @property User        $createdBy
 * @property User        $user
 */
class Invitation extends ActiveRecord
{
    const STATUS_REGISTERED = 1;
    const STATUS_PENDING = 2;
    const STATUS_COMPLETED = 3;

    /**
     * Invitation token is valid 30 days
     */
    const TOKEN_LIFETIME = 3600 * 30 * 24;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%invitations}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'updatedAtAttribute' => false,
            ],
            [
                'class' => BlameableBehavior::class,
                'updatedByAttribute' => false,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email',], 'required'],
            [['status', 'created_at', 'expire_date', 'use_date', 'created_by', 'user_id'], 'integer'],
            ['status', 'default', 'value' => self::STATUS_PENDING],
            ['token', 'default', 'value' => Yii::$app->security->generateRandomString(256)],
            ['expire_date', 'default', 'value' => time() + self::TOKEN_LIFETIME],
            [['email'], 'string', 'max' => 255],
            [['token'], 'string', 'max' => 1024],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'email' => Yii::t('app', 'Email'),
            'status' => Yii::t('app', 'Status'),
            'token' => Yii::t('app', 'Token'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
    }

    /**
     * @return InvitationQuery|ActiveQuery
     */
    public static function find()
    {
        return new InvitationQuery(get_called_class());
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
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
}