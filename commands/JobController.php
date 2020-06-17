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

        try {
            // try to write an empty string to the STDOUT
            // because on long job run, the "connection" to
            // the php://stdout may lost
            fwrite(\STDOUT, '');
            $writer = function ($message) {
                return Console::output($message);
            };
        } catch (\Exception $e) {
            $writer = function ($message) {
                echo $message . PHP_EOL;
                return strlen($message);
            };
        }

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

