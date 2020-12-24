<?php
/**
 * User: zura
 * Date: 15.09.20
 * Time: 20:05
 */

namespace app\modules\v1\users\resources;


use app\modules\v1\setup\models\User;

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
        return array_merge(parent::fields(), [
            'mobile', 'phone', 'birthday', 'about_me', 'hobbies',
        ]);
    }
}