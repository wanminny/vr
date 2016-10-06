yii2 vue
========

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist trylife/yii2-vue "*"
```

or add

```
"trylife/yii2-vue": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
<?= \trylife\vue\VueAsset::register($this); ?>
```