<?php

use yii\helpers\Html;
use dee\adminlte\AdminlteAsset;

/**
 * @var \yii\web\View $this
 * @var string $content
 */
AdminlteAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <?php $this->beginBody() ?>
    <body class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo">
                <?= Yii::$app->name ?>
            </div>

            <div class="login-box-body">
            <?= $content ?>
            </div>
        </div>
    </body>
    <?php $this->endBody() ?>
</html>
<?php $this->endPage() ?>
