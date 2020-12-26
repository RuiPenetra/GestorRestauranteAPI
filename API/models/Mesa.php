<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mesa".
 *
 * @property int $id
 * @property int $n_lugares
 * @property int $estado
 *
 * @property Pedido[] $pedidos
 * @property Reserva[] $reservas
 */
class Mesa extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mesa';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'n_lugares', 'estado'], 'required'],
            [['id', 'n_lugares', 'estado'], 'integer'],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'n_lugares' => 'N Lugares',
            'estado' => 'Estado',
        ];
    }

    /**
     * Gets query for [[Pedidos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPedidos()
    {
        return $this->hasMany(Pedido::className(), ['id_mesa' => 'id']);
    }

    /**
     * Gets query for [[Reservas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReservas()
    {
        return $this->hasMany(Reserva::className(), ['id_mesa' => 'id']);
    }
}
