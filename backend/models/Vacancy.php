<?php
namespace app\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Url;

class Vacancy extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%vacancy}}';
    }

    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function rules(): array
    {
        return [
            [['title', 'description'], 'required'],
            ['title', 'string', 'max' => 255],
            ['description', 'string'],
            ['salary', 'integer', 'min' => 0],
            [['salary'], 'default', 'value' => 0],
        ];
    }

    public function fields(): array
    {
        return [
            'id',
            'title',
            'description',
            'salary',
            
            'link' => function($model) {
                return Url::to(['vacancy/view', 'id' => $model->id], true);
            }
        ];
    }
}
