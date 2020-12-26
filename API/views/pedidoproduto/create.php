<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PedidoProduto */

$this->title = 'Create Pedido Produto';
$this->params['breadcrumbs'][] = ['label' => 'Pedido Produtos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pedido-produto-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
