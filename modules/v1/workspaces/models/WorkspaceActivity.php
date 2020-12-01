<?php

namespace app\modules\v1\workspaces\models;

use app\modules\v1\users\models\query\UserQuery;
use app\modules\v1\users\models\User;
use app\modules\v1\workspaces\models\query\WorkspaceActivityQuery;
use app\modules\v1\workspaces\models\query\WorkspaceQuery;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%workspace_activity}}".
 *
 * @property int $id
 * @property int|null $workspace_id
 * @property string|null $table_name
 * @property int|null $content_id
 * @property string|null $action
 * @property string|null $description
 * @property string|null $data
 * @property string|null $parent_identity
 * @property int|null $created_at
 * @property int|null $created_by
 *
 * @property User $createdBy
 * @property Workspace $workspace
 */
class WorkspaceActivity extends ActiveRecord
{
    public const ACTION_INSERT = 'create';
    public const ACTION_UPDATE = 'update';
    public const ACTION_DELETE = 'delete';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%workspace_activity}}';
    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            [
                'class' => TimestampBehavior::class,
                'updatedAtAttribute' => false,
            ],

            [
                'class' => BlameableBehavior::class,
                'updatedByAttribute' => false,
            ],
        ]);
    }

    public function fields()
    {
        return array_merge(parent::fields(), ['createdBy']);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['workspace_id', 'content_id', 'created_at', 'created_by'], 'integer'],
            [['description', 'data', 'parent_identity'], 'string'],
            [['table_name', 'action'], 'string', 'max' => 128],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['workspace_id'], 'exist', 'skipOnError' => true, 'targetClass' => Workspace::className(), 'targetAttribute' => ['workspace_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'workspace_id' => Yii::t('app', 'Workspace ID'),
            'table_name' => Yii::t('app', 'Table Name'),
            'content_id' => Yii::t('app', 'Content ID'),
            'action' => Yii::t('app', 'Action'),
            'description' => Yii::t('app', 'Description'),
            'data' => Yii::t('app', 'Data'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return ActiveQuery|UserQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * Gets query for [[Workspace]].
     *
     * @return ActiveQuery|WorkspaceQuery
     */
    public function getWorkspace()
    {
        return $this->hasOne(Workspace::className(), ['id' => 'workspace_id']);
    }

    /**
     * {@inheritdoc}
     * @return WorkspaceActivityQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new WorkspaceActivityQuery(get_called_class());
    }
}
