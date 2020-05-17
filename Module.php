<?php
/**
 * Created by PhpStorm.
 * User: Tamás
 * Date: 2019. 01. 10.
 * Time: 19:01
 */

namespace vasadibt\cron;

use Yii;
use yii\console\Application as ConsoleApplication;
use yii\helpers\ArrayHelper;

/**
 * Class Module
 * @package vasadibt\cron
 */
class Module extends \yii\base\Module
{
    /**
     * @var string
     */
    public $phpBinary = PHP_BINARY;
    /**
     * @var string
     */
    public $yiiFile = '@vendor/../yii';
    /**
     * @var
     */
    public $bsVersion;
    /**
     * @var bool
     */
    protected $_isBs4;

    /**
     * {@inheritDoc}
     */
    public function init()
    {
        parent::init();

        if (Yii::$app instanceof ConsoleApplication) {
            $this->controllerNamespace = 'vasadibt\cron\commands';
        }

        $this->registerTranslations();
        $this->configureBsVersion();

        $this->yiiFile = realpath(\Yii::getAlias($this->yiiFile));
    }

    /**
     * Register translations
     */
    public function registerTranslations()
    {
        \Yii::$app->i18n->translations['vbt-cron'] = [
            'class' => '\yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@fullmvc/cron/messages',
        ];
    }

    /**
     * Validate if Bootstrap 4.x version
     * @return bool
     */
    public function isBs4()
    {
        if (!isset($this->_isBs4)) {
            $this->configureBsVersion();
        }
        return $this->_isBs4;
    }

    /**
     * Configures the bootstrap version settings
     * @return string the bootstrap lib parsed version number
     */
    protected function configureBsVersion()
    {
        $version = empty($this->bsVersion) ? ArrayHelper::getValue(Yii::$app->params, 'bsVersion', '3') : $this->bsVersion;
        $version = static::parseVer($version);
        $this->_isBs4 = $version === '4';
        return $version;
    }

    /**
     * Parses and returns the major BS version
     * @param string $ver
     * @return bool|string
     */
    protected static function parseVer($ver)
    {
        $ver = (string)$ver;
        return substr(trim($ver), 0, 1);
    }
}