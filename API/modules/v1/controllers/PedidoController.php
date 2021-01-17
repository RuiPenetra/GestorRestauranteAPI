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
        Yii::$app->response->format=Response::FORMAT_JSON;

        $iduser = Yii::$app->user->identity->id;

        $pedidos=Pedido::findAll(['id_perfil'=>$iduser]);

      
        return $pedidos;

    }


    public function actionView($id_user)
    {

        $perfil=Perfil::findOne($id_user);


        $pedido=Pedido::find()->where(['id_perfil'=>$perfil->id_user])->all();

        if($pedido!=null){

            return $pedido;

        }else{
            throw new NotFoundHttpException('Não existe pedidos');
        }
    }

    public function actionCriar()
    {

        Yii::$app->response->format=Response::FORMAT_JSON;
        $pedido = new Pedido();

        $pedido->attributes=Yii::$app->request->post();

        if($pedido->tipo!=0){
            $pedido->scenario="scenariotakeaway";

        }else{
            $pedido->scenario="scenariorestaurante";
        }

        if($pedido->tipo==0){
            $mesa=Mesa::findOne($pedido->id_mesa);
            $mesa->estado=2;
            $mesa->save();
        }
        
        $pedido->save();
        $pedido_guardado=Pedido::findOne($pedido->id);

        return $pedido_guardado;
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

        $pedido=$this->findModel($id);

        if($pedido->tipo==0){
            $mesa=Mesa::findOne($pedido->id_mesa);
            $mesa->estado=2;
            $mesa->save();
        }

        $pedido->delete();

        return true;
    }

    /**
     * Finds the Pedido model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Pedido the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Pedido::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
