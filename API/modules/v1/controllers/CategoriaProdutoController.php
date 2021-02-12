<?php

namespace app\modules\v1\controllers;

use app\models\User;
use Yii;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class CategoriaProdutoController extends ActiveController
{
    public $modelClass='app\models\CategoriaProduto';

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
                        $user = User::findByUsername($username);
                        if ($user && $user->validatePassword($password)) {
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
        $categorias = new $this->modelClass;

        $rest=$categorias::findAll();

        if($rest!=null){
            return $rest;
        }else{
            throw new NotFoundHttpException("Não existe categorias!");
        }
    }

}
