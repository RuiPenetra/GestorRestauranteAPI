<?php

namespace app\modules\v1\controllers;

use app\models\User;
use Yii;
use app\models\Perfil;
use app\models\PerfilSearch;
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
            'class' => QueryParamAuth::className(),
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
        $modelClass = $this->modelClass;
        $model = $modelClass::find()->where(['id_user' => $iduser])->one();

        if ($model === null)
            throw new \yii\web\NotFoundHttpException("null");

        return $model;
    }
}
