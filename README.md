# LaraCaptcha

[![Latest Version on Packagist](https://img.shields.io/packagist/v/martian/laracaptcha.svg?style=flat-square)](https://packagist.org/packages/martian/laracaptcha)
[![Total Downloads](https://img.shields.io/packagist/dt/martian/laracaptcha.svg?style=flat-square)](https://packagist.org/packages/martian/laracaptcha)
![GitHub Actions](https://github.com/hendurhance/laracaptcha/actions/workflows/main.yml/badge.svg)

A Laravel package to seamlessly integrate Google [reCAPTCHA](https://developers.google.com/recaptcha/intro) v3, v2 or [hCAPTCHA](https://www.hcaptcha.com/) into your application forms or Restful API.

<!-- image flex  -->
<div style="display: flex; justify-content: space-between;">
  <figure style="text-align: center; flex: 1;">
    <img src="https://user-images.githubusercontent.com/59781900/163660320-8209d05d-c7ed-40f3-831b-3dde16904014.png" alt="Image 1" width="200px"/>
    <figcaption style="text-align: center;">hCaptcha</figcaption>
  </figure>
  <figure style="text-align: center; flex: 1;">
    <img src="https://cloud.githubusercontent.com/assets/1529454/5291635/1c426412-7b88-11e4-8d16-46161a081ece.gif" alt="Image 2" width="200px"/>
    <figcaption style="text-align: center;">reCaptcha</figcaption>
  </figure>
</div>

## Supported Captcha Services
| Service | Version | Type | Supported |
| --- | --- | --- | --- |
| [Google reCAPTCHA](https://developers.google.com/recaptcha/docs/v3) | v3 | - | ✅ Yes |
| [Google reCAPTCHA](https://developers.google.com/recaptcha/docs/display) | v2 | Checkbox | ✅ Yes |
| [Google reCAPTCHA](https://developers.google.com/recaptcha/docs/invisible) | v2 | Invisible | ✅ Yes |
| [hCAPTCHA](https://docs.hcaptcha.com/) | - | Checkbox | ✅ Yes |
| [hCAPTCHA](https://docs.hcaptcha.com/invisible) | - | Invisible | ✅ Yes |


## Installation
> **Note:** This package requires PHP 7.4 or higher.

You can install the package via composer:

```bash
composer require martian/laracaptcha
```

## Register Service Provider
Add the following to the `providers` array in `config/app.php`:

```php
Martian\LaraCaptcha\Providers\LaraCaptchaServiceProvider::class,
```

## Publish Configuration File
Publish the configuration file using the following command:

```bash
php artisan vendor:publish --provider="Martian\LaraCaptcha\Providers\LaraCaptchaServiceProvider"
```


## Configuration
The configuration file is located at `config/laracaptcha.php`. The following options are available:

### reCAPTCHA v2 Configuration
- In order to use reCAPTCHA you need to [register your site](https://www.google.com/recaptcha/admin/create) for an API key pair. To use reCaptcha v2 Checkbox, select **Challenge (v2)** **>** **I'm not a robot Checkbox**. To use the invisible reCAPTCHA, select **Challenge (v2)** **>** **Invisible reCAPTCHA badge**. The API key pair consists of a site key and secret key. Set the `default` option to `recaptcha` in `config/laracaptcha.php`:

    ```php
    'default' => 'recaptcha',
    ```
- Change the `version` option to `v2` to use reCAPTCHA v2:
    ```php
    'drivers' => [
        'recaptcha' => [
            ...
            'version' => 'v2',
            ...
        ],
    ],
    ```
- Add `RECAPTCHA_SITE_KEY` and `RECAPTCHA_SECRET_KEY` to your `.env` file:

    ```env
    RECAPTCHA_SITE_KEY=your-site-key
    RECAPTCHA_SECRET_KEY=your-secret-key
    ```

### reCAPTCHA v3 Configuration
- In order to use reCAPTCHA you need to [register your site](https://www.google.com/recaptcha/admin/create) for an API key pair. To use reCaptcha v3, select **reCAPTCHA v3**. The API key pair consists of a site key and secret key. Set the `default` option to `recaptcha` in `config/laracaptcha.php`:

    ```php
    'default' => 'recaptcha',
    ```
- Change the `version` option to `v3` to use reCAPTCHA v3:
    ```php
    'drivers' => [
        'recaptcha' => [
            ...
            'version' => 'v3',
            ...
        ],
    ],
    ```
- Add `RECAPTCHA_SITE_KEY`, `RECAPTCHA_SECRET_KEY` and `RECAPTCHA_SITE_URL` to your `.env` file:

    ```env
    RECAPTCHA_SITE_KEY=your-site-key
    RECAPTCHA_SECRET_KEY=your-secret-key
    RECAPTCHA_SITE_URL=${APP_URL}
    ```

### hCAPTCHA Configuration
- In order to use hCAPTCHA you need to [register your site](https://dashboard.hcaptcha.com/signup) for an API key pair. The API key pair consists of a site key and secret key. Set the `default` option to `hcaptcha` in `config/laracaptcha.php`:

    ```php
    'default' => 'hcaptcha',
    ```
- Add `HCAPTCHA_SITE_KEY` and `HCAPTCHA_SECRET_KEY` to your `.env` file:

    ```env
    HCAPTCHA_SITE_KEY=10000000-ffff-ffff-ffff-000000000001
    HCAPTCHA_SECRET_KEY=0x0000000000000000000000000000000000000000
    ```
*These are the test keys we use by default. You should not use them in production!*
## Usage
To display captcha in your form, follow the steps below according to the captcha configuration you are using.

### reCAPTCHA v2 Checkbox & Invisible
#### Initializing JavaScript
Add the following to the `<head>` section of your page:
```php
{!! LaraCaptcha::script() !!}
```
With other options in [Google reCaptcha v2 Checkbox dox](https://developers.google.com/recaptcha/docs/display#config)
```php
{!! LaraCaptcha::script('yourCallbackFunction', 'explicit', 'en') !!}
```
*Note: The first parameter is the callback function name, the second is the rendering mode (explicit or onload), and the third is the language code from [doc](https://developers.google.com/recaptcha/docs/language)*

#### Displaying Captcha Widget - Checkbox
Add the following to your form:
```php
{!! LaraCaptcha::display() !!}
```
With other options in [Google reCaptcha v2 Checkbox dox](https://developers.google.com/recaptcha/docs/display#render_param)
```php
{!! LaraCaptcha::display(['data-theme' => 'dark']) !!}
```
*Note: The parameter is an array of attributes for the widget*

#### Displaying Captcha Widget - Invisible
Add the following to your form:
```php
{!! LaraCaptcha::displayInvisibleButton('formIdentifier', 'Submit Button',['data-size' => 'invisible']) !!}
```
*Note: The first parameter is the form identifier, the second is the button label (Submit Button), and the third is an array of attributes for the widget, see [doc](https://developers.google.com/recaptcha/docs/invisible#render_param). Add the **formIdentifier** value as the id in the form element* 
#### Validating Captcha
Add the following to your validation rules:
```php
'g-recaptcha-response' => 'recaptcha',
```
You can also use the rule in the Validator facade:
```php
$validator = Validator::make($request->all(), [
    'g-recaptcha-response' => 'recaptcha',
]);
```
#### Add Custom Validation Message
Add the following to your validation messages:
```php
'g-recaptcha-response.recaptcha' => 'Captcha verification failed.',
```
Or you can change the default message in `config/laracaptcha.php`:
```php
'error_message' => 'Captcha verification failed.',
```
### reCAPTCHA v3
#### Initializing JavaScript
Add the following to the `<head>` section of your page:
```php
{!! LaraCaptcha::script() !!}
```
With other options in [Google reCaptcha v3 dox](https://developers.google.com/recaptcha/docs/v3#config)
```php
{!! LaraCaptcha::script('yourCallbackFunction', 'explicit', 'en') !!}
```
#### Displaying Captcha Widget
Add the following to your form:
```php
{!! LaraCaptcha::display() !!}
```
With other options in [Google reCaptcha v3 dox](https://developers.google.com/recaptcha/docs/v3#render_param)
```php
{!! LaraCaptcha::display(['action' => 'homepage', 'custom_validation' => 'yourCustomFunction', 'recaptcha_input_identifier' => 'yourReCaptchaInputId']) !!}
```
*Note: The parameter is an array of attributes for the widget, see [doc](https://developers.google.com/recaptcha/docs/v3#interpreting_the_score) for actions type*

### hCAPTCHA v2 Checkbox & Invisible
#### Initializing JavaScript
Add the following to the `<head>` section of your page:
```php
{!! LaraCaptcha::script() !!}
```
With other options in [hCAPTCHA dox](https://docs.hcaptcha.com/configuration)
```php
{!! LaraCaptcha::script('yourCallbackFunction', 'onload' 'en', 'on') !!}
```
*Note: The first parameter is the callback function name, the second is the rendering mode (onload or explicit), the third is the language code from [doc](https://docs.hcaptcha.com/languages), and the fourth is the recaptchacompat option*

#### Displaying Captcha Widget - Checkbox
Add the following to your form:
```php
{!! LaraCaptcha::display() !!}
```
With other options in [hCAPTCHA dox](https://docs.hcaptcha.com/configuration/#hcaptcha-container-configuration)
```php
{!! LaraCaptcha::display(['data-theme' => 'dark']) !!}
```
*Note: The parameter is an array of attributes for the widget*

#### Displaying Captcha Widget - Invisible
Add the following to your form, see documentation for [invisible hcaptcha](https://docs.hcaptcha.com/invisible):
```php
{!! LaraCaptcha::displayInvisibleButton('formIdentifier', 'Submit Button',['data-size' => 'invisible']) !!}
```
*Note: The first parameter is the form identifier, the second is the button label (Submit Button), and the third is an array of attributes for the widget, see [doc](https://docs.hcaptcha.com/configuration/#hcaptcha-container-configuration)*

#### Validating Captcha
Add the following to your validation rules:
```php
'h-captcha-response' => 'hcaptcha',
```
You can also use the rule in the Validator facade:
```php
$validator = Validator::make($request->all(), [
    'h-captcha-response' => 'hcaptcha',
]);
```
#### Add Custom Validation Message
Add the following to your validation messages:
```php
'h-captcha-response.hcaptcha' => 'Captcha verification failed.',
```
Or you can change the default message in `config/laracaptcha.php`:
```php
'error_message' => 'Captcha verification failed.',
```

For other configuration go through the `config/laracaptcha.php` file.
### Testing

```bash
./vendor/bin/phpunit
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email hendurhance.dev@gmail.com instead of using the issue tracker.

## Credits

-   [Josiah Endurance](https://github.com/hendurhance)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

