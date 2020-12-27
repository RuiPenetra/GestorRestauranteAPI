<?php

namespace app\modules\v1\controllers;

use app\models\User;
use Yii;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\rest\ActiveController;
use yii\web\Controller;
use yii\web\Response;

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
            return ['token'=> $user->auth_key];
        }else{
            if($user==null) {
                return ['message'=>'Username incorreto'];
                /* throw new \yii\web\NotFoundHttpException("Username incorreto");*/
            }elseif ($user->validatePassword($password)==false){
                return ['message'=>'Password incorreta'];
                /*            throw new \yii\web\NotFoundHttpException("Password incorreta");*/
            }else{
                return ['message'=>'Utilizador não existe'];
//            throw new \yii\web\NotFoundHttpException("Utilizador não existe");
            }
        }
    }

}
