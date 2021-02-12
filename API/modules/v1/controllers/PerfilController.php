<?php

namespace app\modules\v1\controllers;

use app\models\User;
use Yii;
use app\models\Perfil;
use app\models\PerfilSearch;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * PerfilController implements the CRUD actions for Perfil model.
 */
class PerfilController extends ActiveController

{
    public $modelClass='app\models\Perfil';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator'] = [
            'class' => 'yii\filters\ContentNegotiator',
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ]
        ];
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'authMethods' => [
                [
                    'class' => HttpBasicAuth::className(),
                    'auth' => [$this,'auth'],
                ],
                QueryParamAuth::className(),
            ]
        ];
        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();

        unset($actions['index']);
        return $actions;
    }

    public function auth($username,$password){
        $user = User::findByUsername($username);
        if ($user && $user->validatePassword($password)) {

            return $user;
        }
        return null;
    }

    public function actionIndex()
    {
        $iduser = Yii::$app->user->identity->id;

        $perfil = $this->modelClass;

        $rest=$perfil::findOne($iduser);

        return $rest;

    }

    public function actionAtualizar($id)
    {
        //USER
        $username=Yii::$app->request->post("username");
        $email=Yii::$app->request->post("email");

        //Futuramente
        //$password=Yii::$app->request->post("password");

        //PERFIL
        $nome=Yii::$app->request->post("nome");
        $apelido=Yii::$app->request->post("apelido");
        $morada=Yii::$app->request->post("morada");
        $datanascimento=Yii::$app->request->post("datanascimento");
        $nacionalidade=Yii::$app->request->post("nacionalidade");
        $codigopostal=Yii::$app->request->post("codigopostal");
        $telemovel=Yii::$app->request->post("telemovel");
        $genero=Yii::$app->request->post("genero");

        $user = User::findOne($id);

        $user->username = $username;
        $user->email = $email;

        $model = new $this->modelClass;

        $perfil=$model::findOne($id);
        $perfil->nome=$nome;
        $perfil->apelido=$apelido;
        $perfil->morada=$morada;
        $perfil->datanascimento=$datanascimento;
        $perfil->nacionalidade=$nacionalidade;
        $perfil->codigopostal=$codigopostal;
        $perfil->telemovel=$telemovel;
        $perfil->genero=$genero;
        $perfil->save();

        if($perfil->save() && $user->save()){

            return ['SaveError' => true];
        }else{
            return ['SaveError' => false];
        }

    }

    public function actionTodos()
    {

        $modelClass = $this->modelClass;

        $perfis=$modelClass::find()->all();

        return $perfis;
    }
}















