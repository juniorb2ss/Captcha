# Captcha
[![Laravel 5](https://img.shields.io/badge/Laravel-5-green.svg)](https://laravel.com)
[![Latest Stable Version](https://poser.pugx.org/juniorb2ss/captcha/v/stable)](https://packagist.org/packages/juniorb2ss/captcha)
[![Total Downloads](https://poser.pugx.org/juniorb2ss/captcha/downloads)](https://packagist.org/packages/juniorb2ss/captcha)
[![Latest Unstable Version](https://poser.pugx.org/juniorb2ss/captcha/v/unstable)](https://packagist.org/packages/juniorb2ss/captcha)
[![License](https://poser.pugx.org/juniorb2ss/captcha/license)](https://packagist.org/packages/juniorb2ss/captcha)

Pacote de serviços para quebra de captcha. Para quebra de captcha é utilizado serviço `DeathByCaptcha`, é feito uma requisição pela abstração da API do serviço, após efetuar requisição é preciso esperar em torno de `~10` segundos para obter a resposta. Visite o site deles e assine um plano para obter credenciais.

Este pacote deverá ser usado com responsabilidade, o autor e contribuidores não devem responder pelas implementações/ações feita com este pacote.

### Atenção

Este pacote foi desenvolvido com o intuito de facilidade implementação de consultas e inteligência de resolução de captchas.
Toda implementação será de sua responsabilidade.

### Version Stable
1.0.0

### Instalação

```sh
$ composer require juniorb2ss/captcha 1.*
```
### Laravel 5
Configure os providers e aliases em `config/app.php`
```php
'providers' => [
    // ....
      Captcha\Laravel\ServicesProvider::class,
    //...
];

'aliases' => [
    //...
    'dbc' => Captcha\Laravel\DeathByCaptchaFacade::class,
    //...
];
```
```php
use Captcha\DeathByCaptcha\Service;
$dbc = new Service;
$dbc->credentials('yourLogin', 'yourPassword');
$text = $dbc->upload($base64Image);
// looping
echo $text;
```

### Desenvolvimento
Deseja contribuir com desenvolvimento? pull request :)

### To-do

License
----
MIT

**Free Software, Hell Yeah!**

[//]: # (These are reference links used in the body of this note and get stripped out when the markdown processor does its job. There is no need to format nicely because it shouldn't be seen. Thanks SO - http://stackoverflow.com/questions/4823468/store-comments-in-markdown-syntax)

