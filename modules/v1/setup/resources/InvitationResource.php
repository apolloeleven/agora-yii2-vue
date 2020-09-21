<?php


namespace app\modules\v1\setup\resources;


use app\models\Invitation;
use Yii;
use yii\db\ActiveQuery;

/**
 * Class InvitationResource
 *
 * @package app\modules\v1\setup\resources
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
}