<?php
/** @var Invitation $model */

use app\models\Invitation;

?>

<p><?php echo Yii::t('app', 'Hello {name}', ['name' => $model->email]) ?></p>
<p>
    <?php echo Yii::t('app', 'You were invited into {name}', [
        'name' => Yii::$app->name
    ]) ?>
</p>
<p><?php echo Yii::t('app', 'Click the link bellow to register') ?>
	<br>
	<a href="<?php echo env('PORTAL_HOST') . '/auth/register/' . $model->token ?>">
      <?php echo Yii::t('app', 'Registration Link') ?>
	</a>
</p>
