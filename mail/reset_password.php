<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $user app\models\User */

$resetLink = Url::to(env('PORTAL_HOST') . '/auth/password-reset/' . $user->password_reset_token);
?>

<p><?php echo Yii::t('app', 'Hello') ?> <?= $user->username ?>,</p>

<p><?php echo Yii::t('app', 'Follow the link below to reset your password') ?>:</p>

<p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>

