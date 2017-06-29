<?php

use yii\helpers\Html;
use yii\bootstrap\BootstrapAsset;

/* @var $this yii\web\View */

BootstrapAsset::register($this);

$this->title = Yii::t('colibri', 'Resume install {appname}', ['appname' => Yii::$app->name]);


$css = <<<CSS
hr {
    border-color: #ccc;
}

#colibri-install {
    box-shadow: 0 0 8px rgba(0, 0, 0, 0.5);
    background: #ecf0f5;
    margin-top: 2%;
    margin-bottom: 2%;
}

.page-header {
    margin-top: 0;
    border-color: #ccc;
}

.page-header h1 {
    font-size: 28px;
}


CSS;

$this->registerCss($css);
?>
<div id="colibri-install" class="container">

    <div class="page-header">
        <h1><?= $this->title ?></h1>
    </div>
    <h2><?= Yii::t('colibri', 'Installation completed') ?></h2>
    <pre><?= $messages ?></pre>

    <?= Html::a(Yii::t('colibri', 'Go to home'), '/', ['class' => 'btn btn-success']) ?>

</div>

