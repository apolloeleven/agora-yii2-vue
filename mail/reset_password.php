<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $user app\models\User */
?>

<p><?php echo Yii::t('app', 'Hello') ?> <?= $user->username ?>,</p>

<p>
    <?php echo Yii::t('app', 'Follow the link below to reset your password') ?>
	<br>
    <?php echo Html::a(Html::encode(Yii::t('app', 'Password Reset')),
        Url::to('@portalUrl/auth/password-reset/' . $user->password_reset_token)) ?>
</p>

