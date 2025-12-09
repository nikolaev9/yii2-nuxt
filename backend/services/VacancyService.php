<?php
namespace app\services;

use app\models\Vacancy;
use yii\data\ActiveDataProvider;
use yii\db\Exception;

class VacancyService implements VacancyServiceInterface
{
    public function getList(): array
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Vacancy::find(),
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'attributes' => [
                    'salary',
                    'created_at',
                ],
            ],
        ]);

        /*
         * Раньше я возвращал ActiveDataProvider потому что RestController умеет с ним работать и автоматически отдает
         * данные о пагинации в заголовках ответа. Но тогда мы теряем возможность использовать VacancyService где-либо
         * кроме контроллера. Поэтому теперь я сам отдаю данные о пагинации.
         * Либо можно было сделать два метода: getList() и getListDataProvider()
         * */
        return [
            'vacancies' => $dataProvider->getModels(),
            'pagination' => [
                'totalCount'   => $dataProvider->getTotalCount(),
                'pageCount'    => $dataProvider->getPagination()->getPageCount(),
                'currentPage'  => $dataProvider->getPagination()->getPage() + 1,
                'perPage'      => $dataProvider->getPagination()->getPageSize(),
            ]
        ];
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