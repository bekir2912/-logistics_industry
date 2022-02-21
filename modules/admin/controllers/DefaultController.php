<?php
namespace app\modules\admin\controllers;

use app\models\gp\Gp;
use app\models\industry\AllDeffect;
use app\models\industry\BufferZone;
use app\models\industry\DepartmentChecking;
use app\models\industry\DepartmentElectro;
use app\models\industry\DepartmentForming;
use app\models\industry\DepartmentGp;
use app\models\industry\DepartmentMechanical;
use app\models\industry\DepartmentPaiting;
use app\models\industry\DepartmentPlastic;
use app\models\industry\DepartmentPrinting;
use app\models\industry\DepartmentRegulator;
use app\models\industry\DepartmentSizing;
use app\models\industry\DepartmentStamping;
use app\models\industry\DepartmentTest;
use app\models\industry\handbook\BDepartment;
use app\modules\admin\controllers\industry\PlansDatesController;
use DateInterval;
use DateTime;
use Yii;
use yii\base\BaseObject;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;

use app\models\Images;
use app\models\File;
use app\models\Category;
use app\models\user\User;
use app\models\user\UserSearch;

use app\models\stock\Stock;
use app\models\product\Product;
use app\models\shipment\Shipment;
use app\models\Regions;

use moonland\phpexcel\Excel;

class DefaultController extends Controller{
    public $user;

    public function beforeAction($action) {
        if ($action->id == 'upload') {
            $this->enableCsrfValidation = false;
        }
        if (!Yii::$app->user->isGuest) {
            $this->user = Yii::$app->user->identity;

            if ($this->user->role == User::ROLE_ADMIN && $action->id == 'index') {
                return $this->redirect(['/admin/default/dashboard']);
            }
            if ($this->user->role == User::ROLE_WORKER && $action->id == 'index') {
                return $this->redirect(['/worker/default/dashboard']);
            }
            if ($this->user->role == User::ROLE_WORKER_SHOP && $action->id == 'index') {
                return $this->redirect(['/worker_shop/default/dashboard']);
            }

        }

        return parent::beforeAction($action);
    }

    public function actionIndex() {
        $model = new User;





        $model->scenario = User::SIGNIN_ADMIN;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->login() && ($user = $model->findByUsername($model->login))) {
                if ($user->role == User::ROLE_ADMIN) {
                    return $this->redirect("/admin/default/dashboard");
                }
                if ($user->role == User::ROLE_WORKER) {
                    return $this->redirect("/worker/default/dashboard");
                }
                if ($user->role == User::ROLE_WORKER_SHOP) {
                    return $this->redirect("/worker_shop/default/dashboard");
                }

                if ($user->role == User::ROLE_MODERATOR) {
                    return $this->redirect("/admin/default/dashboard");
                }

            }
        }

        return $this->render('index', [
            'model'=>$model,

        ]);
    }



    public function actionProfile() {
        return $this->render('profile', [
            'model'=>$this->user
        ]);
    }

    public function actionRemovePhoto($id) {
        $model = Images::findOne($id);

        if ($model && $model->removeImageSize()) {
            Yii::$app->session->setFlash('photo_removed', 'Фото успешно удалено');
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionRemoveFile($id) {
        $model = File::findOne($id);

        if ($model && $model->remove()) {
            Yii::$app->session->setFlash('file_removed', 'Файл успешно удален');
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionChangePassword() {
    	$model = User::findOne($this->user->id);

        $model->scenario = User::SCENARIO_CHANGE_PASSWORD;

        if($model->load(Yii::$app->request->post())){
            if($model->changePassword()){
                Yii::$app->session->setFlash('password_changed', 'Пароль успешно изменен');
            }
            return $this->redirect(Yii::$app->request->referrer);
        }

    	return $this->render('change-password', [
    		'model'=>$model,
    	]);
    }

    public function actionDashboard($type = null) {
        $start = Yii::$app->request->get('start');
        $end = Yii::$app->request->get('end');

        if($end < $start) {
            $end = $start;
        }

        if($start && $end) {
            $start_day = date('Y-m-d', strtotime($end . "-1 days"));
            $end_next_day = date('Y-m-d', strtotime($end . "+1 days"));

            $start = $start_day . ' 08:00:00';
            $end = $end_next_day . ' 08:00:00';
        }
        else {
            $d = new DateTime('first day of this month');
            $month = $d->format('Y-m-d');
            $start = $month .' 08:00:00';


            $now_date = strftime("%Y-%m-%d", time());
            $tomorrow_date = date('Y-m-d',strtotime($now_date . "+1 days"));
            $end = $tomorrow_date .' 08:00:00';


        }


        $date = DateTime::createFromFormat('m/d/Y', '10/27/2014');
        $date->setTime(0,0,0);



        $shipments = Shipment::find()->where(['status'=>0])->count();
        $products = BufferZone::find()->where(['status' => 1])->sum ('amount');
        $stocks = Stock::find()->count();

        $gps_list = '';
        $deffects_list = '';
        if($start && $end){
            $deffects_list = AllDeffect::find()->andFilterWhere(
                ['between', 'dates', $start, $end  ])
                ->orderBy('`id` DESC')->limit(8)->all();

            $gps_list = DepartmentGp::find()->where(['between', 'dates', $start, $end ])
                ->orderBy('`id` DESC')->limit(8)->all();

        }else{
            $deffects_list = AllDeffect::find()->orderBy('`id` DESC')->limit(8)->all();
            $gps_list = DepartmentGp::find()->orderBy('`id` DESC')->limit(8)->all();
        }

        $gps = DepartmentGp::find()->where(['status'=>1])->sum('amount');
        $defects = AllDeffect::find()->where(['status'=>1])->sum('count_deffect');
//        $buffers = Bufferzone::find()->count();
        $departments = BDepartment::find()->count();

        $connection = Yii::$app->getDb();




            //Штамповка
        //---ГП
            $stamping_gp = (int)DepartmentStamping::find()->where(['status' => 1])->andWhere(['not', ['dates' => null]])->andWhere(['between', 'dates', $start, $end ])->sum('amount');
        //---Дефект
            $stamping_defect = (int)AllDeffect::find()->where(['status' => 1])->andWhere(['not', ['dates' => null]])->andWhere(['department_id' => 1])->andWhere(['between', 'dates', $start, $end ])->sum('count_deffect');

            $command = $connection->createCommand("SELECT department_id as 'department_id', sum(value) as 'amount'
                                                    FROM b_plans_dates 
                                                    Where status = 1 AND date IS NOT NULL
                                                    AND department_id = 1 AND date BETWEEN  '$start' AND '$end'
                                                    GROUP BY department_id");
            $stamping_plans = $command->queryAll();

            $stamping_gp_percent = $stamping_gp / $stamping_plans[0]['amount'] * 100;
            $stamping_defect_percent = (int)$stamping_defect / (int)$stamping_gp * 100;

        //Покраска
            //---ГП
            $paiting_gp = (int)DepartmentPaiting::find()->where(['status' => 1])->andWhere(['not', ['dates' => null]])->andWhere(['between', 'dates', $start, $end ])->sum('amount');

            //---Дефект
            $paiting_defect = (int)AllDeffect::find()->where(['status' => 1])->andWhere(['not', ['dates' => null]])->andWhere(['department_id' => 2])->andWhere(['between', 'dates', $start, $end ])->sum('count_deffect');

            $command2 = $connection->createCommand("SELECT department_id as 'department_id', sum(value) as 'amount'
                                                        FROM b_plans_dates 
                                                        Where status = 1 AND date IS NOT NULL
                                                        AND department_id = 2 AND date BETWEEN  '$start' AND '$end'
                                                        GROUP BY department_id");
            $paiting_plans = $command2->queryAll();

            $paiting_gp_percent = $paiting_gp / $paiting_plans[0]['amount'] * 100;
            $paiting_defect_percent = $paiting_defect / $paiting_gp * 100;

        //Механическая сборка
            //---ГП
            $mechanical_gp = (int)DepartmentMechanical::find()->where(['status' => 1])->andWhere(['not', ['dates' => null]])->andWhere(['between', 'dates', $start, $end ])->sum('amount');

            //---Дефект
            $mechanical_defect = (int)AllDeffect::find()->where(['status' => 1])->andWhere(['not', ['dates' => null]])->andWhere(['department_id' => 3])->andWhere(['between', 'dates', $start, $end ])->sum('count_deffect');


            $command3 = $connection->createCommand("SELECT department_id as 'department_id', sum(value) as 'amount'
                                                            FROM b_plans_dates 
                                                            Where status = 1 AND date IS NOT NULL
                                                            AND department_id = 3 AND date BETWEEN  '$start' AND '$end'
                                                            GROUP BY department_id");
            $mechanical_plans = $command3->queryAll();

            $mechanical_gp_percent = $mechanical_gp / $mechanical_plans[0]['amount'] * 100;
            $mechanical_defect_percent = $mechanical_defect / $mechanical_gp * 100;

        //Тест на утечку
            //---ГП
            $test_gp = (int)DepartmentTest::find()->where(['status' => 1])->andWhere(['not', ['dates' => null]])->andWhere(['between', 'dates', $start, $end ])->sum('amount');

            //---Дефект
            $test_defect = (int)AllDeffect::find()->where(['status' => 1])->andWhere(['not', ['dates' => null]])->andWhere(['department_id' => 4])->andWhere(['between', 'dates', $start, $end ])->sum('count_deffect');


            $command4  = $connection->createCommand("SELECT department_id as 'department_id', sum(value) as 'amount'
                                                                FROM b_plans_dates 
                                                                Where status = 1 AND date IS NOT NULL
                                                                AND department_id = 4 AND date BETWEEN  '$start' AND '$end'
                                                                GROUP BY department_id");
            $test_plans = $command4->queryAll();

            $test_gp_percent = $test_gp / $test_plans[0]['amount'] * 100;
            $test_defect_percent = $test_defect / $test_gp * 100;


        //Калибровка
            //---ГП
            $sizing_gp = (int)DepartmentSizing::find()->where(['status' => 1])->andWhere(['not', ['dates' => null]])->andWhere(['between', 'dates', $start, $end ])->sum('amount');

            //---Дефект
            $sizing_defect = (int)AllDeffect::find()->where(['status' => 1])->andWhere(['not', ['dates' => null]])->andWhere(['department_id' => 5])->andWhere(['between', 'dates', $start, $end ])->sum('count_deffect');

            $command5  = $connection->createCommand("SELECT department_id as 'department_id', sum(value) as 'amount'
                                                                    FROM b_plans_dates 
                                                                    Where status = 1 AND date IS NOT NULL
                                                                    AND department_id = 5 AND date BETWEEN  '$start' AND '$end'
                                                                    GROUP BY department_id");
            $sizing_plans = $command5->queryAll();

            $sizing_gp_percent = $sizing_gp / $sizing_plans[0]['amount'] * 100;
            $sizing_defect_percent = $sizing_defect / $sizing_gp * 100;

        //Электро-сборка
            //---ГП
            $electro_gp = (int)DepartmentElectro::find()->where(['status' => 1])->andWhere(['not', ['dates' => null]])->andWhere(['between', 'dates', $start, $end ])->sum('amount');

            //---Дефект
            $electro_defect = (int)AllDeffect::find()->where(['status' => 1])->andWhere(['not', ['dates' => null]])->andWhere(['department_id' => 6])->andWhere(['between', 'dates', $start, $end ])->sum('count_deffect');

            $command6  = $connection->createCommand("SELECT department_id as 'department_id', sum(value) as 'amount'
                                                                        FROM b_plans_dates 
                                                                        Where status = 1 AND date IS NOT NULL
                                                                        AND department_id = 6 AND date BETWEEN  '$start' AND '$end'
                                                                        GROUP BY department_id");
            $electro_plans = $command6->queryAll();

            $electro_gp_percent = $electro_gp / $electro_plans[0]['amount'] * 100;
            $electro_defect_percent = $electro_defect / $electro_gp * 100;

        //ГП
            //---ГП
            $gp_gp = (int)DepartmentGp::find()->where(['status' => 1])->andWhere(['not', ['dates' => null]])->andWhere(['between', 'dates', $start, $end ])->sum('amount');

            //---Дефект
            $gp_defect = (int)AllDeffect::find()->where(['status' => 1])->andWhere(['not', ['dates' => null]])->andWhere(['department_id' => 7])->andWhere(['between', 'dates', $start, $end ])->sum('count_deffect');

            $command7  = $connection->createCommand("SELECT department_id as 'department_id', sum(value) as 'amount'
                                                                            FROM b_plans_dates 
                                                                            Where status = 1 AND date IS NOT NULL
                                                                            AND department_id = 7 AND date BETWEEN  '$start' AND '$end'
                                                                            GROUP BY department_id");
            $gp_plans = $command7->queryAll();

            $gp_gp_percent = $gp_gp / $gp_plans[0]['amount'] * 100;
            $gp_defect_percent = $gp_defect / $gp_gp * 100;

        //Отливка пластиковых деталей
            //---ГП
            $plastic_gp = (int)DepartmentPlastic::find()->where(['in', 'status', [1,3]])->andWhere(['not', ['dates' => null]])->andWhere(['between', 'dates', $start, $end ])->sum('amount');

            //---Дефект
            $plastic_defect = (int)AllDeffect::find()->where(['status' => 1])->andWhere(['not', ['dates' => null]])->andWhere(['department_id' => 8])->andWhere(['between', 'dates', $start, $end ])->sum('count_deffect');

            $command8  = $connection->createCommand("SELECT department_id as 'department_id', sum(value) as 'amount'
                                                                                FROM b_plans_dates 
                                                                                Where status = 1 AND date IS NOT NULL
                                                                                AND department_id = 8 AND date BETWEEN  '$start' AND '$end'
                                                                                GROUP BY department_id");
            $plastic_plans = $command8->queryAll();

            $plastic_gp_percent = $plastic_gp / $plastic_plans[0]['amount'] * 100;
            $plastic_defect_percent = $plastic_defect / $plastic_gp * 100;

        //Сборка газового регулятор
            //---ГП
            $regulator_gp = (int)DepartmentRegulator::find()->where(['in', 'status', [1,3]])->andWhere(['not', ['dates' => null]])->andWhere(['between', 'dates', $start, $end ])->sum('amount');

            //---Дефект
            $regulator_defect = (int)AllDeffect::find()->where(['status' => 1])->andWhere(['not', ['dates' => null]])->andWhere(['department_id' => 9])->andWhere(['between', 'dates', $start, $end ])->sum('count_deffect');

            $command9  = $connection->createCommand("SELECT department_id as 'department_id', sum(value) as 'amount'
                                                                                    FROM b_plans_dates 
                                                                                    Where status = 1 AND date IS NOT NULL
                                                                                    AND department_id = 9 AND date BETWEEN  '$start' AND '$end'
                                                                                    GROUP BY department_id");
            $regulator_plans = $command9->queryAll();

            $regulator_gp_percent = $regulator_gp / $regulator_plans[0]['amount'] * 100;
            $regulator_defect_percent = $regulator_defect / $regulator_gp * 100;

        //Печать надписей на переднюю панель
            //---ГП
            $printing_gp = (int)DepartmentPrinting::find()->where(['status' => 1])->andWhere(['not', ['dates' => null]])->andWhere(['between', 'dates', $start, $end ])->sum('amount');

            //---Дефект
            $printing_defect = (int)AllDeffect::find()->where(['status' => 1])->andWhere(['not', ['dates' => null]])->andWhere(['department_id' => 10])->andWhere(['between', 'dates', $start, $end ])->sum('count_deffect');

            $command10  = $connection->createCommand("SELECT department_id as 'department_id', sum(value) as 'amount'
                                                                                        FROM b_plans_dates 
                                                                                        Where status = 1 AND date IS NOT NULL
                                                                                        AND department_id = 10 AND date BETWEEN  '$start' AND '$end'
                                                                                        GROUP BY department_id");
            $printing_plans = $command10->queryAll();

            $printing_gp_percent = $printing_gp / $printing_plans[0]['amount'] * 100;
            $printing_defect_percent = $printing_defect / $printing_gp * 100;

        //Формовка AUQ-G6
            //---ГП
            $forming_gp = (int)DepartmentForming::find()->where(['in', 'status', [1,3]])->andWhere(['not', ['dates' => null]])->andWhere(['between', 'dates', $start, $end ])->sum('amount');

            //---Дефект
            $forming_defect = (int)AllDeffect::find()->where(['status' => 1])->andWhere(['not', ['dates' => null]])->andWhere(['department_id' => 11])->andWhere(['between', 'dates', $start, $end ])->sum('count_deffect');

            $command11  = $connection->createCommand("SELECT department_id as 'department_id', sum(value) as 'amount'
                                                                                            FROM b_plans_dates 
                                                                                            Where status = 1 AND date IS NOT NULL
                                                                                            AND department_id = 11 AND date BETWEEN  '$start' AND '$end'
                                                                                            GROUP BY department_id");
            $forming_plans = $command11->queryAll();

            $forming_gp_percent = $forming_gp / $forming_plans[0]['amount'] * 100;
            $forming_defect_percent = $forming_defect / $forming_gp * 100;

        //Проверка узлов на утечку
            //---ГП
            $checking_gp = (int)DepartmentChecking::find()->where(['status' => 1])->andWhere(['not', ['dates' => null]])->andWhere(['between', 'dates', $start, $end ])->sum('amount');

            //---Дефект
            $checking_defect = (int)AllDeffect::find()->where(['status' => 1])->andWhere(['not', ['dates' => null]])->andWhere(['department_id' => 12])->andWhere(['between', 'dates', $start, $end ])->sum('count_deffect');


            $command12  = $connection->createCommand("SELECT department_id as 'department_id', sum(value) as 'amount'
                                                                                                FROM b_plans_dates 
                                                                                                Where status = 1 AND date IS NOT NULL
                                                                                                AND department_id = 12 AND date BETWEEN  '$start' AND '$end'
                                                                                                GROUP BY department_id");
            $checking_plans = $command12->queryAll();

            $checking_gp_percent = $checking_gp / $checking_plans[0]['amount'] * 100;
            $checking_defect_percent = $checking_defect / $checking_gp * 100;




        return $this->render('dashboard', [
            'gps' => $gps,
            'gps_list' => $gps_list,
            'deffects_list' => $deffects_list,
            'defects' => $defects,
            'departments' => $departments,
            'shipments' => $shipments,
            'products' => $products,
            'stocks' => $stocks,
            'stamping_gp' => $stamping_gp,
            'stamping_defect' => $stamping_defect,
            'paiting_gp' => $paiting_gp,
            'paiting_defect' => $paiting_defect,
            'mechanical_gp' => $mechanical_gp,
            'mechanical_defect' => $mechanical_defect,
            'test_gp' => $test_gp,
            'test_defect' => $test_defect,
            'sizing_gp' => $sizing_gp,
            'sizing_defect' => $sizing_defect,
            'electro_gp' => $electro_gp,
            'electro_defect' => $electro_defect,
            'gp_gp' => $gp_gp,
            'gp_defect' => $gp_defect,
            'plastic_gp' => $plastic_gp,
            'plastic_defect' => $plastic_defect,
            'regulator_gp' => $regulator_gp,
            'regulator_defect' => $regulator_defect,
            'printing_gp' => $printing_gp,
            'printing_defect' => $printing_defect,
            'forming_gp' => $forming_gp,
            'forming_defect' => $forming_defect,
            'checking_gp' => $checking_gp,
            'checking_defect' => $checking_defect,
            //проценты
            'stamping_gp_percent' => $stamping_gp_percent,
            'stamping_defect_percent' => $stamping_defect_percent,

            'paiting_gp_percent' => $paiting_gp_percent,
            'paiting_defect_percent' => $paiting_defect_percent,

            'mechanical_gp_percent' => $mechanical_gp_percent,
            'mechanical_defect_percent' => $mechanical_defect_percent,

            'test_gp_percent' => $test_gp_percent,
            'test_defect_percent' => $test_defect_percent,

            'sizing_gp_percent' => $sizing_gp_percent,
            'sizing_defect_percent' => $sizing_defect_percent,

            'electro_gp_percent' => $electro_gp_percent,
            'electro_defect_percent' => $electro_defect_percent,

            'gp_gp_percent' => $gp_gp_percent,
            'gp_defect_percent' => $gp_defect_percent,

            'plastic_gp_percent' => $plastic_gp_percent,
            'plastic_defect_percent' => $plastic_defect_percent,

            'regulator_gp_percent' => $regulator_gp_percent,
            'regulator_defect_percent' => $regulator_defect_percent,

            'printing_gp_percent' => $printing_gp_percent,
            'printing_defect_percent' => $printing_defect_percent,

            'forming_gp_percent' => $forming_gp_percent,
            'forming_defect_percent' => $forming_defect_percent,

            'checking_gp_percent' => $checking_gp_percent,
            'checking_defect_percent' => $checking_defect_percent,

        ]);
    }

    public function actionUpload($CKEditorFuncNum) {
        $file = UploadedFile::getInstanceByName('upload');
        if ($file) {
            $path = 'uploads/image/';

            $name = time()+mt_rand(0, 1000000).'.'.$file->extension;
            $path_image = $path.$name;

            if ($file->saveAs($path_image)) {
                return '<script type="text/javascript">window.parent.CKEDITOR.tools.callFunction("'.$CKEditorFuncNum.'", "/'.$path_image.'", "");</script>';
            } else {
                return "Возникла ошибка при загрузке файла\n";
            }
        } else {
            return "Файл не загружен\n";
        }
    }

    public function actionUpdate() {
        $model = User::find()->with('image')->where(['id'=>Yii::$app->user->identity->id])->one();
        
        if ($model) {
            $role = ($model->role == User::ROLE_ADMIN) ? User::ROLE_ADMIN : User::ROLE_MODERATOR;
            $model->scenario = User::UPDATE_ADMIN_USER;
            $current_password = $model->password;

            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $model->password = !$model->password ? $current_password : $model->generatePassword($model->password);
                if ($model->saveUser($role)) {
                    Yii::$app->session->setFlash('profile_saved', 'Информация успешно сохранена');
                }
                return $this->redirect(['/admin/default/profile']);
            }
        }

        $regions = ArrayHelper::map(Category::find()->where(['type'=>'region'])->all(), 'id', 'name_ru');

        return $this->render('update', [
            'model' => $model,
            'regions' => $regions
        ]);
    }

    public function actionChangeAll() {
        if (Yii::$app->request->isAjax && ($data = Yii::$app->request->post())) {
            if ($data['action'] == 'disable') {
                if ($data['page'] == 'user') {
                    User::updateAll(['status'=>0], ['in', 'id', $data['ids']]);
                }
                if ($data['page'] == 'moderator') {
                    User::updateAll(['status'=>0], ['in', 'id', $data['ids']]);
                }
            }

            if ($data['action'] == 'enable') {
                if ($data['page'] == 'user') {
                    User::updateAll(['status'=>1], ['in', 'id', $data['ids']]);
                }
                if ($data['page'] == 'moderator') {
                    User::updateAll(['status'=>1], ['in', 'id', $data['ids']]);
                }
            }

            if ($data['action'] == 'remove') {
                if ($data['page'] == 'user') {
                    $model = User::find()->with('image')->where(['in', 'id', $data['ids']])->all();
                    if ($model) {
                        foreach ($model as $user) {
                            $user->removeUser();
                        }
                    }
                }
                if ($data['page'] == 'news') {
                    $model = News::find()->with('image')->where(['in', 'id', $data['ids']])->all();
                    if ($model) {
                        foreach ($model as $news) {
                            $news->removeNews();
                        }
                    }
                }
                if ($data['page'] == 'moderator') {
                    $model = User::find()->with('image')->where(['in', 'id', $data['ids']])->all();
                    if ($model) {
                        foreach ($model as $moderator) {
                            $moderator->delete();
                        }
                    }
                }
            }

            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    // public function actionBranches() {
    //     // Bankomats::deleteAll();
    //     $path_file = 'bankomat.xlsx';
    //     $inputFileType = \PHPExcel_IOFactory::identify($path_file);
    //     $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
    //     $objPHPExcel = $objReader->load($path_file);

    //     $sheet = $objPHPExcel->getSheet(0);
    //     $highestRow = $sheet->getHighestRow();
    //     $highestColumn = $sheet->getHighestColumn();

    //     $vals = array();
    //     $keys = array('region_id', 'card_type', 'type', 'vazifalari_type', 'terminal_id', 'merchant_id', 'bank_name', 'branch_name', 'branch_mfo', 'bankomat_place', 'bankomat_address', 'location', 'phone');

    //     for ($row = 0; $row <= $highestRow; $row++) {
    //         $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);

    //         if ($row < 365 || $row > 394) {
    //             continue;
    //         }

    //         if ($rowData[0][0]) {
    //             $vals[] = [
    //                 'region_id' => 13,
    //                 'card_type' => $rowData[0][1],
    //                 'type' => $rowData[0][2],
    //                 'vazifalari_type' => $rowData[0][3],
    //                 'terminal_id' => $rowData[0][4],
    //                 'merchant_id' => $rowData[0][5],
    //                 'bank_name' => $rowData[0][6],
    //                 'branch_name' => $rowData[0][7],
    //                 'branch_mfo' => $rowData[0][9],
    //                 'bankomat_place' => $rowData[0][9],
    //                 'bankomat_address' => $rowData[0][10],
    //                 'location' => $rowData[0][11],
    //                 'phone' => $rowData[0][12],
    //             ];
    //         }
    //     }

    //     // echo '<pre>';
    //     // print_r($vals);
    //     // die;

    //     Yii::$app->db->createCommand()->batchInsert('bankomats', $keys, $vals)->execute();
    // }
}
?>