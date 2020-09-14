<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user app\models\User */
/* @var $password string */

?>

<p>
    <?php echo Yii::t('app', 'Hello') ?> <b><?= $user->email ?></b>
</p>

<p>
    <?php echo Yii::t('app', 'Your password was changed by the') ?>
	<b><?= Html::encode($user->updatedBy->getDisplayName()) ?></b>.
</p>

<p>
    <?php echo Yii::t('app', 'You can now access the system with new password: ') ?>
    <?= Html::encode($password) ?>
</p>

<p>
    <?php echo Yii::t('app', 'Click here to login:') ?>
    <?php echo Html::a(getenv('PORTAL_HOST_INFO'), getenv('PORTAL_HOST_INFO')) ?>
</p>
