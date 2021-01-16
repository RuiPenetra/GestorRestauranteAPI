<?php

namespace app\modules\v1\controllers;

use app\models\User;
use Yii;
use app\models\Perfil;
use app\models\PerfilSearch;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
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
                    'auth' => function ($username, $password) {
                        $user = User::find()->where(['username' => $username])->one();
                        if ($user->verifyPassword($password)) {
                            return $user;
                        }
                        return null;
                    },
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

    public function actionIndex()
    {
        $iduser = Yii::$app->user->identity->id;

        $perfil= Perfil::findOne($iduser);;

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
    }

    public function actionCriar()
    {
        Yii::$app->response->format=Response::FORMAT_JSON;
        
        $user = new User();
        $user->attributes=Yii::$app->request->post();
        $user->save();
       
        $request = Yii::$app->request;
        // $perfil= new Perfil();
        // $perfil->attributes=Yii::$app->request->post();
        
        // $perfil->save();
        
        return $user;
    }


    public function actionAtualizar($id)
    {
        Yii::$app->response->format=Response::FORMAT_JSON;

        $user = User::findOne($id);

        $user->username=Yii::$app->request->post('username');
        $user->email=Yii::$app->request->post('email');

        $user->save();

        $perfil = Perfil::findOne($user->id);

        $perfil->attributes=Yii::$app->request->post();

        $perfil->save();


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
                    'username' => $user->username,
                    'email' => $user->email];
    }
}















