<?php


namespace fullmvc\cron\commands;

use fullmvc\cron\models\CronJob;
use yii\console\Controller;
use yii\helpers\Console;

/**
 * Class JobController
 * @package fullmvc\cron\commands
 */
class JobController extends Controller
{
    /**
     * @param $id
     */
    public function actionRun($id)
    {
        $job = CronJob::findOne($id);
        $run = $job->run();

        Console::output('Process is finished, exit code: #' . $run->exit_code);
        Console::output($run->output);
        if (!empty($run->error_output)) {
            Console::output($run->error_output);
        }
    }

    /**
     * @param $id
     */
    public function actionRunQuick($id)
    {
        $job = CronJob::findOne($id);
        $job->runQuick();
        Console::output('Job started without wait finish: ' . $job->name);
    }
}

