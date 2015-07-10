<?php

namespace demi\recaptcha;

use yii\web\AssetBundle;

/**
 * ReCaptcha assets
 */
class ReCaptchaAsset extends AssetBundle
{
    public $js = [
        '//www.google.com/recaptcha/api.js?onload=renderReCaptchaCallback&render=explicit',
    ];
    public $jsOptions = [
        'async' => true,
        'defer' => true,
    ];

    public function init()
    {
        if (isset($this->js[0])) {
            // Add current site language to script url
            $this->js[0] .= '&hl=' . \Yii::$app->language;
        }

        parent::init();
    }
}