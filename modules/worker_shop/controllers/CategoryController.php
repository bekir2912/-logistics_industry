<?php
namespace app\modules\worker_shop\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\HttpException;

use app\models\user\User;
use app\models\Category;

class CategoryController extends Controller{
    public $user;

    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        if (($action->id == 'get-category') && ($action->id == 'sub-category')) {
            $this->enableCsrfValidation = false;
        }
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/worker_shop']);
        }
        $this->user = User::find()->with('moderatorAccess', 'moderatorAccess.moderator')->where(['id'=>Yii::$app->user->identity->id])->one();

        if (($this->user->role == User::ROLE_MODERATOR)) {
            $accesses = array();

            if ($this->user && $this->user->moderatorAccess) {
                foreach ($this->user->moderatorAccess as $v) {
                    if ($v && $v->moderator) {
                        $accesses[] = $v->moderator->url;
                    }
                }
            }

            if (!in_array('category', $accesses)) {
                throw new HttpException(403, 'Access Forbidden');
            }
        }

        return parent::beforeAction($action);
    }

    public function actionIndex($type = null) {
        $model = new Category;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->type = $type;
            if ($model->saveCategory()) {
                Yii::$app->session->setFlash('category_saved', 'Категория успешно сохранена');
            }
            return $this->redirect(Yii::$app->request->referrer);
        }

        $categories = $model->getCategories($model->find()->orderBy('sort')->asArray()->where(['type'=>$type])->all());

        return $this->render('index', [
            'model' => $model,
            'categories' => $categories,
            'type' => $type
        ]);
    }

    public function actionRemove($id) {
        $model = Category::findOne($id);

        if ($this->user && ($this->user->role != User::ROLE_USER) && $model) {
            if ($model->image) {
                $model->image->removeImageSize();
            }
            if ($model->delete()) {
                Yii::$app->session->setFlash('category_removed', 'Категория успешна удалена');
            }
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionSaveSort(){
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if ($data['top']) {
                $top = explode(',', $data['top']);
                foreach ($top as $k => $v) {
                    $id = explode('-',$v);
                    $cat = Category::findOne($id[1]);
                    $cat->sort = $id[0];
                    $cat->save(false);
                }
            }
            if ($data['second']) {
                $second = explode(',', $data['second']);
                foreach ($second as $k => $v) {
                    $id = explode('-',$v);
                    $cat = Category::findOne($id[1]);
                    $cat->sort = $id[0];
                    $cat->save(false);
                }
            }
            if ($data['third']) {
                $third = explode(',', $data['third']);
                foreach ($third as $k => $v) {
                    $id = explode('-',$v);
                    $cat = Category::findOne($id[1]);
                    $cat->sort = $id[0];
                    $cat->save(false);
                }
            }
            $save = true;
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ['save'=>$save];
        }
    }

    public function actionGetCategory() {
        if (Yii::$app->request->isAjax && ($data = Yii::$app->request->post())) {
            $query = Category::find()->with('image')->where(['id'=>$data['id']])->one();

            $category['id'] = $query->id;
            $category['name_ru'] = $query->name_ru;
            $category['name_uz'] = $query->name_uz;
            $category['name_en'] = $query->name_en;
            $category['description_ru'] = $query->description_ru;
            $category['description_uz'] = $query->description_uz;
            $category['description_en'] = $query->description_en;
            $category['photo'] = $query->photo;
            $category['price'] = $query->price;

            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['category'=>$category];
        }
    }

    public function actionSubCategory() {
        if (Yii::$app->request->isAjax && ($data = Yii::$app->request->post())) {
            $categories = $data['id'] ? Category::find()->where(['parent_id'=>$data['id']])->all() : null;

            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['categories'=>$categories];
        }
    }
}