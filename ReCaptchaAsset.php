<?php
/**
 * @copyright Copyright (c) 2018 Ivan Orlov
 * @license   https://github.com/demisang/yii2-recaptcha/blob/master/LICENSE
 * @link      https://github.com/demisang/yii2-recaptcha#readme
 * @author    Ivan Orlov <gnasimed@gmail.com>
 */

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
