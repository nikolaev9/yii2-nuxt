<?php
namespace app\services;

use app\models\Vacancy;
use yii\data\ActiveDataProvider;
use yii\db\Exception;

class VacancyService implements VacancyServiceInterface
{
    public function getList($params): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => Vacancy::find(),
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'attributes' => [
                    'salary',
                    'created_at',
                ],
            ]
        ]);
    }

    public function getOne($id): ?Vacancy
    {
        return Vacancy::findOne($id);
    }

    /**
     * @throws Exception
     */
    public function create(array $data): Vacancy
    {
        $model = new Vacancy();
        $model->load($data, '');

        if (!$model->save()) {
            throw new \RuntimeException(
                json_encode($model->errors)
            );
        }

        return $model;
    }
}