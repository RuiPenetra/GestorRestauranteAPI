<?php

namespace app\modules\v1\controllers;

use app\models\Perfil;
use app\models\User;
use Yii;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\filters\ContentNegotiator;
use yii\rest\ActiveController;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;


/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends ActiveController
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

        $user = new $this->modelClass;

        $rest=$user::findOne($iduser);

        if($rest!=null){
            return $rest;
        }else{
            return null;
        }

    }
    public function actionCriar()
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
