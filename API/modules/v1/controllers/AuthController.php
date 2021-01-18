<?php

namespace app\modules\v1\controllers;

use app\models\User;
use Yii;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\rest\ActiveController;
use yii\web\Controller;
use yii\web\Response;
use app\models\Perfil;
use yii\web\HttpException;
/**
 * Default controller for the `v1` module
 */
class AuthController extends ActiveController
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

    public function actions()
    {
        $actions = parent::actions();

        unset($actions['index']);
        return $actions;
    }

    public function actionIndex()
    {
        $request = Yii::$app->request;
        $username=$request->post('username');
        $password=$request->post('password');

        $user = User::findByUsername($username);
        if ($user && $user->validatePassword($password)){
            return $user;
        }
        return null;
    }

    public function actionLogin()
    {
        $request = Yii::$app->request;
        $username=$request->post('username');
        $password=$request->post('password');

        $user = User::findByUsername($username);
        if ($user && $user->validatePassword($password)){

            $perfil=Perfil::findOne($user->id);
            return [
                'success'=>true,
                'id_user' => $perfil->id_user,
                'nome' => $perfil->nome,
                'apelido' => $perfil->apelido,
                'genero' => $perfil->genero,
                'cargo' => $perfil->cargo,
                'token' => $perfil->user->auth_key
            ];

        }else{
            return null;
        }
    }

    public function actionRegistar()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $user = new User();
        $user->attributes = Yii::$app->request->post();
        $user->username = Yii::$app->request->post("username");
        $user->email = Yii::$app->request->post("email");

        $user->setPassword(Yii::$app->request->post("password"));
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        if ($user->save() == true) {

            $perfil = new Perfil();
            $perfil->attributes = Yii::$app->request->post();
            $perfil->id_user = $user->id;
            $perfil->cargo = "cliente";

            if ($perfil->save() == true) {

                return ['id_user' => $perfil->id_user,
                    'nome' => $perfil->nome,
                    'apelido' => $perfil->apelido,
                    'morada' => $perfil->morada,
                    'datanascimento' => $perfil->datanascimento,
                    'nacionalidade' => $perfil->nacionalidade,
                    'codigopostal' => $perfil->codigopostal,
                    'telemovel' => $perfil->telemovel,
                    'genero' => $perfil->genero,
                    'cargo' => $perfil->cargo,
                    'username' => $perfil->user->username,
                    'email' => $perfil->user->email];
            }else{
                $user = User::findOne($user->id);
                $user->delete();

            }
        }else{
            throw new HttpException("500", 'Credenciais Invalidas.');
        }

        return $user;
    }

}
