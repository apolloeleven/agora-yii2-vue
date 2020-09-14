<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user app\models\User */
/* @var $newPassword string */

?>

<p><?php echo Yii::t('app', 'Hello') ?> <?= $user->username ?>,</p>

<p><?php echo Yii::t('app', 'you have requested a new password for the Agora Intranet Platform.') ?></p>

<p><?php echo Yii::t('app', 'Your new password') ?>:<?= Html::encode($newPassword) ?></p>

<p><?php echo Yii::t('app', 'Please change your temporary password after your first login.') ?></p>

