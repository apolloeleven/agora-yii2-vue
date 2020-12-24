<?php
/**
 * User: zura
 * Date: 28.09.20
 * Time: 21:31
 */

namespace app\modules\v1\users\models;


use app\modules\v1\setup\models\User;
use yii\base\Model;

/**
 * Class ChangePassword
 *
 * @author  Zura Sekhniashvili <zurasekhniashvili@gmail.com>
 * @package app\models
 */
class ChangePassword extends Model
{
    public $old_password;
    public $password;
    public $confirm_password;

    /**
     * @var User
     */
    public $user;

    public function rules()
    {
        return [
            [['old_password', 'password', 'confirm_password'], 'required'],
            [
                'password',
                'match',
                'pattern' => '/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/',
                'message' => \Yii::t('app', 'Should contain at least 1 lower case, 1 upper case, 1 digit and min length of 8')
            ],
            ['old_password', 'validatePassword'],
            ['password', 'compare', 'compareAttribute' => 'confirm_password']
        ];
    }

    public function attributeLabels()
    {
        return [
            'password' => \Yii::t('app', 'Password'),
            'confirm_password' => \Yii::t('app', 'Confirm Password'),
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword(string $attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->old_password)) {
                $this->addError($attribute, \Yii::t('app', 'Password is incorrect'));
            }
        }
    }

    /**
     * @return mixed
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     */
    public function getUser()
    {
        return $this->user;
    }

    public function changePassword()
    {
        $user = $this->getUser();
        $user->password_hash = \Yii::$app->security->generatePasswordHash($this->password);
        return $user->save();
    }
}