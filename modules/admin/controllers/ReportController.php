<?php
namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;

use app\models\user\User;
use app\models\Category;
use app\models\product\Product;
use app\models\product\ProductSearch;
use app\models\shop\ShopProduct;
use app\models\shop\ShopProductSearch;
use app\models\gp\Gp;
use app\models\gp\GpSearch;

class ReportController extends Controller{
	public $user;

    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/admin']);
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

            if (!in_array('report', $accesses)) {
                throw new HttpException(403, 'В доступе отказано');
            }
        }

        return parent::beforeAction($action);
    }

    public function actionIndex() {
        Yii::$app->session->setFlash('report_removed', 'На стадии разработки');
        return $this->render('index');
    }

    public function actionDetailIncome($type = null) {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $dataProvider->query->with('image')->andWhere(['status'=>1]);

        $dataProvider->setSort([
            'defaultOrder' => [
                'id' => 'desc'
            ]
        ]);

        $today = Product::find()->where('date >= CURDATE()')->andWhere(['status'=>1])->all();

        $week = Product::find()->where('date >= DATE_SUB(CURRENT_DATE, INTERVAL 7 DAY)')->andWhere(['status'=>1])->all();
        $month = Product::find()->where('date >= DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH)')->andWhere(['status'=>1])->all();
        $tmonth = Product::find()->where('date >= DATE_SUB(CURRENT_DATE, INTERVAL 3 MONTH)')->andWhere(['status'=>1])->all();
        $hyear = Product::find()->where('date >= DATE_SUB(CURRENT_DATE, INTERVAL 6 MONTH)')->andWhere(['status'=>1])->all();
        $year = Product::find()->where('date >= DATE_SUB(CURRENT_DATE, INTERVAL 12 MONTH)')->andWhere(['status'=>1])->all();

        if (!$type) {
            $products = Product::find()->where(['status'=>1])->all();
        }

        $data = [];

        switch ($type) {
            case 'month' : $products = $month; break;
            case 'tmonth' : $products = $tmonth; break;
            case 'hyear' : $products = $hyear; break;
            case 'year' : $products = $year; break;
            case 'week' : $products = $week; break;
        }

        $product_ids = [];
        foreach ($products as $pr) {
            $product_ids[] = $pr->id;
        }

        $dataProvider->query->with('image')->andWhere(['status'=>1])->andWhere(['in', 'id', $product_ids]);

        foreach ($products as $k => $v) {
            $date = explode(' ', $v->date);
            @$data[$date[0]]['amount'] += 1; 
            @$data[$date[0]]['sum'] += 5;
        }

        $data_c = [];

        foreach ($products as $product) {
            $date = date('m-Y', strtotime($product->date));
            $data_c[$date][] = $product;
        }
        
        $data_product = [];

        $monthes = [
            '01' => 'Январь',
            '02' => 'Февраль',
            '03' => 'Март',
            '04' => 'Апрель',
            '05' => 'Март',
            '06' => 'Июнь',
            '07' => 'Июль',
            '08' => 'Август',
            '09' => 'Сентябрь',
            '10' => 'Октябрь',
            '11' => 'Ноябрь',
            '12' => 'Декабрь',
        ];

        if ($data_c) {
            foreach ($data_c as $key_c => $value_c) {
                $d = explode('-', $key_c);
                $t = explode('-', $d);
                if ($year = Yii::$app->request->get('year')) {
                    if ($d[1] == $year) {
                        $data_product[$d[0]]['count'] = count($value_c);
                        $data_product[$d[0]]['month'] = $monthes[$d[0]];
                    }
                } else {
                    $data_product[$d[0]]['count'] = count($value_c);
                    $data_product[$d[0]]['month'] = $monthes[$d[0]];
                }
            }
        }

        $data_products = [];

        foreach ($monthes as $k => $month) {
            if (array_key_exists($k, $data_product)) {
                $data_products[$k] = $data_product[$k]['count'];
            } else {
                $data_products[$k] = 0;
            }
        }

        return $this->render('detail-income', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'data_product' => $data_product,
            'data_products' => $data_products,
            'today' => $today,
            'week' => $week,
            'month' => $month,
            'hyear' => $hyear,
            'data' => $data,
            'type' => $type,
        ]);
    }

    public function actionDetailDefect($type = null) {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $dataProvider->query->with('image')->andWhere(['status'=>2]);

        $dataProvider->setSort([
            'defaultOrder' => [
                'id' => 'desc'
            ]
        ]);

        $today = Product::find()->where('date >= CURDATE()')->andWhere(['status'=>2])->all();

        $week = Product::find()->where('date >= DATE_SUB(CURRENT_DATE, INTERVAL 7 DAY)')->andWhere(['status'=>2])->all();
        $month = Product::find()->where('date >= DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH)')->andWhere(['status'=>2])->all();
        $tmonth = Product::find()->where('date >= DATE_SUB(CURRENT_DATE, INTERVAL 3 MONTH)')->andWhere(['status'=>2])->all();
        $hyear = Product::find()->where('date >= DATE_SUB(CURRENT_DATE, INTERVAL 6 MONTH)')->andWhere(['status'=>2])->all();
        $year = Product::find()->where('date >= DATE_SUB(CURRENT_DATE, INTERVAL 12 MONTH)')->andWhere(['status'=>2])->all();

        if (!$type) {
            $products = Product::find()->where(['status'=>2])->all();
        }

        $data = [];

        switch ($type) {
            case 'month' : $products = $month; break;
            case 'tmonth' : $products = $tmonth; break;
            case 'hyear' : $products = $hyear; break;
            case 'year' : $products = $year; break;
            case 'week' : $products = $week; break;
        }

        $product_ids = [];
        foreach ($products as $pr) {
            $product_ids[] = $pr->id;
        }

        $dataProvider->query->with('image')->andWhere(['status'=>2])->andWhere(['in', 'id', $product_ids]);

        foreach ($products as $k => $v) {
            $date = explode(' ', $v->date);
            @$data[$date[0]]['amount'] += 1; 
            @$data[$date[0]]['sum'] += 5;
        }

        $data_c = [];

        foreach ($products as $product) {
            $date = date('m-Y', strtotime($product->date));
            $data_c[$date][] = $product;
        }

        $data_product = [];

        $monthes = [
            '01' => 'Январь',
            '02' => 'Февраль',
            '03' => 'Март',
            '04' => 'Апрель',
            '05' => 'Март',
            '06' => 'Июнь',
            '07' => 'Июль',
            '08' => 'Август',
            '09' => 'Сентябрь',
            '10' => 'Октябрь',
            '11' => 'Ноябрь',
            '12' => 'Декабрь',
        ];

        if ($data_c) {
            foreach ($data_c as $key_c => $value_c) {
                $d = explode('-', $key_c);
                $t = explode('-', $d);
                if ($year = Yii::$app->request->get('year')) {
                    if ($d[1] == $year) {
                        $data_product[$d[0]]['count'] = count($value_c);
                        $data_product[$d[0]]['month'] = $monthes[$d[0]];
                    }
                } else {
                    $data_product[$d[0]]['count'] = count($value_c);
                    $data_product[$d[0]]['month'] = $monthes[$d[0]];
                }
            }
        }

        $data_products = [];

        foreach ($monthes as $k => $month) {
            if (array_key_exists($k, $data_product)) {
                $data_products[$k] = $data_product[$k]['count'];
            } else {
                $data_products[$k] = 0;
            }
        }

        return $this->render('detail-defect', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'data_product' => $data_product,
            'data_products' => $data_products,
            'today' => $today,
            'week' => $week,
            'month' => $month,
            'hyear' => $hyear,
            'data' => $data,
            'type' => $type,
        ]);
    }

    public function actionDetailShop($type = null) {
        $searchModel = new ShopProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $dataProvider->setSort([
            'defaultOrder' => [
                'id' => 'desc'
            ]
        ]);

        $today = ShopProduct::find()->where('date >= CURDATE()')->andWhere(['status'=>1])->all();

        $week = ShopProduct::find()->where('date >= DATE_SUB(CURRENT_DATE, INTERVAL 7 DAY)')->andWhere(['status'=>1])->all();
        $month = ShopProduct::find()->where('date >= DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH)')->andWhere(['status'=>1])->all();
        $tmonth = ShopProduct::find()->where('date >= DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH)')->andWhere(['status'=>1])->all();
        $hyear = ShopProduct::find()->where('date >= DATE_SUB(CURRENT_DATE, INTERVAL 6 MONTH)')->andWhere(['status'=>1])->all();
        $year = ShopProduct::find()->where('date >= DATE_SUB(CURRENT_DATE, INTERVAL 12 MONTH)')->andWhere(['status'=>1])->all();

        if (!$type) {
            $products = ShopProduct::find()->all();
        }

        $data = [];

        switch ($type) {
            case 'month' : $products = $month; break;
            case 'tmonth' : $products = $tmonth; break;
            case 'hyear' : $products = $hyear; break;
            case 'year' : $products = $year; break;
            case 'week' : $products = $week; break;
        }
        
        $product_ids = [];
        foreach ($products as $pr) {
            $product_ids[] = $pr->id;
        }

        $dataProvider->query->andWhere(['status'=>1])->andWhere(['in', 'id', $product_ids]);

        foreach ($products as $k => $v) {
            $date = explode(' ', $v->date);
            @$data[$date[0]]['amount'] += 1; 
            @$data[$date[0]]['sum'] += 5;
        }

        $data_c = [];

        foreach ($products as $product) {
            $date = date('m-Y', strtotime($product->date));
            $data_c[$date][] = $product;
        }

        $data_product = [];

        $monthes = [
            '01' => 'Январь',
            '02' => 'Февраль',
            '03' => 'Март',
            '04' => 'Апрель',
            '05' => 'Март',
            '06' => 'Июнь',
            '07' => 'Июль',
            '08' => 'Август',
            '09' => 'Сентябрь',
            '10' => 'Октябрь',
            '11' => 'Ноябрь',
            '12' => 'Декабрь',
        ];

        if ($data_c) {
            foreach ($data_c as $key_c => $value_c) {
                $d = explode('-', $key_c);
                $t = explode('-', $d);
                if ($year = Yii::$app->request->get('year')) {
                    if ($d[1] == $year) {
                        $data_product[$d[0]]['count'] = count($value_c);
                        $data_product[$d[0]]['month'] = $monthes[$d[0]];
                    }
                } else {
                    $data_product[$d[0]]['count'] = count($value_c);
                    $data_product[$d[0]]['month'] = $monthes[$d[0]];
                }
            }
        }

        $data_products = [];

        foreach ($monthes as $k => $month) {
            if (array_key_exists($k, $data_product)) {
                $data_products[$k] = $data_product[$k]['count'];
            } else {
                $data_products[$k] = 0;
            }
        }

        return $this->render('detail-shop', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'data_product' => $data_product,
            'data_products' => $data_products,
            'today' => $today,
            'week' => $week,
            'month' => $month,
            'hyear' => $hyear,
            'data' => $data,
            'type' => $type,
        ]);
    }

    public function actionDetailLine($type = null) {
        $searchModel = new ShopProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $dataProvider->setSort([
            'defaultOrder' => [
                'id' => 'desc'
            ]
        ]);

        $today = ShopProduct::find()->where('date >= CURDATE()')->andWhere(['status'=>1])->all();

        $week = ShopProduct::find()->where('date >= DATE_SUB(CURRENT_DATE, INTERVAL 7 DAY)')->andWhere(['status'=>1])->all();
        $month = ShopProduct::find()->where('date >= DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH)')->andWhere(['status'=>1])->all();
        $tmonth = ShopProduct::find()->where('date >= DATE_SUB(CURRENT_DATE, INTERVAL 3 MONTH)')->andWhere(['status'=>1])->all();
        $hyear = ShopProduct::find()->where('date >= DATE_SUB(CURRENT_DATE, INTERVAL 6 MONTH)')->andWhere(['status'=>1])->all();
        $year = ShopProduct::find()->where('date >= DATE_SUB(CURRENT_DATE, INTERVAL 12 MONTH)')->andWhere(['status'=>1])->all();

        if (!$type) {
            $products = ShopProduct::find()->all();
        }

        $data = [];

        switch ($type) {
            case 'month' : $products = $month; break;
            case 'tmonth' : $products = $tmonth; break;
            case 'hyear' : $products = $hyear; break;
            case 'year' : $products = $year; break;
            case 'week' : $products = $week; break;
        }

        $product_ids = [];
        foreach ($products as $pr) {
            $product_ids[] = $pr->id;
        }

        $dataProvider->query->andWhere(['status'=>1])->andWhere(['in', 'id', $product_ids]);

        foreach ($products as $k => $v) {
            $date = explode(' ', $v->date);
            @$data[$date[0]]['amount'] += 1; 
            @$data[$date[0]]['sum'] += 5;
        }

        $data_c = [];

        foreach ($products as $product) {
            $date = date('m-Y', strtotime($product->date));
            $data_c[$date][] = $product;
        }

        $data_product = [];

        $monthes = [
            '01' => 'Январь',
            '02' => 'Февраль',
            '03' => 'Март',
            '04' => 'Апрель',
            '05' => 'Март',
            '06' => 'Июнь',
            '07' => 'Июль',
            '08' => 'Август',
            '09' => 'Сентябрь',
            '10' => 'Октябрь',
            '11' => 'Ноябрь',
            '12' => 'Декабрь',
        ];

        if ($data_c) {
            foreach ($data_c as $key_c => $value_c) {
                $d = explode('-', $key_c);
                $t = explode('-', $d);
                if ($year = Yii::$app->request->get('year')) {
                    if ($d[1] == $year) {
                        $data_product[$d[0]]['count'] = count($value_c);
                        $data_product[$d[0]]['month'] = $monthes[$d[0]];
                    }
                } else {
                    $data_product[$d[0]]['count'] = count($value_c);
                    $data_product[$d[0]]['month'] = $monthes[$d[0]];
                }
            }
        }

        $data_products = [];

        foreach ($monthes as $k => $month) {
            if (array_key_exists($k, $data_product)) {
                $data_products[$k] = $data_product[$k]['count'];
            } else {
                $data_products[$k] = 0;
            }
        }

        return $this->render('detail-line', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'data_product' => $data_product,
            'data_products' => $data_products,
            'today' => $today,
            'week' => $week,
            'month' => $month,
            'hyear' => $hyear,
            'data' => $data,
            'type' => $type,
        ]);
    }

    public function actionDetailGpGet($type = null) {
        $searchModel = new GpSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $dataProvider->setSort([
            'defaultOrder' => [
                'id' => 'desc'
            ]
        ]);

        $today = Gp::find()->where('date >= CURDATE()')->andWhere(['status'=>1])->all();

        $week = Gp::find()->where('date >= DATE_SUB(CURRENT_DATE, INTERVAL 7 DAY)')->andWhere(['status'=>1])->all();
        $month = Gp::find()->where('date >= DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH)')->andWhere(['status'=>1])->all();
        $tmonth = Gp::find()->where('date >= DATE_SUB(CURRENT_DATE, INTERVAL 3 MONTH)')->andWhere(['status'=>1])->all();
        $hyear = Gp::find()->where('date >= DATE_SUB(CURRENT_DATE, INTERVAL 6 MONTH)')->andWhere(['status'=>1])->all();
        $year = Gp::find()->where('date >= DATE_SUB(CURRENT_DATE, INTERVAL 12 MONTH)')->andWhere(['status'=>1])->all();

        if (!$type) {
            $products = Gp::find()->all();
        }

        $data = [];

        switch ($type) {
            case 'month' : $products = $month; break;
            case 'tmonth' : $products = $tmonth; break;
            case 'hyear' : $products = $hyear; break;
            case 'year' : $products = $year; break;
            case 'week' : $products = $week; break;
        }

        $product_ids = [];
        foreach ($products as $pr) {
            $product_ids[] = $pr->id;
        }

        $dataProvider->query->andWhere(['in', 'id', $product_ids]);

        foreach ($products as $k => $v) {
            $date = explode(' ', $v->date);
            @$data[$date[0]]['amount'] += 1; 
            @$data[$date[0]]['sum'] += 5;
        }

        $data_c = [];

        foreach ($products as $product) {
            $date = date('m-Y', strtotime($product->date));
            $data_c[$date][] = $product;
        }

        $data_product = [];

        $monthes = [
            '01' => 'Январь',
            '02' => 'Февраль',
            '03' => 'Март',
            '04' => 'Апрель',
            '05' => 'Март',
            '06' => 'Июнь',
            '07' => 'Июль',
            '08' => 'Август',
            '09' => 'Сентябрь',
            '10' => 'Октябрь',
            '11' => 'Ноябрь',
            '12' => 'Декабрь',
        ];

        if ($data_c) {
            foreach ($data_c as $key_c => $value_c) {
                $d = explode('-', $key_c);
                $t = explode('-', $d);
                if ($year = Yii::$app->request->get('year')) {
                    if ($d[1] == $year) {
                        $data_product[$d[0]]['count'] = count($value_c);
                        $data_product[$d[0]]['month'] = $monthes[$d[0]];
                    }
                } else {
                    $data_product[$d[0]]['count'] = count($value_c);
                    $data_product[$d[0]]['month'] = $monthes[$d[0]];
                }
            }
        }

        $data_products = [];

        foreach ($monthes as $k => $month) {
            if (array_key_exists($k, $data_product)) {
                $data_products[$k] = $data_product[$k]['count'];
            } else {
                $data_products[$k] = 0;
            }
        }

        return $this->render('detail-gp-get', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'data_product' => $data_product,
            'data_products' => $data_products,
            'today' => $today,
            'week' => $week,
            'month' => $month,
            'hyear' => $hyear,
            'data' => $data,
            'type' => $type,
        ]);
    }

    public function actionDetailGpSend($type = null) {
        $searchModel = new GpSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $dataProvider->setSort([
            'defaultOrder' => [
                'id' => 'desc'
            ]
        ]);

        $today = Gp::find()->where('date >= CURDATE()')->all();

        $week = Gp::find()->where('date >= DATE_SUB(CURRENT_DATE, INTERVAL 7 DAY)')->all();
        $month = Gp::find()->where('date >= DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH)')->all();
        $tmonth = Gp::find()->where('date >= DATE_SUB(CURRENT_DATE, INTERVAL 3 MONTH)')->all();
        $hyear = Gp::find()->where('date >= DATE_SUB(CURRENT_DATE, INTERVAL 6 MONTH)')->all();
        $year = Gp::find()->where('date >= DATE_SUB(CURRENT_DATE, INTERVAL 12 MONTH)')->all();

        if (!$type) {
            $products = Gp::find()->all();
        }

        $data = [];

        switch ($type) {
            case 'month' : $products = $month; break;
            case 'tmonth' : $products = $tmonth; break;
            case 'hyear' : $products = $hyear; break;
            case 'year' : $products = $year; break;
            case 'week' : $products = $week; break;
        }

        $product_ids = [];
        foreach ($products as $pr) {
            $product_ids[] = $pr->id;
        }

        $dataProvider->query->andWhere(['in', 'id', $product_ids]);

        foreach ($products as $k => $v) {
            $date = explode(' ', $v->date);
            @$data[$date[0]]['amount'] += 1; 
            @$data[$date[0]]['sum'] += 5;
        }

        $data_c = [];

        foreach ($products as $product) {
            $date = date('m-Y', strtotime($product->date));
            $data_c[$date][] = $product;
        }

        $data_product = [];

        $monthes = [
            '01' => 'Январь',
            '02' => 'Февраль',
            '03' => 'Март',
            '04' => 'Апрель',
            '05' => 'Март',
            '06' => 'Июнь',
            '07' => 'Июль',
            '08' => 'Август',
            '09' => 'Сентябрь',
            '10' => 'Октябрь',
            '11' => 'Ноябрь',
            '12' => 'Декабрь',
        ];

        if ($data_c) {
            foreach ($data_c as $key_c => $value_c) {
                $d = explode('-', $key_c);
                $t = explode('-', $d);
                if ($year = Yii::$app->request->get('year')) {
                    if ($d[1] == $year) {
                        $data_product[$d[0]]['count'] = count($value_c);
                        $data_product[$d[0]]['month'] = $monthes[$d[0]];
                    }
                } else {
                    $data_product[$d[0]]['count'] = count($value_c);
                    $data_product[$d[0]]['month'] = $monthes[$d[0]];
                }
            }
        }

        $data_products = [];

        foreach ($monthes as $k => $month) {
            if (array_key_exists($k, $data_product)) {
                $data_products[$k] = $data_product[$k]['count'];
            } else {
                $data_products[$k] = 0;
            }
        }

        return $this->render('detail-gp-send', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'data_product' => $data_product,
            'data_products' => $data_products,
            'today' => $today,
            'week' => $week,
            'month' => $month,
            'hyear' => $hyear,
            'data' => $data,
            'type' => $type,
        ]);
    }
}