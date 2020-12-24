<?php

namespace app\modules\v1\setup\models;

use app\modules\v1\users\models\User;
use app\modules\v1\setup\models\UserDepartment;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%departments}}".
 *
 * @property int $id
 * @property string $name
 * @property int $country_id
 * @property int|null $parent_id
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 *
 * @property Country $country
 * @property User $createdBy
 * @property Department $parent
 * @property Department[] $departments
 * @property UserDepartment[] $userDepartments
 * @property User $updatedBy
 */
class Department extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%departments}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            BlameableBehavior::class
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'country_id'], 'required'],
            [['country_id', 'parent_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['name'], 'unique', 'targetAttribute' => ['name', 'country_id'], 'message' => Yii::t('app', 'Department {value} already exists in this country')],
            [['name'], 'string', 'max' => 255],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Country::className(), 'targetAttribute' => ['country_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Department::className(), 'targetAttribute' => ['parent_id' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'country_id' => Yii::t('app', 'Country'),
            'parent_id' => Yii::t('app', 'Parent Department'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * Gets query for [[Country]].
     *
     * @return \yii\db\ActiveQuery|\app\modules\v1\setup\models\query\CountryQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['id' => 'country_id']);
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery|\app\modules\v1\users\models\query\UserQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * Gets query for [[Parent]].
     *
     * @return \yii\db\ActiveQuery|\app\modules\v1\setup\models\query\DepartmentQuery
     */
    public function getParent()
    {
        return $this->hasOne(Department::className(), ['id' => 'parent_id']);
    }

    /**
     * Gets query for [[Departments]].
     *
     * @return \yii\db\ActiveQuery|\app\modules\v1\setup\models\query\DepartmentQuery
     */
    public function getDepartments()
    {
        return $this->hasMany(Department::className(), ['parent_id' => 'id']);
    }

    /**
     * Gets query for [[UpdatedBy]].
     *
     * @return \yii\db\ActiveQuery|\app\modules\v1\users\models\query\UserQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * {@inheritdoc}
     * @return \app\modules\v1\setup\models\query\DepartmentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\v1\setup\models\query\DepartmentQuery(get_called_class());
    }

    /**
     * @return \yii\db\ActiveQuery
     * @author Saiat Kalbiev <kalbievich11@gmail.com>
     */
    public function getUserDepartments()
    {
        return $this->hasMany(UserDepartment::class, ['department_id' => 'id']);
    }
}
