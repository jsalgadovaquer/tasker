<?php

/* @var $this \yii\web\View */

/* @var $content string */

//use app\widgets\Alert;
use kartik\alert\Alert;
use kartik\alert\AlertBlock;
use kartik\icons\Icon;
use kartik\widgets\Growl;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use kartik\icons\FontAwesomeAsset;

Icon::map($this);
AppAsset::register($this);
FontAwesomeAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Tasks', 'url' => ['/task']],
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
    <?php
    echo AlertBlock::widget([
        'useSessionFlash' => true,
        'type' => AlertBlock::TYPE_GROWL,
        'delay' => 1000,
        'alertSettings' => [
            'error' => [
                'options' => [
                    'class' => 'col-xs-11 col-sm-3 growlAlertBlock'
                ],
                'pluginOptions' => [
                    'offset' => 80,
                    'showProgressbar' => false,
                    'timer' => false,
                    'placement' => [
                        'from' => 'top',
                        'align' => 'right',
                    ],
                ],
                'type' => Growl::TYPE_DANGER,
                'icon' => 'fas fa-times',
                'showSeparator' => true,
            ],
            'success' => [
                'options' => [
                    'class' => 'col-xs-11 col-sm-3 growlAlertBlock'
                ],
                'pluginOptions' => [
                    'offset' => 80,
                    'showProgressbar' => true,
                    'placement' => [
                        'from' => 'top',
                        'align' => 'right',
                    ],
                ],
                'type' => Growl::TYPE_SUCCESS,
                'icon' => 'fas fa-check-circle',
                'showSeparator' => true,
            ],
            'info' => [
                'options' => [
                    'class' => 'col-xs-11 col-sm-3 growlAlertBlock'
                ],
                'pluginOptions' => [
                    'offset' => 80,
                    'showProgressbar' => false,
                    'timer' => false,
                    'placement' => [
                        'from' => 'top',
                        'align' => 'right',
                    ],
                ],
                'type' => Growl::TYPE_INFO,
                'icon' => 'fas fa-info-circle fa-15x',
                'showSeparator' => true,
            ],
            'warning' => [
                'options' => [
                    'class' => 'col-xs-11 col-sm-3 growlAlertBlock'
                ],
                'pluginOptions' => [
                    'offset' => 80,
                    'showProgressbar' => false,
                    'timer' => false,
                    'placement' => [
                        'from' => 'top',
                        'align' => 'right',
                    ],
                ],
                'type' => Growl::TYPE_WARNING,
                'icon' => 'fas fa-exclamation-triangle fa-15x',
                'showSeparator' => true,
            ],

        ]
    ]);
    ?>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; Tasker <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
