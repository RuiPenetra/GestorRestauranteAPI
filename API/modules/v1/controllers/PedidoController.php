<?php

namespace app\modules\v1\controllers;

use app\models\Mesa;
use app\models\PedidoProduto;
use app\models\Perfil;
use app\models\User;
use Yii;
use app\models\Pedido;
use app\models\PedidoSearch;
use yii\base\ErrorException;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\QueryParamAuth;
use yii\helpers\ArrayHelper;
use yii\rest\ActiveController;
use yii\web\Controller;
use yii\web\ErrorHandler;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * PedidoController implements the CRUD actions for Pedido model.
 */
class PedidoController extends ActiveController
{
    public $modelClass='app\models\Pedido';

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
        $iduser = Yii::$app->user->identity->id;

        $pedido = new $this->modelClass;

        $pedidos=$pedido::findAll(['id_perfil'=>$iduser]);


        if($pedidos!=null){
            return $pedidos;
        }else{
            throw new NotFoundHttpException('Não existe pedidos associados a este utilizador');
        }

    }


    public function actionVerPed($id_user)
    {

        $perfil=Perfil::findOne($id_user);


        $pedido=Pedido::find()->where(['id_perfil'=>$perfil->id_user])->all();


        return $pedido;

    }

    public function actionPedrestaurante()
    {

        $pedido = new $this->modelClass;

        $id_perfil= Yii::$app->user->identity->id;
        $id_mesa= Yii::$app->request->post("id_mesa");
        $data= Yii::$app->request->post("data");

        $pedido->id_perfil= $id_perfil;
        $pedido->tipo= 0;
        $pedido->data= $data;
        $pedido->estado= 1;
        $pedido->id_mesa= $id_mesa;
        $pedido->scenario="scenariorestaurante";

        $mesa=Mesa::findOne($id_mesa);

        if($mesa->estado!=2){
            return ['SaveError'=>false];
        }
        $res=$pedido->save();

        if($res==true){
            $mesa=Mesa::findOne($pedido->id_mesa);
            $mesa->estado=1;
            $mesa->save();
            return ['SaveError'=>$res];
        }

        return ['SaveError'=>false];
    }

    public function actionPedtakeaway()
    {

        $pedido = new $this->modelClass;

        $id_perfil= Yii::$app->user->identity->id;
        $nome_pedido= Yii::$app->request->post("nome_pedido");
        $data= Yii::$app->request->post("data");


        $pedido->id_perfil= $id_perfil;
        $pedido->tipo= 1;
        $pedido->data= $data;
        $pedido->estado= 0;
        $pedido->scenario="scenariotakeaway";
        $pedido->nome_pedido= $nome_pedido;
        $res=$pedido->save();

        if($res==true){
            return ['SaveError'=>$res];
        }

        return ['SaveError'=>$res];
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }


    public function actionApagar($id)
    {
        PedidoProduto::deleteAll(['id_pedido'=>$id]);

        $pedido = new $this->modelClass;

        $pedido = $pedido::findOne($id);

        if($pedido->tipo==0){
            $mesa=Mesa::findOne($pedido->id_mesa);
            $mesa->estado=2;
            $mesa->save();
        }

        $rest=$pedido->delete();

        if($rest==true) {
            Yii::$app->response->statusCode =200;
            return ['code'=>'ok'];
        }else{
            Yii::$app->response->statusCode =404;
            return ['code'=>'error'];
        }

    }

}
