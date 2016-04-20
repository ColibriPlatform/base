<?php
use yii\web\View;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
// use modules\admin\assets\AdminAsset;
/*
$user = Yii::$app->getUser();
$identity = $user->getIdentity();
$profil = $identity->getProfile()->one();
*/
/* @var $this View */
?>
<header class="main-header">
    <a href="<?= Yii::$app->homeUrl; ?>" class="logo">
        <span class="logo-mini"><?= ArrayHelper::getValue(Yii::$app->params, 'app.name.small', 'App')?></span>
        <span class="logo-lg"><?= Yii::$app->name ?></span>
    </a>
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">

              <!-- User Account -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  
                  <?php /* if ($this->params['adminAssetBundle'] instanceof \yii\web\AssetBundle): ?>
                  <img src="<?= $this->params['adminAssetBundle']->baseUrl ?>/img/anonymous.png" class="user-image" alt="User Image" />
                  <?php endif */?>
                  <span class="hidden-xs"><?php // = empty($profil->name)?  $identity->username : $profil->name ?></span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <?php /*if ($this->params['adminAssetBundle'] instanceof \yii\web\AssetBundle): ?>
                    <img src="<?= $this->params['adminAssetBundle']->baseUrl ?>/img/anonymous.png" class="img-circle" alt="User Image">
                    <?php endif */?>
                    <p><?php // = empty($profil->name)?  $identity->username : $profil->name ?></p>
                  </li>
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-left">
                      <?= Html::a('Profile', ['/user/settings/profile'], ['class' => 'btn btn-default btn-flat'])?>
                    </div>
                    <div class="pull-right">
                      <?= Html::a('Logout', ['/user/security/logout'], ['class' => 'btn btn-default btn-flat', 'data-method' => 'post'])?>
                    </div>
                  </li>
                </ul>
              </li>
              <!-- Control Sidebar Toggle Button -->
              <li>
                <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
              </li>
            </ul>
        </div>
    </nav>
</header>