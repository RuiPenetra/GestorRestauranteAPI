<?php

namespace app\modules\v1\controllers;

use app\models\Pedido;
use app\models\User;
use Yii;
use app\models\PedidoProduto;
use app\models\PedidoprodutoSearch;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
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


    /**
     * Lists all PedidoProduto models.
     * @return mixed
     */
    public function actionIndex($id)
    {
        $pedidoProduto = new $this->modelClass;

        $rest=$pedidoProduto::findAll(['id_pedido'=>$id]);


            return $rest;


    }

    /**
     * Devolve a resposta TRUE or FALSE
     */
    public function actionAddrestaurante()
    {
        $id_pedido=Yii::$app->request->post('id_pedido');
        $id_produto=Yii::$app->request->post('id_produto');
        $quant_Pedida=Yii::$app->request->post('quant_Pedida');
        $preco=Yii::$app->request->post('preco');


        $pedidoProduto = new $this->modelClass;


        $pedProduto=$pedidoProduto::findOne(['id_pedido'=>$id_pedido,'id_produto'=>$id_produto]);

        if($pedProduto!=null){
            $pedProduto->quant_Pedida=$pedProduto->quant_Pedida + $quant_Pedida;
            $pedProduto->preco=$pedProduto->preco + $preco;
            $rest=$pedProduto->save();

        }else{
            $pedidoProduto->id_pedido=$id_pedido;
            $pedidoProduto->id_produto=$id_produto;
            $pedidoProduto->quant_Pedida=$quant_Pedida;
            $pedidoProduto->preco=$preco;
            $pedidoProduto->quant_Entregue=0;
            $pedidoProduto->quant_Preparacao=0;
            $pedidoProduto->estado=0;

            $rest= $pedidoProduto->save();
        }

        return ['SaveError'=>$rest];


    }


    public function actionAddtakeaway()
    {
        $iduser = Yii::$app->user->identity->id;

        $Items=Yii::$app->request->post('items');
        $id_produto=Yii::$app->request->post('id_produto');
        $quant=Yii::$app->request->post('quant');
        $preco=Yii::$app->request->post('preco');


        //$array = json_decode($response, true);

        $pedido=Pedido::findOne(['id_perfil'=>$iduser, 'estado'=>0]);

        $pedidoProduto = new $this->modelClass;

        $pedidoProduto->id_pedido= $pedido->id;
        $pedidoProduto->id_produto= $id_produto;
        $pedidoProduto->quant_Pedida= $quant;
        $pedidoProduto->preco= $preco;
        $pedidoProduto->quant_Entregue= 0;
        $pedidoProduto->quant_Preparacao= 0;
        $pedidoProduto->estado=0;

        $res= $pedidoProduto->save();

        $itemsPedido= sizeof($pedidoProduto::findAll(['id_pedido'=>$pedido->id]));

        if($Items==$itemsPedido){
            $pedido->estado=1;
            $pedido->save();
        }

        return ['SaveError'=>$res];
    }

    public function actionAtualizar($id)
    {
        $quant_Pedida=Yii::$app->request->post('quant_Pedida');
        $preco=Yii::$app->request->post('preco');

        $pedidoProduto = new $this->modelClass;

        $rest=$pedidoProduto::findOne($id);

        if(count($rest)>0){
            $rest->quant_Pedida=$quant_Pedida;
            $rest->preco=$preco;

            if($rest->estado!=0){
                $rest->estado=1;
            }

            $response=$rest->save();

            return ['SaveError'=>$response];
        }else{
            throw new NotFoundHttpException("Pedido Produto não encontrado!");
        }

    }

    public function actionRemover($id)
    {
        $pedidoProduto = new $this->modelClass;

        $rest=$pedidoProduto::findOne($id);
        $response=$rest->delete();

        if($response) {
            Yii::$app->response->statusCode =200;
            return ['code'=>'ok'];
        }else{
            Yii::$app->response->statusCode =404;
            return ['code'=>'error'];
        }
    }
}
