<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "offers".
 *
 * @property int $task_id
 * @property int $executor_id
 * @property string $dt_add
 * @property string $message
 * @property int $price
 *
 * @property Users $executor
 * @property Tasks $task
 */
class Offers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'offers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['task_id', 'executor_id', 'message', 'price'], 'required'],
            [['task_id', 'executor_id', 'price'], 'integer'],
            [['dt_add'], 'safe'],
            [['message'], 'string'],
            [['task_id', 'executor_id'], 'unique', 'targetAttribute' => ['task_id', 'executor_id']],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tasks::class, 'targetAttribute' => ['task_id' => 'id']],
            [['executor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['executor_id' => 'id']],
            ['denied', 'boolean'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'task_id' => 'Task ID',
            'executor_id' => 'Executor ID',
            'dt_add' => 'Dt Add',
            'message' => 'Message',
            'price' => 'Price',
        ];
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
     * Gets query for [[Task]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Tasks::class, ['id' => 'task_id']);
    }
}
