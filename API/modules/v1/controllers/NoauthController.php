<?php

namespace app\modules\v1\controllers;

use app\models\Produto;
use app\models\User;
use Yii;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\rest\ActiveController;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\models\Perfil;
use yii\web\HttpException;
/**
 * Default controller for the `v1` module
 */
class NoauthController extends ActiveController
{

    public $modelClass='app\models\User';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator'] = [
            'class' => 'yii\filters\ContentNegotiator',
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ]
        ];

        return $behaviors;
    }

    public function actionRegistaruser()
    {
        //USER
        $username=Yii::$app->request->post("username");
        $email=Yii::$app->request->post("email");
        $password=Yii::$app->request->post("password");

        //PERFIL
        $nome=Yii::$app->request->post("nome");
        $apelido=Yii::$app->request->post("apelido");
        $morada=Yii::$app->request->post("morada");
        $datanascimento=Yii::$app->request->post("datanascimento");
        $nacionalidade=Yii::$app->request->post("nacionalidade");
        $codigopostal=Yii::$app->request->post("codigopostal");
        $telemovel=Yii::$app->request->post("telemovel");
        $genero=Yii::$app->request->post("genero");

        $user = new $this->modelClass;

        $user->username = $username;
        $user->email = $email;

        $user->setPassword($password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();

        $rest=$user->save();

        if ($rest==true) {

            $perfil = new Perfil();
            $perfil->id_user=$user->id;
            $perfil->nome=$nome;
            $perfil->apelido=$apelido;
            $perfil->morada=$morada;
            $perfil->datanascimento=$datanascimento;
            $perfil->nacionalidade=$nacionalidade;
            $perfil->codigopostal=$codigopostal;
            $perfil->telemovel=$telemovel;
            $perfil->genero=$genero;
            $perfil->cargo="cliente";

            $auth = Yii::$app->authManager;

            $cliente = $auth->getRole('cliente');
            $auth->assign($cliente, $user->id);

            $rest=$perfil->save();

            if($rest!=true){
                $user->delete();

            }
            return ['SaveError' => $rest];

        }

        return ['SaveError' => $rest];

    }


    public function actionTodosprodutos()
    {
        $produtos = Produto::findAll(['estado' => 0]);

        return $produtos;
    }

    public function actionProdutocategoria($id)
    {
        $produtos = Produto::findAll(['id_categoria' => $id,'estado' => 0]);

        return $produtos;
    }


}
