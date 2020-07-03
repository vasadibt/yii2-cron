<?php


namespace vasadibt\cron\commands;

use vasadibt\cron\models\CronJob;
use yii\console\Controller;
use yii\helpers\Console;

/**
 * Class JobController
 * @package vasadibt\cron\commands
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
        $writer = function ($message) {
            try {
                return Console::output($message);
            } catch (\Exception $e) {
                $message = $message . PHP_EOL;
                echo $message;
                return strlen($message);
            }
        };

        $writer('Process is finished, exit code: #' . $run->exit_code);
        $writer($run->output);

        if ($run->error_output) {
            $writer($run->error_output);
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

