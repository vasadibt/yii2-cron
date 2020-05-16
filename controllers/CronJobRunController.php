<?php

namespace fullmvc\cron\controllers;

use Yii;
use fullmvc\cron\models\CronJobRunSearch;
use yii\web\Controller;

/**
 * CronJobRunController implements the CRUD actions for CronJobRun model.
 */
class CronJobRunController extends Controller
{
    /**
     * Lists all CronJobRun models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CronJobRunSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort->defaultOrder = ['start' => SORT_DESC];

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
