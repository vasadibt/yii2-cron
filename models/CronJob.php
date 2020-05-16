<?php
/**
 * Created by Model Generator.
 */

namespace fullmvc\cron\models;

use common\helpers\FileHelper;
use Symfony\Component\Process\Process;
use fullmvc\cron\Module;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveQueryInterface;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "cron_job".
 *
 * @property int $id
 * @property int $last_id
 * @property string $name
 * @property string $schedule
 * @property string $command
 * @property int $max_execution_time
 * @property boolean $active
 *
 * @property CronJobRun[] $cronJobRuns
 * @property CronJobRun $last
 */
class CronJob extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cron_job';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['last_id', 'max_execution_time'], 'integer'],
            [['active'], 'boolean'],
            [['name', 'schedule', 'command'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('vbt-cron', 'ID'),
            'last_id' => Yii::t('vbt-cron', 'Last'),
            'name' => Yii::t('vbt-cron', 'Name'),
            'schedule' => Yii::t('vbt-cron', 'Schedule'),
            'command' => Yii::t('vbt-cron', 'Command'),
            'max_execution_time' => Yii::t('vbt-cron', 'Max execution time'),
            'active' => Yii::t('vbt-cron', 'Active'),
        ];
    }

    /**
     * @return ActiveQuery|CronJobQuery|ActiveQueryInterface
     */
    public function getCronJobRuns()
    {
        return $this->hasMany(CronJobRun::class, ['job_id' => 'id']);
    }

    /**
     * @return ActiveQuery|CronJobQuery|ActiveQueryInterface
     */
    public function getLast()
    {
        return $this->hasOne(CronJobRun::class, ['id' => 'last_id']);
    }

    /**
     * {@inheritdoc}
     * @return CronJobQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CronJobQuery(get_called_class());
    }

    /**
     * @return array|CronJob[]
     */
    public static function findRunnable()
    {
        return static::find()
            ->leftJoin('cron_job_run', 'cron_job_run.id = cron_job.last_id')
            ->where(['AND',
                ['active' => 1],
                ['OR',
                    ['cron_job_run.id' => null],
                    ['cron_job_run.in_progress' => 0],
                    ['AND',
                        ['>', 'cron_job.max_execution_time', 0],
                        ['<', 'cron_job_run.start', new Expression('DATE_ADD(NOW(), INTERVAL -2 * cron_job.max_execution_time SECOND)')]
                    ]
                ],
            ])
            ->all();
    }

    /**
     * Run cron job
     *
     * @return CronJobRun
     */
    public function run()
    {
        $process = $this->buildProcess($this->command, $this->max_execution_time ?? 60);
        $process->start();

        $start = microtime(true);

        $run = new CronJobRun();
        $run->job_id = $this->id;
        $run->start = date('Y-m-d H:i:s');
        $run->pid = (string)$process->getPid();
        $run->in_progress = true;
        $run->save(false);
        $this->last_id = $run->id;
        $this->save();

        $run->exit_code = $process->wait();
        $run->runtime = microtime(true) - $start;
        $run->finish = date('Y-m-d H:i:s');
        $run->output = $process->getOutput();
        $run->error_output = $process->getErrorOutput();
        $run->in_progress = false;
        $run->save(true);

        return $run;
    }

    /**
     * Run cron job without waiting the result
     */
    public function runQuick()
    {
        $process = $this->buildProcess('cron/job/run ' . $this->id);
        $process->start();
    }

    /**
     * @param string $command
     * @param int $timeout
     * @return Process
     */
    public function buildProcess($command, $timeout = 60)
    {
        $command = $this->buildCommand($command);
        $process = new Process($command, null, null, null, $timeout);
        return $process;
    }

    /**
     * Build cron job command
     *
     * @return string
     */
    public function buildCommand($command)
    {
        $module = Module::getInstance();

        return strtr('{php} {yii} {command}', [
            '{php}' => $module->phpBinary,
            '{yii}' => $module->yiiFile,
            '{command}' => $command,
        ]);
    }
}
