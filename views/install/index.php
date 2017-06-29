<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\BootstrapAsset;

/* @var $this yii\web\View */
/* @var $model colibri\base\models\InstallForm  */

BootstrapAsset::register($this);

$this->title = Yii::t('colibri', 'Install {appname}', ['appname' => Yii::$app->name]);

$tzArray = DateTimeZone::listIdentifiers();
$timezoneIdentifiers = [];
foreach ($tzArray as $v) {
    $timezoneIdentifiers[$v] = $v;
}

$script = <<<JS

    $('#install-form')[0].reset();

    $('#installform-language').change(function(event) {
        window.location.search =  '?lang=' + this.value;
    });
JS;

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

$this->registerJs($script);
$this->registerCss($css);
?>
<div id="colibri-install" class="container">

    <?php $form = ActiveForm::begin([
        'id' => 'install-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-7\">{hint}\n{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]); ?>

    <div class="form-group page-header">
        <h1 class="col-lg-10 col-lg-offset-2"><?= $this->title ?></h1>
    </div>

    <?php if ($model->globalError): ?>
    <p class="alert alert-danger">
    <?= $model->globalError ?>
    </p>
    <?php endif ?>
    
    <?= $form->field($model, 'language')->dropDownList(['en' =>'English', 'fr' => 'French']) ?>
    <?= $form->field($model, 'timeZone')->dropDownList($timezoneIdentifiers, ['prompt' => Yii::t('colibri', 'Choose')]) ?>

    <div class="form-group">
        <h2 class="col-lg-10 col-lg-offset-2"><small> <?= Yii::t('colibri', 'Database configuration')?></small></h2>
    </div>
    <?= $form->field($model, 'dbType')->dropDownList(['mysql' => 'Mysql', 'pgsql' => 'PostgreSql']) ?>
    <?= $form->field($model, 'dbHost')->textInput() ?>
    <?= $form->field($model, 'dbUsername')->textInput() ?>
    <?= $form->field($model, 'dbPassword')->passwordInput() ?>
    <?= $form->field($model, 'dbName')->textInput() ?>
    <?= $form->field($model, 'dbTablePrefix')->textInput() ?>

    <div class="form-group">
        <h2 class="col-lg-10 col-lg-offset-2"><small> <?= Yii::t('colibri', 'Administrator account')?></small></h2>
    </div>
    <?= $form->field($model, 'email')->textInput() ?>
    <?= $form->field($model, 'login')->textInput() ?>
    <?= $form->field($model, 'password')->passwordInput() ?>

    <div class="form-group">
        <hr />
        <div class="col-lg-offset-2 col-lg-10">
            <?= Html::submitButton(Yii::t('colibri', 'Process install'), ['class' => 'btn btn-primary', 'name' => 'install-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>


</div>

