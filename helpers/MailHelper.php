<?php

namespace app\helpers;

use app\models\Invitation;
use app\models\User;
use Yii;
use yii\mail\MessageInterface;

/**
 * Class MailHelper
 */
class MailHelper
{
    /**
     * @param MessageInterface $message
     * @return bool
     */
    private static function sendMail(MessageInterface $message)
    {
        $message->setFrom(env('EMAIL_FROM'));
        if (env('EMAIL_CC')) {
            $message->setCc(env('EMAIL_CC'));
        }
        if (env('EMAIL_BCC')) {
            $message->setBcc(env('EMAIL_BCC'));
        }

        return $message->send();
    }

    /**
     * Send mail user about reset password
     *
     * @param $user
     * @return bool
     */
    public static function resetPassword($user)
    {
        $message = Yii::$app->mailer->compose('reset_password',
            [
                'user' => $user,
                'link' => env('PORTAL_HOST') . "/auth/password-reset/$user->password_reset_token"
            ])
            ->setSubject(Yii::t('app', 'Your new password'))
            ->setTo($user->email);

        return self::sendMail($message);
    }

    /**
     * When admin wants to invite user this method will be called
     *
     * @param Invitation $invitation
     * @return bool
     */
    public static function invitation(Invitation $invitation)
    {
        $message = Yii::$app->mailer->compose('user_invitation', ['model' => $invitation,])
            ->setSubject(Yii::t('app', 'You are invited to {name}', ['name' => Yii::$app->name]))
            ->setTo($invitation->email);

        return self::sendMail($message);
    }

    /**
     * Send mail inviter after user registered
     *
     * @param Invitation $invitation
     * @param User $user Newly registered user
     * @return bool
     */
    public static function acceptInvitation(Invitation $invitation, User $user)
    {
        $message = Yii::$app->mailer->compose('invitation_accepted', [
            'model' => $invitation,
            'user' => $user
        ])
            ->setSubject(Yii::t('app', 'Your invitation to join to {name} was accepted', ['name' => Yii::$app->name]))
            ->setTo($invitation->createdBy->email);

        return self::sendMail($message);
    }
}