<?php
namespace app\repositories;

use app\models\Vacancy;

interface VacancyRepositoryInterface
{
    public function queryAll(): array;
    public function findOne(int $id): ?Vacancy;
    public function save(Vacancy $vacancy): bool;
}