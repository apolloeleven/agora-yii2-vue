<?php

use yii\db\Migration;

/**
 * Class m200921_084115_build_authorization_data
 */
class m200921_084115_build_authorization_data extends Migration
{
    /**
     * {@inheritdoc}
     * @throws Exception
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        //Create direct permissions for portalUser
        $shareOnTimeline = $auth->createPermission('shareOnTimeline');
        $shareOnTimeline->description = 'Share On Timeline';
        $auth->add($shareOnTimeline);

        $leaveComment = $auth->createPermission('leaveComment');
        $leaveComment->description = 'Leave Comment';
        $auth->add($leaveComment);

        $like = $auth->createPermission('like');
        $like->description = 'Like/Unlike';
        $auth->add($like);

        $readArticle = $auth->createPermission('readArticle');
        $readArticle->description = 'Read Article';
        $auth->add($readArticle);

        //Create user role
        $user = $auth->createRole('user');
        $user->description = 'User';
        $auth->add($user);

        //Give user role permissions
        $auth->addChild($user, $shareOnTimeline);
        $auth->addChild($user, $leaveComment);
        $auth->addChild($user, $like);
        $auth->addChild($user, $readArticle);

        //Create direct permissions for workspaceAdmin
        $createWorkspace = $auth->createPermission('createWorkspace');
        $createWorkspace->description = 'Create Workspace';
        $auth->add($createWorkspace);

        $deleteWorkspace = $auth->createPermission('deleteWorkspace');
        $deleteWorkspace->description = 'Delete Workspace';
        $auth->add($deleteWorkspace);

        $updateWorkspace = $auth->createPermission('updateWorkspace');
        $updateWorkspace->description = 'Update Workspace';
        $auth->add($updateWorkspace);

        $addUser = $auth->createPermission('addUser');
        $addUser->description = 'Add User';
        $auth->add($addUser);

        $updateUser = $auth->createPermission('updateUser');
        $updateUser->description = 'Update User';
        $auth->add($updateUser);

        $deleteUser = $auth->createPermission('deleteUser');
        $deleteUser->description = 'Delete User';
        $auth->add($deleteUser);

        $createArticle = $auth->createPermission('createArticle');
        $createArticle->description = 'Create Article';
        $auth->add($createArticle);

        $updateArticle = $auth->createPermission('updateArticle');
        $updateArticle->description = 'Update Article';
        $auth->add($updateArticle);

        $deleteArticle = $auth->createPermission('deleteArticle');
        $deleteArticle->description = 'Delete Article';
        $auth->add($deleteArticle);

        $uploadAttachment = $auth->createPermission('uploadAttachment');
        $uploadAttachment->description = 'Upload Attachment';
        $auth->add($uploadAttachment);

        $deleteAttachment = $auth->createPermission('deleteAttachment');
        $deleteAttachment->description = 'Delete Attachment';
        $auth->add($deleteAttachment);

        //Create workspaceAdmin role
        $workspaceAdmin = $auth->createRole('workspaceAdmin');
        $workspaceAdmin->description = 'Workspace Admin';
        $auth->add($workspaceAdmin);

        //Give workspaceAdmin direct permissions
        $auth->addChild($workspaceAdmin, $createWorkspace);
        $auth->addChild($workspaceAdmin, $deleteWorkspace);
        $auth->addChild($workspaceAdmin, $updateWorkspace);
        $auth->addChild($workspaceAdmin, $addUser);
        $auth->addChild($workspaceAdmin, $updateUser);
        $auth->addChild($workspaceAdmin, $deleteUser);

        $auth->addChild($workspaceAdmin, $createArticle);
        $auth->addChild($workspaceAdmin, $updateArticle);
        $auth->addChild($workspaceAdmin, $deleteArticle);
        $auth->addChild($workspaceAdmin, $uploadAttachment);
        $auth->addChild($workspaceAdmin, $deleteAttachment);

        //Give workspaceAdmin all permissions of user
        $auth->addChild($workspaceAdmin, $user);

        //Create admin role
        $admin = $auth->createRole('admin');
        $admin->description = 'Global Admin';
        $auth->add($admin);

        //Give admin all permissions of portalAdmin
        $auth->addChild($admin, $workspaceAdmin);

        //Assign admin role to admin
        $auth->assign($admin, 1);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();
    }
}
