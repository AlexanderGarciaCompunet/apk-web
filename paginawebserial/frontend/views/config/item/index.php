<?php

use mdm\admin\models\AuthItem;
use yii\rbac\Item;
use \frontend\assets\MaterialAsset;
use \frontend\assets\InputsAsset;
use yii\helpers\Html;

use kartik\grid\GridView;
use mdm\admin\components\RouteRule;
use mdm\admin\components\Configs;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel mdm\admin\models\searchs\AuthItem */
/* @var $context mdm\admin\components\ItemController */

MaterialAsset::register($this);

InputsAsset::register($this);

$context = $this->context;
$labels = $context->labels();
$this->title = Yii::t('rbac-admin', $labels['Items']);
$this->params['breadcrumbs'][] = $this->title;

$rules = array_keys(Configs::authManager()->getRules());
$rules = array_combine($rules, $rules);
unset($rules[RouteRule::RULE_NAME]);

$model = new AuthItem(null);
$model->type = Item::TYPE_ROLE;
?>
<div class="container">
  <div class="row justify-content-center mt-4">
    <div class="col-md-4 mr-4 ">
      <div class="card">
        <div class="card-body">

          <?= $this->render('_form', ['model' => $model]); ?>
        </div>
      </div>
    </div>
    <div class="col-md-7">
      <div class="card">
        <div class="card-body">
          <div class="card-text font-weight-bold">
            Listado de Roles
          </div>
          <?=
          GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'summary'=> '',
            'columns' => [
              ['class' => 'yii\grid\SerialColumn'],
              [
                'attribute' => 'name',
                'label' => Yii::t('rbac-admin', 'Name'),
              ],
              [
                'attribute' => 'description',
                'label' => Yii::t('rbac-admin', 'Description'),
              ],
              [
                'class' => 'yii\grid\ActionColumn',
                'header'=>'Acciones',
                'template' => '<div class="d-flex justify-content-center">{view}{delete}</div>',
                'buttons' => [
                  'view' => function ($url) {
                    return Html::a(
                      '<svg aria-hidden="true" style="display:inline-block;font-size:inherit;height:1em;overflow:visible;vertical-align:-.125em;width:1em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M498 142l-46 46c-5 5-13 5-17 0L324 77c-5-5-5-12 0-17l46-46c19-19 49-19 68 0l60 60c19 19 19 49 0 68zm-214-42L22 362 0 484c-3 16 12 30 28 28l122-22 262-262c5-5 5-13 0-17L301 100c-4-5-12-5-17 0zM124 340c-5-6-5-14 0-20l154-154c6-5 14-5 20 0s5 14 0 20L144 340c-6 5-14 5-20 0zm-36 84h48v36l-64 12-32-31 12-65h36v48z"></path></svg>',
                      $url,
                      [
                        'title' => 'Editar',
                        'class' => 'mr-3'

                      ],
                    );
                  },
                ]
              ],
            ],
            'striped' => true,
            'resizableColumns' => true,
            'responsiveWrap' => true,
            'responsive' => true,
            'floatOverflowContainer' => true,
          ])
          ?>
        </div>
      </div>
    </div>
  </div>
</div>
