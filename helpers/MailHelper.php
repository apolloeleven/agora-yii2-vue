<?php

namespace app\helpers;

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
     * @param $password
     * @return bool
     */
    public static function resetPassword($user, $password)
    {
        $message = Yii::$app->mailer->compose('reset_password', ['user' => $user, 'newPassword' => $password])
            ->setSubject(Yii::t('app', 'Your new password'))
            ->setTo($user->email);

        return self::sendMail($message);
    }

    /**
     * Send generated password to user
     *
     * @param $user
     * @param $password
     * @return bool
     */
    public static function generatedPassword($user, $password)
    {
        $message = \Yii::$app->mailer->compose('generated_password', ['user' => $user, 'password' => $password])
            ->setSubject(\Yii::t('app', 'Generate password for your account {name}', ['name' => \Yii::$app->name]))
            ->setTo($user->email);

        return self::sendMail($message);
    }
}