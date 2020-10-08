<?php


namespace app\modules\v1\users\resources;


use app\helpers\MailHelper;
use app\modules\v1\users\models\Invitation;
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

            if (InvitationResource::find()->byEmail($this->email)->count()) {
                throw new ValidationException((Yii::t('app', 'Invitation already sent')));
            }
            if (UserResource::find()->byEmail($this->email)->active()->count()) {
                throw new ValidationException((Yii::t('app', 'User already exists')));
            }
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

        if ($insert) MailHelper::sendInvitation($this);
    }

    /**
     * Find invitation by valid token
     *
     * @param $token
     * @return \app\modules\v1\users\resources\InvitationResource|array|ActiveRecord|null
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