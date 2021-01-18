<?php

namespace app\modules\v1\controllers;

use app\models\Produto;
use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;
use yii\web\Response;

class ProdutoController extends ActiveController
{
    public $modelClass='app\models\Produto';

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
        $model = Produto::findAll(['estado' => 0]);

        return $model;
    }

    public function actionCategoria($id)
    {
        $model = Produto::findAll(['id_categoria' => $id,'estado' => 0]);

        return $model;
    }
}
