Yii2-reCAPTCHA
===================
Yii2 wrapper for [reCAPTCHA](https://www.google.com/recaptcha/intro/index.html widget)

Installation
---
Run
```code
composer require "demi/recaptcha" "~1.0"
```

# Configurations
---

[Register new reCAPTCHA API keys](https://www.google.com/recaptcha/admin)

Add keys to ```/common/config/params.php```:
```php
return [
    // reCAPTCHA API keys
    'reCAPTCHA.siteKey' => 'xxxxxxxxx',
    'reCAPTCHA.secretKey' => 'xxxxxxxxx',
];
```

In the form:
```php
<?= $form->field($model, 'captcha', ['enableAjaxValidation' => false])->label(false)
    ->widget('demi\recaptcha\ReCaptcha', ['siteKey' => Yii::$app->params['reCAPTCHA.siteKey']]) ?>
```

In the model validation rules:
```php
public function rules()
{
    return [
        // captcha
        [
            ['captcha'], 'demi\recaptcha\ReCaptchaValidator', 'secretKey' => Yii::$app->params['reCAPTCHA.secretKey'],
            'when' => function ($model) {
                /** @var $model self */
                return !$model->hasErrors() && Yii::$app->user->isGuest;
            }
        ],
}
```
