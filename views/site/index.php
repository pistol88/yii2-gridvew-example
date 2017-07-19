<?php
use yii\grid\GridView;
use yii\jui\DatePicker;
use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Congratulations!</h1>

        <p class="lead">You have successfully created your Yii-powered application.</p>

        <p><a class="btn btn-lg btn-success" href="http://www.yiiframework.com">Get started with Yii</a></p>
    </div>

    <div class="body-content">

        <?= GridView::widget([
            'dataProvider' => $eventsDataProvider,
            'filterModel' => $eventsSearchModel,
            'columns' => [
                'name',
                [
                    'attribute' => 'date',
                    'filter' => DatePicker::widget(['name' => 'EventSearch[date_from]', 'dateFormat' => 'yyyy-MM-dd', 'value' => $eventsSearchModel->date_from])
                                .
                                DatePicker::widget(['name' => 'EventSearch[date_to]', 'dateFormat' => 'yyyy-MM-dd', 'value' => $eventsSearchModel->date_to])
                ],
                [
                    'label' => 'Дистанции',
                    'filter' => Html::input("number", 'EventSearch[distance_from]', $eventsSearchModel->distance_from)
                                .
                                Html::input("number", 'EventSearch[distance_to]', $eventsSearchModel->distance_to),
                    'content' => function($model) {
                        $return = '<ul>';
                        foreach($model->distances as $distance) {
                            $return .= "<li>{$distance->name}, {$distance->value}</li>";
                        }
                        $return .= '</ul>';
                        
                        return $return;
                    }
                ],
                [
                    'label' => 'Количество участников',
                    'filter' => '',
                    'content' => function($model) {
                        return $model->getUserCount();
                    }
                ],
                // ...
            ],
        ]) ?>

    </div>
</div>
