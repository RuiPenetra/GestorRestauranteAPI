<?php

namespace app\modules\v1\controllers;

use app\models\Perfil;
use app\models\User;
use Yii;
use app\models\Pedido;
use app\models\PedidoSearch;
use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;
use yii\web\Controller;
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
        $iduser = Yii::$app->user->identity->id;

        $pedidos=Pedido::findAll(['id_perfil'=>$iduser]);

        if ($pedidos != null)
            return $pedidos;
        else
            throw new NotFoundHttpException('Não existe pedidos');

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

    public function actionCreateRestaurante()
    {
        Yii::$app->response->format=Response::FORMAT_JSON;
        $pedido = new Pedido();
        $request = Yii::$app->request;
        $pedido->scenario="scenariorestaurante";
        $pedido->estado=0;
        $pedido->tipo=0;
        $pedido->id_mesa=$request->post('id_mesa');
        $pedido->data=$request->post('data');
        $pedido->id_perfil=Yii::$app->user->identity->id;


        if ($pedido->save()) {

            return $pedido;

        }else{
            throw new NotFoundHttpException('Erro criar pedido');
        }

    }

    /**
     * Updates an existing Pedido model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
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

    /**
     * Deletes an existing Pedido model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
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
