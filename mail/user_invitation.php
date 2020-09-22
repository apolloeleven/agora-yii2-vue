<?php

use app\models\Invitation;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var Invitation $model */
/* @var $link */
?>

<p><?php echo Yii::t('app', 'Hello {name}', ['name' => $model->email]) ?></p>
<p>
    <?php echo Yii::t('app', 'You were invited into {name} by {inviterName}', [
        'name' => Yii::$app->name,
        'inviterName' => $model->createdBy->getDisplayName()
    ]) ?>
</p>
<p>
    <?php echo Yii::t('app', 'Click the link bellow to register') ?>
	<br>
    <?php echo Html::a(Html::encode(Yii::t('app', 'Registration Link')), $link) ?>
</p>



