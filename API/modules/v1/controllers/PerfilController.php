<?php

namespace app\modules\v1\controllers;

use app\models\User;
use Yii;
use app\models\Perfil;
use app\models\PerfilSearch;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
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
            'class' => CompositeAuth::className(),
            'authMethods' => [
                [
                    'class' => HttpBasicAuth::className(),
                    'auth' => function ($username, $password) {
                        $user = User::find()->where(['username' => $username])->one();
                        if ($user->verifyPassword($password)) {
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

    public function actionCriar()
    {
        Yii::$app->response->format=Response::FORMAT_JSON;
        
        $user = new User();
        $user->attributes=Yii::$app->request->post();
        $user->save();
       
        $request = Yii::$app->request;
        // $perfil= new Perfil();
        // $perfil->attributes=Yii::$app->request->post();
        
        // $perfil->save();
        
        return $user;
    }


    public function actionUpdate($id)
    {
        $modelClass = $this->modelClass;

        Yii::$app->response->format=Response::FORMAT_JSON;
        $perfil = Perfil::findOne($id);


        $perfil->load(Yii::$app->request->post());

        $perfil->save();

        $user = User::findOne($perfil->id_user);
        $user->load(Yii::$app->request->post());

        $user->save();

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















