<?php

namespace demi\recaptcha;

use Yii;
use yii\base\InvalidConfigException;
use yii\validators\Validator;

class ReCaptchaValidator extends Validator
{
    /**
     * reCAPTCHA API secret key
     * Follow this link https://www.google.com/recaptcha/admin to get your API keys
     *
     * @var string
     */
    public $secretKey;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if ($this->secretKey === null) {
            throw new InvalidConfigException('You must set "secretKey" param for ReCaptcha validator');
        }

        if ($this->message === null) {
            $this->message = Yii::t('yii', 'The verification code is incorrect.');
        }
    }

    /**
     * @inheritdoc
     */
    public function validateAttribute($model, $attribute)
    {
        $value = $model->$attribute;

        // Create an instance of the service using your secret
        $recaptcha = new \ReCaptcha\ReCaptcha($this->secretKey);

        // Make the call to verify the response and also pass the user's IP address
        $resp = $recaptcha->verify($value, Yii::$app->request->getUserIP());

        if (!$resp->isSuccess()) {
            $this->addError($model, $attribute, $this->message);
        }
    }
}