<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
use colibri\base\assets\ColibriAsset;

/* @var $this yii\web\View */
/* @var $model colibri\models\InstallForm  */

ColibriAsset::register($this);

$this->title = Yii::t('colibri', 'Install {appname}', ['appname' => Yii::$app->name]);

$tzArray = DateTimeZone::listIdentifiers();
$timezoneIdentifiers = [];
foreach ($tzArray as $v) {
    $timezoneIdentifiers[$v] = $v;
}

$script = <<<JS

    $('#sqliteInfo').hide();
    $('#install-form')[0].reset();

    $('#installform-dbtype').change(function(event) {
        if (this.value == 'sqlite') {
            $('#sqliteInfo').show();
            $('#dbServerInfo').hide();
        } else {
            $('#sqliteInfo').hide();
            $('#dbServerInfo').show();
        }
    });

    $('#installform-dbname').bind('keyup change blur focus', function(event) {
        if ($('#installform-dbtype').val() == 'sqlite') {
            $('#sqliteName').text(this.value);
        }
    });
JS;

$css = <<<CSS
.wrapper {
    margin-top: 5% !important;
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
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-7\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]); ?>

    <div class="form-group page-header">
        <h1 class="col-lg-10 col-lg-offset-2"><?= $this->title ?></h1>
    </div>

    <?= $form->field($model, 'timeZone')->dropDownList($timezoneIdentifiers, ['prompt' => Yii::t('colibri', 'Choose')]) ?>
    <?= $form->field($model, 'language')->dropDownList(['en' =>'English', 'fr' => 'French']) ?>
    <?= $form->field($model, 'dbType')->dropDownList(['mysql' => 'Mysql', 'pgsql' => 'PostgreSql', 'sqlite' =>'Sqlite']) ?>
    <?= $form->field($model, 'dbName')->textInput() ?>

    <div id="dbServerInfo">
    <?= $form->field($model, 'dbHost')->textInput() ?>
    <?= $form->field($model, 'dbUsername')->textInput() ?>
    <?= $form->field($model, 'dbPassword')->passwordInput() ?>
    </div>

    <div class="form-group" id="sqliteInfo">
        <div class="col-lg-offset-2 col-lg-10">
           <?= Yii::t('colibri', 'Path')?> : <?= Yii::$app->getRuntimePath() ?>/<span id="sqliteName"></span>.sqlite
        </div>
    </div>
    <div class="form-group">
        <h2 class="col-lg-10 col-lg-offset-2"><small> <?= Yii::t('migration', 'Administrator account')?></small></h2>
    </div>
    <?= $form->field($model, 'email')->textInput() ?>
    <?= $form->field($model, 'login')->textInput() ?>
    <?= $form->field($model, 'password')->passwordInput() ?>


    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <?= Html::submitButton(Yii::t('migration', 'Process install'), ['class' => 'btn btn-primary', 'name' => 'install-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>


</div>

