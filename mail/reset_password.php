<?php

use app\modules\v1\users\models\User;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $user User */
?>

<p><?php echo Yii::t('app', 'Hello') ?> <?= $user->username ?>,</p>

<p>
    <?php echo Yii::t('app', 'Follow the link below to reset your password') ?>
	<br>
    <?php echo Html::a(Html::encode(Yii::t('app', 'Password Reset')), "@portalUrl/auth/password-reset/$user->password_reset_token") ?>
</p>

