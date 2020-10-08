<?php

namespace app\modules\v1\users\models;


use app\helpers\MailHelper;
use app\rest\ValidationException;
use Yii;
use yii\base\Exception;
use yii\base\Model;

/**
 * Class SignupForm
 *
 * @package app\models
 */
class SignupForm extends Model
{
    public $email;
    public $firstname;
    public $lastname;
    public $password;
    public $password_repeat;

    /**
     * {@inheritDoc}
     */
    public function rules()
    {
        return [
            [['firstname', 'lastname'], 'string', 'max' => 255],
            [['password'], 'string', 'min' => 6],
            ['email', 'filter', 'filter' => 'trim'],
            [['email', 'firstname', 'lastname'], 'required'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('app', 'Username'),
            'email' => Yii::t('app', 'E-mail'),
            'firstname' => Yii::t('app', 'Firstname'),
            'lastname' => Yii::t('app', 'Lastname'),
            'Password' => Yii::t('app', 'Password'),
        ];
    }

    /**
     * Signs user up.
     *
     * @param Invitation $invitation
     * @return User|null the saved model or null if saving fails
     * @throws Exception
     * @throws \yii\db\Exception
     */
    public function signup(Invitation $invitation)
    {
        $dbTransaction = Yii::$app->db->beginTransaction();
        // Save user
        $user = new User();
        $user->username = $this->email;
        $user->email = $this->email;
        $user->status = User::STATUS_INACTIVE;
        $user->first_name = $this->firstname;
        $user->last_name = $this->lastname;
        $user->setPassword($this->password);
        if (!$user->save()) {
            $dbTransaction->rollBack();
            throw new ValidationException(Yii::t('app', "User was not saved for email $this->email"));
        };

        // TODO Assign role to user

        // Update invitation object
        $invitation->use_date = time();
        $invitation->user_id = $user->id;
        $invitation->status = Invitation::STATUS_REGISTERED;
        if (!$invitation->save()) {
            $dbTransaction->rollBack();
            throw new ValidationException(Yii::t('app', "Invitation was not updated. Token: $invitation->token"));
        }

        if (!MailHelper::acceptInvitation($invitation, $user)) {
            $dbTransaction->rollBack();
            throw new ValidationException(Yii::t('app', 'Unable to send email for inviter'));
        }

        $dbTransaction->commit();
        return $user;
    }

}