<?php

namespace app\modules;

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
        \Yii::$app->response->format=\yii\web\Response::FORMAT_JSON;
        $pedido = new Pedido();
        $request = Yii::$app->request;
        $pedido->scenario='scenariorestaurante';
        $pedido = new Pedido();
        $pedido->estado = $request->post('nome');


        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
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
