<?php
/** @var Invitation $model */

/** @var User $user */

use app\models\Invitation;
use app\models\User;
use yii\helpers\Html;

?>

<p><?php echo Yii::t('app', 'Hello {name}', ['name' => $model->createdBy->email]) ?></p>
<p>
    <?php echo Yii::t('app', 'The email: {email} has accepted your invitation and joined to intranet.', [
        'email' => $model->email,
    ]) ?>
</p>
<p><?php echo Yii::t('app', 'Please {link} and activate the user to be able to login.', [
        'link' => Html::a(Yii::t('common', 'login into the intranet'), env('PORTAL_HOST'))
    ]) ?>
</p>
