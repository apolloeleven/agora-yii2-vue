<?php

namespace app\modules\v1\workspaces\models;

use app\modules\v1\users\models\query\UserQuery;
use app\modules\v1\users\models\User;
use app\modules\v1\workspaces\models\query\UserActivtyQuery;
use app\modules\v1\workspaces\models\query\WorkspaceQuery;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%user_activity}}".
 *
 * @property int $id
 * @property int|null $workspace_id
 * @property string|null $table_name
 * @property int|null $content_id
 * @property string|null $action
 * @property string|null $description
 * @property int|null $created_at
 * @property int|null $created_by
 *
 * @property User $createdBy
 * @property Workspace $workspace
 */
class UserActivity extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_activity}}';
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

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['workspace_id', 'content_id', 'created_at', 'created_by'], 'integer'],
            [['description'], 'string'],
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
            'id' => 'ID',
            'workspace_id' => 'Workspace ID',
            'table_name' => 'Table Name',
            'content_id' => 'Content ID',
            'action' => 'Action',
            'description' => 'Description',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
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
     * @return UserActivtyQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserActivtyQuery(get_called_class());
    }
}
