<?php

namespace app\models\form;

use app\models\Categories;
use app\models\Cities;
use app\models\Files;
use app\models\Tasks;
use taskforce\business\Task;
use taskforce\exception\TaskAddException;
use taskforce\Files as TaskforceFiles;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class AddTask extends Model
{
    public $title;
    public $description;
    public $category;
    public $address;
    public $price;
    public $date;
    public $files;
    public $lat;
    public $long;
    public $city;

    public $file_names;

    public function attributeLabels()
    {
        return
          [
            'title' => 'Опишите суть работы',
            'description' => 'Подробности задания',
            'category' => 'Категория',
            'address' => 'Локация',
            'price' => 'Бюджет',
            'date' => 'Срок исполнения',
            'files' => 'Файлы',
          ];
    }

    public function rules()
    {
        return
          [
            [['title', 'description', 'category'], 'required'],
            ['title', 'string', 'max' => 60, 'min' => '10'],
            ['description', 'string', 'max' => 300, 'min' => '20'],
            ['category', 'exist', 'targetClass' => Categories::class, 'targetAttribute' => ['category' => 'id']],
            ['price', 'compare', 'compareValue' => 0, 'operator' => '>', 'type' => 'number'],
            ['date', 'date', 'format' => 'Y-m-d'],
            ['date', 'compare', 'compareValue' => date('Y-m-d'), 'operator' => '>', 'type' => 'date'],
            ['files', 'file', 'maxFiles' => 4, 'maxSize' => 1024 * 1024 * 3],
            [['lat', 'long', 'address', 'city'], 'safe']
          ];
    }

    public function saveTask()
    {
        $task = new Tasks();
        $task->customer_id = Yii::$app->user->getId();
        $task->title = $this->title;
        $task->description = $this->description;
        $task->category_id = $this->category;
        $task->budget = $this->price;
        $task->date_completion = $this->date;
        $task->status = Task::STATUS_NEW;
        if ($this->lat && $this->long) {
            $task->lat = $this->lat;
            $task->long = $this->long;
            $task->address = $this->address;
            $task->city_id = Cities::getCityId($this->city);
        }
        if (!$task->save()) {
            throw new TaskAddException('Не удалось загрузить обьявление');
        }


        $this->file_names = TaskforceFiles::uploadTaskFiles(UploadedFile::getInstances($this, 'files'));

        if (count($this->file_names) > 0 && $task->id) {
            foreach ($this->file_names as $name) {
                $file = new Files();
                $file->task_id = $task->id;
                $file->file_name = $name;
                $file->save();
            }
        }
        return $task->id;
    }
}
