<?php
/**
 * Created by PhpStorm.
 * User: Tamás
 * Date: 2019. 01. 10.
 * Time: 18:12
 */

namespace fullmvc\cron\commands;

use Cron\CronExpression;
use fullmvc\cron\models\CronJob;
use yii\console\Controller;
use yii\console\widgets\Table;
use yii\helpers\ArrayHelper;

/**
 * Class JobController
 * @package console\controllers
 */
class CronController extends Controller
{
    /**
     * @var string The controller default action
     */
    public $defaultAction = 'jobs';

    /**
     * List all jobs
     * @throws \Exception
     */
    public function actionJobs()
    {
        $jobs = CronJob::find()
            ->where(['active' => true])
            ->all();

        echo PHP_EOL;
        echo Table::widget([
            'headers' => ['ID', 'Name', 'Schedule', 'Command', 'Max execution time', 'Active'],
            'rows' => ArrayHelper::getColumn($jobs, function (CronJob $job) {
                return [
                    $job->id,
                    $job->name,
                    $job->schedule,
                    $job->command,
                    $job->max_execution_time,
                    $job->active ? true : false,
                ];
            })
        ]);
    }

    /**
     * Run cron jobs
     */
    public function actionRun()
    {
        foreach (CronJob::findRunnable() as $job) {
            if (CronExpression::factory($job->schedule)->isDue()) {
                $this->run('/cron/job/run-quick', [$job->id]);
            }
        }
    }
}