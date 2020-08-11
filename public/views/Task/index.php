<?php

use app\models\Task;
use kartik\grid\GridView;
use kartik\icons\Icon;
use kartik\select2\Select2;
use yii\bootstrap\Modal;
use yii\helpers\Html;

use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TaskSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model Task */
/* @var $summary array */

$this->title = Yii::t('task', 'Tasks');
$this->params['breadcrumbs'][] = $this->title;
$css = <<<CSS

 .timer div{
  display: inline-block;
  padding-left: 5px;
}

.days {
  color: #db4844;
  font-weight: bold;
}
.hours {
  color: #f07c22;
  font-weight: bold;
}
.minutes {
  color: #f6da74;
  font-weight: bold;
}
.seconds {
  color: #abcd58;
  font-weight: bold;
}
CSS;

$this->registerCss($css);
?>
    <div class="task-index">
        <?php
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'beforeFooter' => '',
            'columns' => [
                'name',
                [
                    'attribute' => 'start',
                    'filter' => false
                ],
                [
                    'attribute' => 'end',
                    'filter' => false
                ],
                ['attribute' => 'stat',
                    'label' => 'Task Status',
                    'filterType' => GridView::FILTER_SELECT2,
                    'width' => '150px',
                    'filter' => [
                        1 => Icon::show('lock-open', ['class' => 'text-warning']) . ' Running',
                        2 => Icon::show('lock', ['class' => 'text-success']) . ' Closed'
                    ],
                    'format' => 'raw',
                    'filterWidgetOptions' => [
                        'size' => Select2::SMALL,
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'hideSearch' => true,
                        'pluginOptions' => [
                            'allowClear' => true,
                            'escapeMarkup' => new JsExpression("function(m) { return m; }"),
                            'placeholder' => '',

                        ],
                    ],

                    'value' => function ($row) {
                        if ($row->stat == 1) {
                            $value = Icon::show('lock-open', ['class' => 'text-warning']) . ' Running';
                        } else {
                            $value = Icon::show('lock', ['class' => 'text-success']) . ' Closed';
                        }
                        return $value;

                    }],
                [
                    'attribute' => 'time',
                    'format' => 'raw',
                    'value' => function ($row) {
                        $class = '';
                        if ($row->stat == 1) {
                            $class = 'running';
                        }
                        return "<div class='timer {$class}'>
                                      <div class='days'>$row->timeD</div>&nbsp;<span>Days</span>
                                      <div class='hours'>$row->timeH</div>&nbsp;<span>Hours</span>
                                      <div class='minutes'>$row->timeM</div>&nbsp;<span>Min.</span>
                                      <div class='seconds'>$row->timeS</div>&nbsp;<span>Sec.</span>
                                </div>";
                    }
                ],

                ['class' => 'yii\grid\ActionColumn',
                    'template' => '{finish}',
                    'buttons' => [
                        'finish' => function ($url, $model, $key) {
                            if ($model->stat == 1) {
                                $button = Html::a(Icon::show('hourglass-end') . ' Stop', ['/task/end-task/'],
                                    ['class' => 'btn btn-xs btn-primary',
                                        'data' => [
                                            'pjax' => 0,
                                            'method' => 'POST',
                                            'params' => [
                                                'id' => $key
                                            ]
                                        ]]);
                            } else {
                                $button = '';
                            }
                            return $button;
                        }

                    ]
                ],
            ],
            'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
            'toolbar' => [
                ['content' => Html::button(Icon::show('calculator') . Yii::t('kvgrid', 'Summary'), [
                    'type' => 'button',
                    'title' => Yii::t('kvgrid', 'Summary'),
                    'class' => 'btn btn-primary',
                    'data-toggle' => 'modal',
                    'data-target' => '#summary-modal'
                ])],
                ['content' =>
                    Html::button(Icon::show('plus'), [
                        'type' => 'button',
                        'title' => Yii::t('kvgrid', 'Add Task'),
                        'class' => 'btn btn-success',
                        'data-toggle' => 'modal',
                        'data-target' => '#add-task-modal'
                    ]) .
                    Html::a(Icon::show('redo-alt'), ['/task/'],
                        [
                            'data-pjax' => 0,
                            'class' => 'btn btn-default',
                            'title' => Yii::t('kvgrid', 'Reset Grid')
                        ])
                ],
                '{toggleData}'
            ],
            'pjax' => true,
            'pjaxSettings' => [
                'neverTimeout' => true,
                'options' => [
                    'id' => 'task-grid-pjax',
                    'enablePushState' => false,
                ],
            ],

            'bordered' => true,
            'striped' => false,
            'condensed' => false,
            'responsive' => true,
            'hover' => true,
            'panel' => [
                'heading' => Icon::show('tasks') . 'Task',
                'type' => GridView::TYPE_PRIMARY,
                'footer' => false,
                'after' => false,
            ],
        ]);
        ?>

    </div>
<?php
Modal::begin([
    'id' => 'add-task-modal',
    'header' => 'New Task',
    'headerOptions' => [
        'class' => 'primary'
    ],
    'footer' => false,
    'footerOptions' => ['style' => 'display:none;']

]);

$form = ActiveForm::begin(['id' => 'new-task', 'action' => ['/task/add-task'], 'method' => 'POST'],);

echo $form->field($model, 'name')->textInput(['maxlength' => true]);
echo Html::beginTag('div', ['class' => 'text-right']);
echo Html::button(Icon::show('ban') . ' Cancel', [
    'type' => 'button',
    'class' => 'btn btn-default btn-sm',
    'style' => 'margin-right:5px',
    'data' => [
        'target' => '#add-task-modal',
        'dismiss' => 'modal'
    ]
]);
echo Html::button(Icon::show('plus') . ' Start', [
    'id' => 'add-task',
    'type' => 'submit',
    'class' => 'btn btn-success btn-sm',
]);
echo ' ';
echo Html::endTag('div');
ActiveForm::end();
Modal::end();
//Summary Modal
Modal::begin([
    'id' => 'summary-modal',
    'header' => 'Today Summary',
    'headerOptions' => [
        'class' => 'primary'
    ],
    'footer' => false,
    'footerOptions' => ['style' => 'display:none;']

]);
echo "<div class='content'>";
foreach ($summary as $element) {
    $hours = str_pad($element['Hours'], 2, '0', STR_PAD_LEFT);
    $minutes = str_pad($element['Minutes'], 2, '0', STR_PAD_LEFT);
    $seconds = str_pad($element['Seconds'], 2, '0', STR_PAD_LEFT);
    echo "
              <div class='row timer'>
                  <div class='col-xs-3' ><div class='Task'>{$element['Task']}</div>:&nbsp;</div>
                  <div class='col-xs-2 text-right' ><div class='days'>{$element['Days']}</div>&nbsp;Days</div>
                  <div class='col-xs-2 text-right' ><div class='hours'>{$hours}</div>&nbsp;Hours</div>
                  <div class='col-xs-2 text-right' ><div class='minutes'>{$minutes}</div>&nbsp;Min.</div>
                  <div class='col-xs-2 text-right' ><div class='seconds'>{$seconds}</div>&nbsp;Sec.</div>
              </div>
          ";

}
echo "</div>";
Modal::end();


$js = <<<JS
function makeTimer() {
	$.each($('.running .seconds'),function(key,val){
	    var seconds = $(val);
	    var nextSecond = parseInt(seconds.html()) + 1;
	    if( nextSecond >= 60){
	        seconds.html('00');
	        var minutes = $(val).siblings('.minutes'); 
	        var nextMinute = parseInt(minutes.html()) + 1;
	        if(nextMinute >= 60 ){
	            minutes.html('00');
	            var hours = $(val).siblings('.hours').html(); 
	            var nextHour = parseInt(hours.html()) + 1;
	            if(nextHour >= 24){
	                var days = $(val).siblings('.days'); 
	                days.html(parseInt(days.html()) + 1);
	            }else{
	                hours.html(("0" + nextHour).slice(-2));
	            }
	        }else{
	            minutes.html(("0" + nextMinute).slice(-2));
	        }
	    }else{
	        seconds.html(("0" + nextSecond).slice(-2));
	    }
	});
}

$(document).ready(function (){
    setInterval(function() { makeTimer(); }, 1000);
});
JS;
$this->registerJs($js);


