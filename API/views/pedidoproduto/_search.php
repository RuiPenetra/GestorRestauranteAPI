<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PedidoprodutoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pedido-produto-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_pedido') ?>

    <?= $form->field($model, 'id_produto') ?>

    <?= $form->field($model, 'estado') ?>

    <?= $form->field($model, 'quant_Pedida') ?>

    <?php // echo $form->field($model, 'preco') ?>

    <?php // echo $form->field($model, 'quant_Entregue') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
