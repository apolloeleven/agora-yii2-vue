<?php


namespace app\modules\v1\setup\resources;


use app\helpers\MailHelper;
use app\modules\v1\setup\resources\UserResource;
use app\modules\v1\setup\models\Invitation;
use app\modules\v1\setup\models\User;
use app\rest\ValidationException;
use Yii;
use yii\base\Exception;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class InvitationResource
 *
 * @package app\modules\v1\users\resources
 */
class InvitationResource extends Invitation
{
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

    public function rules()
    {
        return array_merge(parent::rules(), [
            [
                'email', 'unique', 'targetClass' => User::class,
                'when' => function($model) {
                    /** @var $model self */
                    return $model->isNewRecord;
                },
                'message' => Yii::t('app', 'User with this email already exists')
            ],
        ]);
    }

    /**
     * @return ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(UserResource::class, ['id' => 'created_by']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(UserResource::class, ['id' => 'user_id']);
    }

    /**
     * Find invitation by valid token
     *
     * @param $token
     * @return \app\modules\v1\setup\resources\InvitationResource|array|ActiveRecord|null
     */
    public static function findByToken($token)
    {
        return static::find()
            ->byToken($token)
            ->notUsed()
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