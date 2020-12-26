<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PedidoprodutoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pedido Produtos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pedido-produto-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Pedido Produto', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'id_pedido',
            'id_produto',
            'estado',
            'quant_Pedida',
            //'preco',
            //'quant_Entregue',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
