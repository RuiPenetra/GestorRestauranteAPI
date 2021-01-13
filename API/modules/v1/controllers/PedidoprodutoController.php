<?php

namespace app\modules\v1\controllers;

use Yii;
use app\models\PedidoProduto;
use app\models\PedidoprodutoSearch;
use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * PedidoprodutoController implements the CRUD actions for PedidoProduto model.
 */
class PedidoprodutoController extends ActiveController
{
    public $modelClass='app\models\PedidoProduto';

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


    /**
     * Lists all PedidoProduto models.
     * @return mixed
     */
    public function actionIndex()
    {
        $pedidos=PedidoProduto::find()->all();

        if ($pedidos != null)
            return $pedidos;
        else
            throw new NotFoundHttpException('Não existe pedidos');
    }

    /**
     * Displays a single PedidoProduto model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionAll($id)
    {

        $pedidosProduto=PedidoProduto::findAll(['id_pedido'=>$id]);

        return $pedidosProduto;
    }

    /**
     * Creates a new PedidoProduto model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCriar()
    {
        Yii::$app->response->format=Response::FORMAT_JSON;

        $pedidoProduto = new PedidoProduto();

        $pedidoProduto->attributes=Yii::$app->request->post();

        $pedidoProduto->quant_Entregue=0;
        $pedidoProduto->quant_Preparacao=0;
        if($pedidoProduto->save()){

            return $pedidoProduto;
        }else{
            return Yii::$app->response->send('ERROOOO');
        }
    }

    /**
     * Updates an existing PedidoProduto model.
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
     * Deletes an existing PedidoProduto model.
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
     * Finds the PedidoProduto model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PedidoProduto the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PedidoProduto::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
