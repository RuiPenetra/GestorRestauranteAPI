<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pedido_produto".
 *
 * @property int $id
 * @property int $id_pedido
 * @property int $id_produto
 * @property int $estado
 * @property int $quant_Pedida
 * @property float $preco
 * @property int $quant_Entregue
 *
 * @property Produto $produto
 * @property Pedido $pedido
 */
class PedidoProduto extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pedido_produto';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_pedido', 'id_produto', 'estado', 'quant_Pedida', 'preco', 'quant_Entregue'], 'required'],
            [['id_pedido', 'id_produto', 'estado', 'quant_Pedida', 'quant_Entregue'], 'integer'],
            [['preco'], 'number'],
            [['id_produto'], 'exist', 'skipOnError' => true, 'targetClass' => Produto::className(), 'targetAttribute' => ['id_produto' => 'id']],
            [['id_pedido'], 'exist', 'skipOnError' => true, 'targetClass' => Pedido::className(), 'targetAttribute' => ['id_pedido' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_pedido' => 'Id Pedido',
            'id_produto' => 'Id Produto',
            'estado' => 'Estado',
            'quant_Pedida' => 'Quant Pedida',
            'preco' => 'Preco',
            'quant_Entregue' => 'Quant Entregue',
        ];
    }

    /**
     * Gets query for [[Produto]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduto()
    {
        return $this->hasOne(Produto::className(), ['id' => 'id_produto']);
    }

    /**
     * Gets query for [[Pedido]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPedido()
    {
        return $this->hasOne(Pedido::className(), ['id' => 'id_pedido']);
    }
}
