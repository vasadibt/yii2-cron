<?php
/**
 * Created by Model Generator.
 */

namespace vasadibt\cron\models;

use Symfony\Component\Process\Process;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveQueryInterface;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "cron_job".
 * @property integer $id
 * @property integer $last_id
 * @property string $name
 * @property string $schedule
 * @property string $command
 * @property integer $active
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
            [['last_id', 'active'], 'integer'],
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
                ],
            ])
            ->all();
    }

    /**
     * Build cron job command
     *
     * @return string
     */
    public function buildCommand()
    {
        return strtr('{php} {yii} {command}', [
            '{php}' => PHP_BINARY,
            '{yii}' => \Yii::getAlias('@root/yii'),
            '{command}' => $this->command,
        ]);
    }

    /**
     * Run cron job
     *
     * @return CronJobRun
     */
    public function run()
    {
        $process = new Process($this->buildCommand());
        $process->start();
        $start = microtime(true);

        $run = new CronJobRun();
        $run->job_id = $this->id;
        $run->start = date('Y-m-d H:i:s');
        $run->pid = (string)$process->getPid();
        $run->in_progress = 1;
        $run->save(false);
        $this->last_id = $run->id;
        $this->save();

        $run->exit_code = $process->wait();
        $run->runtime = microtime(true) - $start;
        $run->finish = date('Y-m-d H:i:s');
        $run->output = $process->getOutput();
        $run->error_output = $process->getErrorOutput();
        $run->in_progress = 0;
        $run->save(true);

        return $run;
    }

    /**
     * Run cron job without waiting the result
     */
    public function runQuick()
    {
        $command = strtr('{php} {yii} {command}', [
            '{php}' => PHP_BINARY,
            '{yii}' => \Yii::getAlias('@root/yii'),
            '{command}' => 'cron/job/run ' . $this->id,
        ]);

        $process = new Process($command);
        $process->start();
    }
}
