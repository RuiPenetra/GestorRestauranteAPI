<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "categoria_produto".
 *
 * @property int $id
 * @property string $nome
 * @property int $editavel
 *
 * @property Produto[] $produtos
 */
class CategoriaProduto extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'categoria_produto';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nome', 'editavel'], 'required'],
            [['editavel'], 'integer'],
            [['nome'], 'string', 'max' => 255],
            [['nome'], 'unique'],
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
            'editavel' => 'Editavel',
        ];
    }

    /**
     * Gets query for [[Produtos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProdutos()
    {
        return $this->hasMany(Produto::className(), ['id_categoria' => 'id']);
    }
}
