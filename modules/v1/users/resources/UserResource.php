<?php
/**
 * User: zura
 * Date: 15.09.20
 * Time: 20:05
 */

namespace app\modules\v1\users\resources;


use app\models\User;
use Yii;
use yii\db\ActiveQuery;

/**
 * Class UserResource
 *
 * @author  Zura Sekhniashvili <zurasekhniashvili@gmail.com>
 * @package app\modules\v1\users\resources
 */
class UserResource extends User
{
    public function fields()
    {
        return [
            'id',
            'username',
            'created_at' => function () {
                return Yii::$app->formatter->asDatetime($this->created_at);
            },
            'updated_at' => function () {
                return Yii::$app->formatter->asDatetime($this->updated_at);
            },
            'displayName' => function () {
                return $this->getDisplayName();
            },
            'access_token',
            'email',
            'status',
        ];
    }

    public function extraFields()
    {
        return ['userProfile'];
    }

    /**
     * @return ActiveQuery
     */
    public function getUserProfile()
    {
        return $this->hasOne(UserProfileResource::class, ['user_id' => 'id']);
    }
}