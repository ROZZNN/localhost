<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Мои книги';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-my-books">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Загрузить новую книгу', ['upload'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => new \yii\data\ActiveDataProvider([
            'query' => \app\models\Book::find()
                ->joinWith('bookusers')
                ->where(['bookuser.id_user' => Yii::$app->user->id]),
        ]),
        'columns' => [
            'title',
            'author',
            'description:ntext',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{read} {delete}',
                'buttons' => [
                    'read' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-book"></span>', ['read', 'id' => $model->id], [
                            'title' => 'Читать',
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete-book', 'id' => $model->id], [
                            'title' => 'Удалить',
                            'data' => [
                                'confirm' => 'Вы уверены, что хотите удалить эту книгу?',
                                'method' => 'post',
                            ],
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>
</div> 