<?php

namespace app\modules\v1\workspaces\models\query;

use app\modules\v1\workspaces\models\TimelinePost;
use yii\db\ActiveQuery;


/**
 * Class TimelinePostQuery
 *
 * @package app\modules\v1\workspaces\models\query
 */
class TimelinePostQuery extends ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return TimelinePost[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return TimelinePost|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @param $id
     * @return TimelinePostQuery
     * @author Saiat Kalbiev <kalbievich11@gmail.com>
     */
    public function byWorkspaceId($id)
    {
        return $this->andWhere([TimelinePost::tableName() . '.workspace_id' => $id]);
    }

    /**
     * Get timeline posts which has file
     *
     * @return TimelinePostQuery
     */
    public function hasFile()
    {
        return $this->andWhere(['NOT', [TimelinePost::tableName() . '.file_path' => null]]);
    }
}
