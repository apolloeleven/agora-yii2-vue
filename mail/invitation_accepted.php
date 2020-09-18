<?php

use app\models\Invitation;
use app\models\User;

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
<br>
<p>
    <?php echo Yii::t('app', 'Now you can activate the user to be able to login.') ?>
</p>
