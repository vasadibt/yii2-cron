<?php

use yii\db\Migration;

/**
 * Handles adding max_execution_time to table `{{%cron_job}}`.
 */
class m190525_122152_add_max_execution_time_column_to_cron_job_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%cron_job}}', 'max_execution_time', $this->integer()->after('command'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%cron_job}}', 'max_execution_time');
    }
}
