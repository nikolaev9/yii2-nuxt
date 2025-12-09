<?php
namespace app\services;

use app\models\Vacancy;
use yii\data\ActiveDataProvider;

interface VacancyServiceInterface
{
    public function getList(): array;

    public function getOne($id): ?Vacancy;

    public function create(array $data): ?Vacancy;
}