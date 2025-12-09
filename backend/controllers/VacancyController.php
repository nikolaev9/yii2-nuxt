<?php
namespace app\controllers;

use app\models\Vacancy;
use app\services\VacancyServiceInterface;
use Yii;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\rest\Controller;

class VacancyController extends Controller
{
    private $service;
    
    public function __construct($id, $module, VacancyServiceInterface $service, $config = [])
    {
        $this->service = $service;

        parent::__construct($id, $module, $config);
    }

    // Отключил rateLimiter чтобы не использовать User (меньше файлов)
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        unset($behaviors['rateLimiter']);
        return $behaviors;
    }

    /**
     * Список вакансий
     */
    public function actionIndex(): array
    {
        return $this->service->getList();
    }

    /**
     * Одна вакансия
     */
    public function actionView($id): ?Vacancy
    {
        return $this->service->getOne($id);
    }

    /**
     * @throws InvalidConfigException
     */
    public function actionCreate(): array
    {
        try {
            $vacancy = $this->service->create(Yii::$app->request->getBodyParams());

            return [
                'result' => 'success',
                'id' => $vacancy->id
            ];

        } catch (\RuntimeException $e) {

            Yii::$app->response->statusCode = 400;

            return [
                'result' => 'error',
                'errors' => json_decode($e->getMessage(), true)
            ];
        }
    }

    public function actionOptions(): void
    {
        Yii::$app->response->statusCode = 200;
    }
}