<?php


namespace app\models;


use app\helpers\MailHelper;
use Yii;
use yii\base\Exception;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%invitations}}".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $email
 * @property int|null $status
 * @property string|null $token
 * @property int|null $expire_date
 * @property int|null $use_date
 * @property int|null $created_at
 * @property int|null $created_by
 *
 * @property User $createdBy
 * @property User $user
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
                'updatedAtAttribute' => false
            ],
            [
                'class' => BlameableBehavior::class,
                'updatedByAttribute' => false
            ]
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
     * Return fields for frontend
     *
     * @return array
     */
    public function fields()
    {
        return array_merge(parent::fields(), [
            'created_at' => function () {
                return $this->created_at * 1000;
            },
            'use_date' => function () {
                return $this->use_date ? Yii::$app->formatter->asDatetime($this->use_date) : null;
            },
            'statusLabel' => function () {
                return $this->getStatusLabel();
            }
        ]);
    }

    /**
     * Extra fields with relation
     *
     * @return array|string[]
     */
    public function extraFields()
    {
        return ['createdBy', 'user'];
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

    /**
     * @param bool $insert
     * @return bool
     * @throws Exception
     */
    public function beforeSave($insert)
    {
        if ($insert) {
            $this->status = self::STATUS_PENDING;
            $this->token = Yii::$app->security->generateRandomString(256);
            $this->expire_date = time() + self::TOKEN_LIFETIME;
        }

        return parent::beforeSave($insert);
    }

    /**
     * After save send invitation email to user
     *
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($insert) {
            MailHelper::invitation($this);
        }
    }

    /**
     * Find invitation by valid token
     *
     * @param $token
     * @return array|ActiveRecord|null
     */
    public static function findByToken($token)
    {
        return static::find()
            ->andWhere(['token' => $token, 'use_date' => null])
            ->andWhere(['>', 'expire_date', time()])
            ->one();
    }

    /**
     * @return array
     */
    public function getStatusLabels()
    {
        return [
            self::STATUS_PENDING => Yii::t('app', 'Pending'),
            self::STATUS_REGISTERED => Yii::t('app', 'Registered'),
            self::STATUS_COMPLETED => Yii::t('app', 'Completed'),
        ];
    }

    /**
     * @return mixed
     */
    public function getStatusLabel()
    {
        return self::getStatusLabels()[$this->status];
    }
}