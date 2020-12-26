<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PedidoProduto;

/**
 * PedidoprodutoSearch represents the model behind the search form of `app\models\PedidoProduto`.
 */
class PedidoprodutoSearch extends PedidoProduto
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_pedido', 'id_produto', 'estado', 'quant_Pedida', 'quant_Entregue'], 'integer'],
            [['preco'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = PedidoProduto::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_pedido' => $this->id_pedido,
            'id_produto' => $this->id_produto,
            'estado' => $this->estado,
            'quant_Pedida' => $this->quant_Pedida,
            'preco' => $this->preco,
            'quant_Entregue' => $this->quant_Entregue,
        ]);

        return $dataProvider;
    }
}
