<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "perfil".
 *
 * @property int $id_user
 * @property string $nome
 * @property string $apelido
 * @property string $morada
 * @property string $datanascimento
 * @property string $codigopostal
 * @property string $nacionalidade
 * @property string $telemovel
 * @property int $genero
 * @property string $cargo
 *
 * @property Falta[] $faltas
 * @property Horario[] $horarios
 * @property Pedido[] $pedidos
 * @property User $user
 * @property Reserva[] $reservas
 */
class Perfil extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'perfil';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_user', 'nome', 'apelido', 'morada', 'datanascimento', 'codigopostal', 'nacionalidade', 'telemovel', 'genero', 'cargo'], 'required'],
            [['id_user', 'genero'], 'integer'],
            [['datanascimento'], 'safe'],
            [['nome', 'apelido', 'morada', 'codigopostal', 'nacionalidade', 'telemovel', 'cargo'], 'string', 'max' => 255],
            [['telemovel'], 'unique'],
            [['id_user'], 'unique'],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_user' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_user' => 'Id User',
            'nome' => 'Nome',
            'apelido' => 'Apelido',
            'morada' => 'Morada',
            'datanascimento' => 'Datanascimento',
            'codigopostal' => 'Codigopostal',
            'nacionalidade' => 'Nacionalidade',
            'telemovel' => 'Telemovel',
            'genero' => 'Genero',
            'cargo' => 'Cargo',
        ];
    }

    /**
     * Gets query for [[Faltas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFaltas()
    {
        return $this->hasMany(Falta::className(), ['id_funcionario' => 'id_user']);
    }

    /**
     * Gets query for [[Horarios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHorarios()
    {
        return $this->hasMany(Horario::className(), ['id_funcionario' => 'id_user']);
    }

    /**
     * Gets query for [[Pedidos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPedidos()
    {
        return $this->hasMany(Pedido::className(), ['id_perfil' => 'id_user']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'id_user']);
    }

    /**
     * Gets query for [[Reservas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReservas()
    {
        return $this->hasMany(Reserva::className(), ['id_funcionario' => 'id_user']);
    }

    /**
     * @param int $id_user
     */
    public function setIdUser($id_user)
    {
        $this->id_user = $id_user;
    }

    /**
     * @param string $nome
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    /**
     * @param string $apelido
     */
    public function setApelido($apelido)
    {
        $this->apelido = $apelido;
    }

    /**
     * @param string $morada
     */
    public function setMorada($morada)
    {
        $this->morada = $morada;
    }

    /**
     * @param string $datanascimento
     */
    public function setDatanascimento($datanascimento)
    {
        $this->datanascimento = $datanascimento;
    }

    /**
     * @param string $codigopostal
     */
    public function setCodigopostal($codigopostal)
    {
        $this->codigopostal = $codigopostal;
    }

    /**
     * @param string $nacionalidade
     */
    public function setNacionalidade($nacionalidade)
    {
        $this->nacionalidade = $nacionalidade;
    }

    /**
     * @param string $telemovel
     */
    public function setTelemovel($telemovel)
    {
        $this->telemovel = $telemovel;
    }

    /**
     * @param int $genero
     */
    public function setGenero($genero)
    {
        $this->genero = $genero;
    }

    /**
     * @param string $cargo
     */
    public function setCargo($cargo)
    {
        $this->cargo = $cargo;
    }


}
