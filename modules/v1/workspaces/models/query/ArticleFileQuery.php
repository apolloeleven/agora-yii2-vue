<?php


namespace app\modules\v1\workspaces\models\query;

use app\modules\v1\workspaces\models\Article;
use app\modules\v1\workspaces\models\ArticleFile;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class ArticleFileQuery
 *
 * @package app\modules\v1\workspaces\models\query
 */
class ArticleFileQuery extends ActiveQuery
{
    /**
     * @param null $db
     * @return Article[]
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @param null $db
     * @return Article|array|ActiveRecord
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @param $id
     * @return ArticleFileQuery
     */
    public function byId($id)
    {
        return $this->andWhere([ArticleFile::tableName() . '.id' => $id]);
    }

    /**
     * @param $id
     * @return ArticleFileQuery
     */
    public function byArticleId($id)
    {
        return $this->andWhere([ArticleFile::tableName() . '.article_id' => $id]);
    }

    /**
     * @param $name
     * @return ArticleFileQuery
     */
    public function byName($name)
    {
        return $this->andWhere([ArticleFile::tableName() . '.name' => $name]);
    }
}