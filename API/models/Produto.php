<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "produto".
 *
 * @property int $id
 * @property string $nome
 * @property string|null $ingredientes
 * @property float $preco
 * @property int $id_categoria
 * @property int $estado
 *
 * @property PedidoProduto[] $pedidoProdutos
 * @property CategoriaProduto $categoria
 */
class Produto extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'produto';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nome', 'preco', 'id_categoria', 'estado'], 'required'],
            [['preco'], 'number'],
            [['id_categoria', 'estado'], 'integer'],
            [['nome'], 'string', 'max' => 255],
            [['ingredientes'], 'string', 'max' => 500],
            [['nome'], 'unique'],
            [['id_categoria'], 'exist', 'skipOnError' => true, 'targetClass' => CategoriaProduto::className(), 'targetAttribute' => ['id_categoria' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nome' => 'Nome',
            'ingredientes' => 'Ingredientes',
            'preco' => 'Preco',
            'id_categoria' => 'Id Categoria',
            'estado' => 'Estado',
        ];
    }

    /**
     * Gets query for [[PedidoProdutos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPedidoProdutos()
    {
        return $this->hasMany(PedidoProduto::className(), ['id_produto' => 'id']);
    }

    /**
     * Gets query for [[Categoria]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategoria()
    {
        return $this->hasOne(CategoriaProduto::className(), ['id' => 'id_categoria']);
    }
}
