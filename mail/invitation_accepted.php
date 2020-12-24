<?php

use app\modules\v1\users\models\User;
use app\modules\v1\setup\models\Invitation;

/**
 * @var Invitation $model
 * @var User $user
 */

?>

<p>
    <?php echo Yii::t('app', 'Hello {name}',
        [
            'name' => $model->createdBy->email
        ])
    ?>
</p>
<p>
    <?php echo Yii::t('app', 'The email: {email} has accepted your invitation and joined to intranet.',
        [
            'email' => $model->email,
        ])
    ?>
</p>
<p>
    <?php echo Yii::t('app', 'Now you can activate the user to be able to login.') ?>
</p>
