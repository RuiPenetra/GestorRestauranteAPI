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
/*        $perfil=Perfil::find()
            ->where(['perfil.id_user'=>$iduser])
            ->joinWith('user')
            ->asArray()
            ->all();*/

        $perfil=Perfil::find()
            ->select('perfil.*,user.*')
            ->leftJoin('user','user.id = perfil.id_user')
            ->where(['perfil.id_user'=>$iduser])
            ->asArray()
            ->all();

//        SELECT p.*, u.username, u.email FROM perfil p JOIN user u on p.id_user=u.id
        $user= Perfil::find()->where(['id_user'=>$iduser])->with('user')->where(['id_user'=>$iduser]);

        $user=User::findOne($iduser);
        if ($perfil === null)
            throw new \yii\web\NotFoundHttpException("null");

        return $perfil;
    }

    public function actionUpdate($id)
    {
        $modelClass = $this->modelClass;

        \Yii::$app->response->format=\yii\web\Response::FORMAT_JSON;
        $perfil = Perfil::findOne($id);

        $perfil->nome = $request->post('nome');
        $perfil->apelido = $request->post('apelido');
        $perfil->morada = $request->post('morada');
        $perfil->datanascimento = $request->post('datanascimento');
        $perfil->codigopostal = $request->post('codigopostal');
        $perfil->telemovel = $request->post('telemovel');
        $perfil->genero = $request->post('genero');
        $perfil->nacionalidade = $request->post('nacionalidade');
        $perfil->save();

        $user = User::findOne($perfil->id_user);
        $user->email = $request->post('email');
        $user->username = $request->post('username');

        if ($request->post('nova_password') != null) {
            $user->setPassword($request->post('nova_password'));
        }
        $user->save();

        $allUser = Perfil::find()
            ->select('perfil.*,user.*')
            ->leftJoin('user', 'user.id = perfil.id_user')
            ->where(['perfil.id_user' => $user->id])
            ->asArray()
            ->all();

        return $allUser;
    }
}















