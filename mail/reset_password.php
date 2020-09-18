<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $user app\models\User */
/* @var $link */
?>

<p><?php echo Yii::t('app', 'Hello') ?> <?= $user->username ?>,</p>

<p>
    <?php echo Yii::t('app', 'Follow the link below to reset your password') ?>
	<br>
    <?php echo Html::a(Html::encode(Yii::t('app', 'Password Reset')), Url::to($link)) ?>
</p>

