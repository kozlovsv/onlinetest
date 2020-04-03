<?php

namespace app\widgets;

use app\assets\PNotifyAsset;
use kartik\icons\FontAwesomeAsset;
use Yii;
use yii\base\Widget;
use yii\helpers\Json;

/**
 *
 * @property string $pnotifyOptionsJS
 */
class Alert extends Widget
{

    const STYLING_FONTAWESOME = 'fontawesome';
    const STYLING_BOOTSTRAP = 'bootstrap3';

    /**
     * Настройки плагина PNotify
     * @var array
     */
    public $clientOptions = [];

    public $styling = self::STYLING_FONTAWESOME;

    /**
     * Таймаут скрытия
     * @var int
     */
    public $closeTimeout = 4000;

    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();
        $this->initOptions();
    }

    /**
     * Initializes the widget options.
     * This method sets the default values for various options.
     */
    protected function initOptions()
    {
        $this->clientOptions = array_merge([
            'delay' => $this->closeTimeout,
            'styling' => $this->styling,
            'addclass' => 'stack-bottomright',
            'stack' => [
                'dir1' => 'up',
                'dir2' => 'left',
                'firstpos1' => 60,
                'firstpos2' => 10
            ]
        ], $this->clientOptions);
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        $this->registerClientScript();
    }


    /**
     * Registers the needed JavaScript.
     */
    public function registerClientScript()
    {
        $flashes = Yii::$app->session->getAllFlashes(true);
        if (!$flashes) return; //Если нет сообщений, то скрипты не подключаем.
        $this->registerAssets();
        $optionsJSVarName = 'alertPnotifyOptions';
        $js = "var {$optionsJSVarName}  = {$this->getPnotifyOptionsJS()} \n";
        $js .= "var {$optionsJSVarName}  = {$this->getPnotifyOptionsJS()} \n";
        foreach ($flashes as $key => $message) {
            $js .= $this->notifyJS($key, $message, $optionsJSVarName) . "\n";
        }
        $this->getView()->registerJs($js);
    }

    public function registerAssets()
    {
        $view = $this->getView();
        if ($this->styling === self::STYLING_FONTAWESOME) {
            FontAwesomeAsset::register($view);
        }
        PNotifyAsset::register($view);
    }

    /**
     * Получить настройки для плагина PNotify в формате JSON
     * @return string
     */
    public function getPnotifyOptionsJS()
    {
        return Json::encode($this->clientOptions);
    }

    /**
     * JS Код вывода сообщения
     * @param $type string Тип сообщения (info, error, success, warning)
     * @param $text string Текст сообщения
     * @param $optionsJSVarName string Имя переменной JS в котором хранятся настройки
     * @return string
     */
    public function notifyJS($type, $text, $optionsJSVarName)
    {
        return <<<JS
            new PNotify($.extend({}, {$optionsJSVarName}, {
                            type: "{$type}",
                            text: "{$text}"
                        }));
JS;
    }
}