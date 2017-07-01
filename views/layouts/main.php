<?php
/**
 * This file is part of Colibri platform
 *
 * @link https://github.com/ColibriPlatform
 * @copyright   (C) 2017 PHILIP Sylvain. All rights reserved.
 * @license     MIT; see LICENSE.md
 */

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;

use yii\bootstrap\BootstrapAsset;

BootstrapAsset::register($this);

$css = <<<CSS
html,
body {
    height: 100%;
}

.wrap {
    min-height: 100%;
    height: auto;
    margin: 0 auto -60px;
    padding: 0 0 60px;
}

.wrap > .container {
    padding: 70px 15px 20px;
}

.footer {
    height: 60px;
    background-color: #f5f5f5;
    border-top: 1px solid #ddd;
    padding-top: 20px;
}
CSS;

$this->registerCss($css);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'Colibri',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'encodeLabels' => false,
        'items' => [
            ['label' => 'Home', 'url' => ['/default/index']],
            ['label' => 'Login', 'url' => ['user/security/login'], 'visible' => Yii::$app->user->isGuest ],
            ['label' => 'Logout', 'url' => ['user/security/logout'], 'visible' => !Yii::$app->user->isGuest,
                        'linkOptions' => ['data-method' => 'post']],
            /*
            '<li class="divider"></li>',
            [
                'label' => '<i class="glyphicon glyphicon-cog"></i>',
                'url' => ['/backend'],
                // 'visible' => \Yii::$app->user->can('backend_default_index', ['route' => true]),
            ]
            */
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
