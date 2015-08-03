<?php

namespace demi\recaptcha;

use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\JsExpression;
use yii\web\View;
use yii\widgets\InputWidget;

/**
 * ReCaptcha widget
 */
class ReCaptcha extends InputWidget
{
    /**
     * reCAPTCHA widget language.
     * By default Yii::$app->language.
     *
     * @var string Language code. See all list: https://developers.google.com/recaptcha/docs/language
     */
    public $lang;
    /**
     * reCAPTCHA API public key
     * Follow this link https://www.google.com/recaptcha/admin to get your API keys
     *
     * @var string
     */
    public $siteKey;
    /** @var array HTML-options for widget container */
    public $options = [];
    /** @var array gReCaptcha widget params */
    public $params = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        if (array_key_exists('id', $this->options)) {
            $this->id = $this->options['id'];
        } else {
            $this->options['id'] = $this->id;
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $id = $this->id;
        $options = $this->options;

        $params = [
            'sitekey' => $this->siteKey,
            'lang' => $this->lang,
            'callback' => new JsExpression(<<<JS
function(response) {
    $(document).find("#$id-input").val(response);
}
JS
            ),
        ];

        $this->registerClientScript(ArrayHelper::merge($params, $this->params));

        $content = Html::tag('div', '', ArrayHelper::merge($options, $this->options));

        $inputOptions = [
            'id' => $id . '-input',
        ];
        if ($this->hasModel()) {
            $content .= Html::activeHiddenInput($this->model, $this->attribute, $inputOptions);
        } else {
            $content .= Html::hiddenInput($this->name, null, $inputOptions);
        }

        return $content;
    }

    /**
     * Register google reCAPTCHA js api and custom render scripts
     *
     * @param array $gCaptchaParams
     */
    public function registerClientScript($gCaptchaParams = [])
    {
        $id = $this->id;
        $view = $this->view;
        ReCaptchaAsset::register($view);

        $options = Json::encode($gCaptchaParams);

        // Directly creating captcha widgets.
        // This code placed only one time irrespective of count of captcha-widgets
        $view->registerJs(<<<JS
if (typeof (renderReCaptchaCallback) === "undefined") {
    var reCaptchaWidgets = {};
    var renderReCaptchaCallback = function() {
        for (var widgetId in reCaptchaWidgets) {
            if (reCaptchaWidgets.hasOwnProperty(widgetId)) {
                grecaptcha.render(document.getElementById(widgetId), reCaptchaWidgets[widgetId]);
            }
        }
    };
}
JS
            , View::POS_HEAD, 'renderReCaptchaCallbackFunction');

        // Append new captcha widget info
        $view->registerJs(<<<JS
reCaptchaWidgets.$id = $options;
JS
            , View::POS_HEAD);
    }
}