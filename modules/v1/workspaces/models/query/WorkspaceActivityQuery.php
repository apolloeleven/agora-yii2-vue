<?php

namespace app\modules\v1\workspaces\models\query;

/**
 * This is the ActiveQuery class for [[\app\modules\v1\workspaces\models\WorkspaceActivity]].
 *
 * @see \app\modules\v1\workspaces\models\WorkspaceActivity
 */
class WorkspaceActivityQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \app\modules\v1\workspaces\models\WorkspaceActivity[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \app\modules\v1\workspaces\models\WorkspaceActivity|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
