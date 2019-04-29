<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cron_job_run}}`.
 */
class m190426_144159_create_cron_job_run_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cron_job_run}}', [
            'id' => $this->primaryKey(),
            'job_id' => $this->integer()->notNull(),
            'pid' => $this->string()->notNull(),

            'in_progress' => $this->boolean()->notNull(),

            'start' => $this->dateTime()->notNull(),
            'finish' => $this->dateTime(),
            'runtime' => $this->float(),

            'exit_code' => $this->integer(),
            'output' => $this->text(),
            'error_output' => $this->text(),
        ]);

        $this->createIndex('idx-cron_job_run-job_id', 'cron_job_run', 'job_id');
        $this->addForeignKey('fk-cron_job_run-job_id', 'cron_job_run', 'job_id', 'cron_job', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%cron_job_run}}');
    }
}
