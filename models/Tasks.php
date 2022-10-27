<?php

namespace app\models;

use taskforce\business\Task;
use taskforce\exception\TaskActionException;
use Yii;

/**
 * This is the model class for table "tasks".
 *
 * @property int $id
 * @property string $dt_add
 * @property int $customer_id
 * @property int|null $executor_id
 * @property string $title
 * @property string|null $description
 * @property int $category_id
 * @property int|null $city_id
 * @property string|null $location
 * @property int|null $budget
 * @property string|null $date_completion
 * @property string $status
 *
 * @property Categories $category
 * @property Cities $city
 * @property Users $customer
 * @property Users $executor
 * @property Users[] $executors
 * @property Files[] $files
 * @property Offers[] $offers
 * @property Reviews[] $reviews
 */
class Tasks extends \yii\db\ActiveRecord
{   
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tasks';
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dt_add' => 'Dt Add',
            'customer_id' => 'Customer ID',
            'executor_id' => 'Executor ID',
            'title' => 'Title',
            'description' => 'Description',
            'category_id' => 'Category ID',
            'city_id' => 'City ID',
            'location' => 'Location',
            'budget' => 'Budget',
            'date_completion' => 'Date Completion',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Categories::class, ['id' => 'category_id']);
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(Cities::class, ['id' => 'city_id']);
    }

    /**
     * Gets query for [[Customer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Users::class, ['id' => 'customer_id']);
    }

    /**
     * Gets query for [[Executor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExecutor()
    {
        return $this->hasOne(Users::class, ['id' => 'executor_id']);
    }

    /**
     * Gets query for [[Executors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExecutors()
    {
        return $this->hasMany(Users::class, ['id' => 'executor_id'])->viaTable('offers', ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Files]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFiles()
    {
        return $this->hasMany(Files::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Offers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOffers()
    {
        return $this->hasMany(Offers::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Reviews]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReviews()
    {
        return $this->hasMany(Reviews::class, ['task_id' => 'id']);
    }

    public function setExecutor($executorId)
    {   
        if (!$this->executor_id)
        {
            $this->executor_id = $executorId;
            $this->status = Task::STATUS_IN_PROGRESS;
                
            return $this->save();
        }
        throw new TaskActionException('Исполнитель уже назначен');
    }

    public function reject()
    {
        $this->status = Task::STATUS_FAIL;
        $this->save();        
    }

    public function cancel()
    {
        $this->status = Task::STATUS_FAIL;
        $this->save();
    }
    
}
