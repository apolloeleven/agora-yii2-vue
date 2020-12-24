<?php
/**
 * User: zura
 * Date: 15.09.20
 * Time: 20:05
 */

namespace app\modules\v1\setup\resources;


use app\modules\v1\users\models\User;

/**
 * Class UserResource
 *
 * @author  Zura Sekhniashvili <zurasekhniashvili@gmail.com>
 * @package app\modules\v1\setup\resources
 */
class UserProfileResource extends User
{
    public function fields()
    {
        return [
            'id', 'email', 'username', 'first_name', 'last_name', 'mobile', 'phone', 'birthday', 'about_me', 'hobbies',
            'image_url' => function () {
                return $this->getImageUrl();
            },
            'display_name' => function () {
                return $this->first_name . ' ' . $this->last_name;
            }
        ];
    }
}