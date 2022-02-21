<?php
namespace app\modules\worker_shop\controllers;

use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;

use app\models\Images;
use app\models\File;
use app\models\Category;
use app\models\user\User;
use app\models\user\UserSearch;

use app\models\coming\Coming;
use app\models\writeoff\Writeoff;
use app\models\stock\Stock;
use app\models\product\Product;
use app\models\agent\Agent;
use app\models\purchase\Purchase;
use app\models\shipment\Shipment;
use app\models\noticeto\Noticeto;
use app\models\borrow\Borrow;
use app\models\maintenance\Maintenance;
use app\models\Branches;
use app\models\Regions;
use app\models\Bankomats;

use moonland\phpexcel\Excel;

class DefaultController extends Controller{
    public $user;

    public function beforeAction($action) {
        if ($action->id == 'upload') {
            $this->enableCsrfValidation = false;
        }
        if (!Yii::$app->user->isGuest) {
            $this->user = Yii::$app->user->identity;
        } else {
            return $this->redirect(['/']);
        }

        return parent::beforeAction($action);
    }

    public function actionIndex() {
        $model = new User;

        $model->scenario = User::SIGNIN_ADMIN;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->login() && ($user = $model->findByUsername($model->login))) {
                if ($user->role == User::ROLE_ADMIN) {
                    return $this->redirect("/worker_shop/default/dashboard");
                }
                if ($user->role == User::ROLE_WORKER) {
                    return $this->redirect("/worker_shop/default/dashboard");
                }
            }
        }

        return $this->render('index', [
            'model'=>$model
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
        $shipments = Shipment::find()->where(['status'=>0])->count();
        $products = Product::find()->count();
        $stocks = Stock::find()->count();

        return $this->render('dashboard', [
            'shipments' => $shipments,
            'products' => $products,
            'stocks' => $stocks
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
                return $this->redirect(['/worker_shop/default/profile']);
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