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
     * @param int $id
     */
    public function actionRun($id)
    {
        $job = CronJob::findOne($id);
        $run = $job->run();

        $this->output('Process is finished, exit code: #' . $run->exit_code);
        $this->output($run->output);

        if ($run->error_output) {
            $this->output($run->error_output);
        }
    }

    /**
     * @param int $id
     */
    public function actionRunQuick($id)
    {
        $job = CronJob::findOne($id);
        $job->runQuick();
        $this->output('Job started without wait finish: ' . $job->name);
    }

    /**
     * @param string $message
     */
    protected function output($message){
        echo $message . PHP_EOL;
    }
}

