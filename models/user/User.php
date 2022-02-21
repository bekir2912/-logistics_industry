<?php
namespace app\models\user;

use app\models\industry\DepartmentStamping;
use app\models\industry\handbook\BDepartment;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\web\IdentityInterface;
use yii\web\UploadedFile;

use app\models\Images;
use app\models\moderator\ModeratorAccess;
use app\models\Category;
use app\models\feedback\Feedback;

use yii\services\Sms;

class User extends ActiveRecord implements IdentityInterface {
    // roles
    const ROLE_ADMIN = 1;

    const ROLE_OTK = 1;
    const ROLE_UCHET = 2;


    const ROLE_MODERATOR = 2;
    const ROLE_USER = 3;
    const ROLE_STOCK = 4;
    const ROLE_WORKER = 5;
    const ROLE_WORKER_SHOP = 6;

    const ROLE_INDUSTRY_ADMIN = 20;
    const ROLE_INDUSTRY_USER = 21;


    const ROLE_WORKER_STAMPING = 101;
    const ROLE_WORKER_PAITING = 102;
    const ROLE_WORKER_MECHANICA = 103;
    const ROLE_WORKER_TEST = 104;
    const ROLE_WORKER_SIZING = 105;
    const ROLE_WORKER_ELECTRO = 106;
    const ROLE_WORKER_GP = 107;
    const ROLE_WORKER_PLASTIC = 108;
    const ROLE_WORKER_REGULATOR = 109;
    const ROLE_WORKER_PRINTING = 110;
    const ROLE_WORKER_FORMING = 111;
    const ROLE_WORKER_CHECKING = 112;

    // photo settings
    const PHOTO_PATH = 'uploads/user/';
    const PHOTO_DEFAULT = '/assets_files/img/user.png';

    // extra variales
    public $user_gallery = [];
    public $imageFiles = [];
    public $moderator_access = [];
    public $remember;
    public $password_repeat;

    // scenarios
    // admin
    const SIGNIN_ADMIN = 'signin_admin';
    const SIGNUP_ADMIN_USER = 'singup_admin_user';
    const UPDATE_ADMIN_USER = 'update_admin_user';

    // scenarios
    // industry_admin
    const SIGNIN_INDUSTRY_ADMIN = 'signin_industry_admin';
    const SIGNUP_INDUSTRY_ADMIN_USER = 'singup_industry_admin_user';
    const UPDATE_INDUSTRY_ADMIN_USER = 'update_industry_admin_user';


    // moderator
    const SIGNUP_MODERATOR = 'signup_moderator';
    const UPDATE_MODERATOR = 'update_moderator';

    // worker
    const SIGNUP_WORKER = 'signup_worker';
    const UPDATE_WORKER = 'update_worker';


    // shop
    const SIGNUP_WORKER_SHOP = 'signup_worker_shop';
    const UPDATE_WORKER_SHOP = 'update_worker_shop';

    // user
    const SIGNIN_USER = 'signin_user';
    const SIGNUP_USER = 'signup_user';
    const UPDATE_USER = 'update_user';
    const FORGOT_PASSWORD = 'forgot_password';
    const RECOVER_PASSWORD = 'recover_password';

    // INDUSTRY
    // user
    const SIGNIN_INDUSTRY_USER = 'signin_industry_user';
    const SIGNUP_INDUSTRY_USER = 'signup_industry_user';
    const UPDATE_INDUSTRY_USER = 'update_industry_user';
    const FORGOT_INDUSTRY_PASSWORD = 'forgot_industry_password';
    const RECOVER_INDUSTRY_PASSWORD = 'recover_industry_password';


    // stamping
    const SIGNUP_WORKER_STAMPING = 'signup_worker_stamping';
    const UPDATE_WORKER_STAMPING = 'update_worker_stamping';

    // paiting
    const SIGNUP_WORKER_PAITING = 'signup_worker_paiting';
    const UPDATE_WORKER_PAITING = 'update_worker_paiting';

    // MECHANICA
    const SIGNUP_WORKER_MECHANICA = 'signup_worker_mechanica';
    const UPDATE_WORKER_MECHANICA = 'update_worker_mechanica';

    // TEST
    const SIGNUP_WORKER_TEST = 'signup_worker_test';
    const UPDATE_WORKER_TEST = 'update_worker_test';

    // SIZING
    const SIGNUP_WORKER_SIZING = 'signup_worker_sizing';
    const UPDATE_WORKER_SIZING = 'update_worker_sizing';

    // ELECTRO
    const SIGNUP_WORKER_ELECTRO = 'signup_worker_electro';
    const UPDATE_WORKER_ELECTRO = 'update_worker_electro';

    // GP
    const SIGNUP_WORKER_GP = 'signup_worker_gp';
    const UPDATE_WORKER_GP = 'update_worker_gp';

    public static function tableName() {
        return 'b_user';
    }

    public function rules() {
        return [
            // moderator
            // sign-up
            [['name', 'login', 'password', 'is_part','is_edit', 'is_remove',  'department_id'], 'required', 'message'=>'Заполните поле', 'on'=>self::SIGNUP_MODERATOR],
            ['login', 'checkLogin', 'on'=>self::SIGNUP_MODERATOR],

            // update
            [['name', 'login'], 'required', 'message'=>'Заполните поле', 'on'=>self::UPDATE_MODERATOR],
            ['login', 'checkLogin', 'on'=>self::UPDATE_MODERATOR],

            // worker
            // sign-up
            [['name', 'login', 'password', 'type'], 'required', 'message'=>'Заполните поле', 'on'=>self::SIGNUP_WORKER],
            ['login', 'checkLogin', 'on'=>self::SIGNUP_WORKER],

            // update
            [['name', 'login', 'type'], 'required', 'message'=>'Заполните поле', 'on'=>self::UPDATE_WORKER],
            ['login', 'checkLogin', 'on'=>self::UPDATE_WORKER],



            // worker shop
            // sign-up
            [['name', 'login', 'password'], 'required', 'message'=>'Заполните поле', 'on'=>self::SIGNUP_WORKER_SHOP],
            ['login', 'checkLogin', 'on'=>self::SIGNUP_WORKER_SHOP],

            // update
            [['name', 'login'], 'required', 'message'=>'Заполните поле', 'on'=>self::UPDATE_WORKER_SHOP],
            ['login', 'checkLogin', 'on'=>self::UPDATE_WORKER_SHOP],

//INDUSTRT
            // worker stamping
            // sign-up
            [['name', 'login', 'password'], 'required', 'message'=>'Заполните поле', 'on'=>self::SIGNUP_WORKER_STAMPING],
            ['login', 'checkLogin', 'on'=>self::SIGNUP_WORKER_STAMPING],

            // update
            [['name', 'login'], 'required', 'message'=>'Заполните поле', 'on'=>self::UPDATE_WORKER_STAMPING],
            ['login', 'checkLogin', 'on'=>self::UPDATE_WORKER_STAMPING],


            // worker paiting
            // sign-up
            [['name', 'login', 'password'], 'required', 'message'=>'Заполните поле', 'on'=>self::SIGNUP_WORKER_PAITING],
            ['login', 'checkLogin', 'on'=>self::SIGNUP_WORKER_PAITING],

            // update
            [['name', 'login'], 'required', 'message'=>'Заполните поле', 'on'=>self::UPDATE_WORKER_PAITING],
            ['login', 'checkLogin', 'on'=>self::UPDATE_WORKER_PAITING],


            // worker mechanica
            // sign-up
            [['name', 'login', 'password'], 'required', 'message'=>'Заполните поле', 'on'=>self::SIGNUP_WORKER_MECHANICA],
            ['login', 'checkLogin', 'on'=>self::SIGNUP_WORKER_MECHANICA],

            // update
            [['name', 'login'], 'required', 'message'=>'Заполните поле', 'on'=>self::UPDATE_WORKER_MECHANICA],
            ['login', 'checkLogin', 'on'=>self::UPDATE_WORKER_MECHANICA],


            // worker test
            // sign-up
            [['name', 'login', 'password'], 'required', 'message'=>'Заполните поле', 'on'=>self::SIGNUP_WORKER_TEST],

            ['login', 'checkLogin', 'on'=>self::SIGNUP_WORKER_TEST],
            // update
            [['name', 'login'], 'required', 'message'=>'Заполните поле', 'on'=>self::UPDATE_WORKER_TEST],
            ['login', 'checkLogin', 'on'=>self::UPDATE_WORKER_TEST],


            // worker SIZING
            // sign-up
            [['name', 'login', 'password', ], 'required', 'message'=>'Заполните поле', 'on'=>self::SIGNUP_WORKER_SIZING],

            ['login', 'checkLogin', 'on'=>self::SIGNUP_WORKER_SIZING],
            // update
            [['name', 'login'], 'required', 'message'=>'Заполните поле', 'on'=>self::UPDATE_WORKER_SIZING],
            ['login', 'checkLogin', 'on'=>self::UPDATE_WORKER_SIZING],


            // worker Electro
            // sign-up
            [['name', 'login', 'password'], 'required', 'message'=>'Заполните поле', 'on'=>self::SIGNUP_WORKER_ELECTRO],

            ['login', 'checkLogin', 'on'=>self::SIGNUP_WORKER_ELECTRO],
            // update
            [['name', 'login'], 'required', 'message'=>'Заполните поле', 'on'=>self::UPDATE_WORKER_ELECTRO],
            ['login', 'checkLogin', 'on'=>self::UPDATE_WORKER_ELECTRO],


            // worker GP
            // sign-up
            [['name', 'login', 'password'], 'required', 'message'=>'Заполните поле', 'on'=>self::SIGNUP_WORKER_GP],

            ['login', 'checkLogin', 'on'=>self::SIGNUP_WORKER_GP],
            // update
            [['name', 'login'], 'required', 'message'=>'Заполните поле', 'on'=>self::UPDATE_WORKER_GP],
            ['login', 'checkLogin', 'on'=>self::UPDATE_WORKER_GP],


            // industry admin
            // sing in to admin panel
            [['login', 'password'], 'required', 'message'=>'Заполните поле', 'on'=>self::SIGNIN_INDUSTRY_ADMIN],
            ['password', 'checkPassword', 'on'=>self::SIGNIN_INDUSTRY_ADMIN],

            // save user by admin
            [['password', 'name', 'phone', 'is_part', 'department_id'], 'required', 'message'=>'Заполните поле', 'on'=>self::SIGNUP_INDUSTRY_ADMIN_USER],
            [['name', 'phone'], 'required', 'message'=>'Заполните поле', 'on'=>self::UPDATE_INDUSTRY_ADMIN_USER],
            ['phone', 'checkPhone', 'on'=>self::SIGNUP_INDUSTRY_ADMIN_USER],
            ['email', 'checkEmail', 'on'=>self::SIGNUP_INDUSTRY_ADMIN_USER],
            ['phone', 'checkPhoneAdmin', 'on'=>self::UPDATE_INDUSTRY_ADMIN_USER],


            [['email', 'password','is_part',  'department_id'], 'required', 'message'=>'Заполните поле', 'on'=>self::SIGNIN_INDUSTRY_ADMIN],
            ['password', 'checkPassword', 'on'=>self::SIGNIN_INDUSTRY_USER],

            // sign up user
            [['email', 'password','is_part', 'department_id'], 'required', 'message'=>'Заполните поле', 'on'=>self::SIGNUP_INDUSTRY_USER],
            // [['phone'], 'required', 'message'=>'Заполните поле', 'on'=>self::UPDATE_USER],
            ['email', 'checkEmail', 'on'=>self::SIGNUP_INDUSTRY_USER],
            ['email', 'checkEmail', 'on'=>self::UPDATE_INDUSTRY_USER],



            [['email', 'password'], 'required', 'message'=>'Заполните поле', 'on'=>self::SIGNIN_INDUSTRY_USER],
            ['password', 'checkPassword', 'on'=>self::SIGNIN_INDUSTRY_USER],

            // sign up user
            [['email', 'password'], 'required', 'message'=>'Заполните поле', 'on'=>self::SIGNUP_INDUSTRY_USER],
            // [['phone'], 'required', 'message'=>'Заполните поле', 'on'=>self::UPDATE_USER],
            ['email', 'checkEmail', 'on'=>self::SIGNUP_INDUSTRY_USER],
            ['email', 'checkEmail', 'on'=>self::UPDATE_INDUSTRY_USER],
            // ['phone', 'checkPhone', 'on'=>self::SIGNUP_USER],
            // ['phone', 'checkPhone', 'on'=>self::UPDATE_USER],

            // recover password
            [['phone'], 'required', 'message'=>'Заполните поле', 'on'=>self::FORGOT_INDUSTRY_PASSWORD],
            ['phone', 'checkPhoneExists', 'on'=>self::FORGOT_INDUSTRY_PASSWORD],
            [['password', 'password_repeat'], 'required', 'message'=>'Заполните поле', 'on'=>self::RECOVER_INDUSTRY_PASSWORD],
            ['password', 'compare', 'compareAttribute'=>'password_repeat', 'message'=>"Пароли не совпадают", 'on'=>self::RECOVER_INDUSTRY_PASSWORD],



// END INDUSTRT


            // admin
            // sing in to admin panel
            [['login', 'password'], 'required', 'message'=>'Заполните поле', 'on'=>self::SIGNIN_ADMIN],
            ['password', 'checkPassword', 'on'=>self::SIGNIN_ADMIN],

            // save user by admin
            [['password', 'name', 'phone'], 'required', 'message'=>'Заполните поле', 'on'=>self::SIGNUP_ADMIN_USER],
            [['name', 'phone'], 'required', 'message'=>'Заполните поле', 'on'=>self::UPDATE_ADMIN_USER],
            ['phone', 'checkPhone', 'on'=>self::SIGNUP_ADMIN_USER],
            ['email', 'checkEmail', 'on'=>self::SIGNUP_ADMIN_USER],
            ['phone', 'checkPhoneAdmin', 'on'=>self::UPDATE_ADMIN_USER],







            // user
            // sign in
            [['email', 'password'], 'required', 'message'=>'Заполните поле', 'on'=>self::SIGNIN_USER],
            ['password', 'checkPassword', 'on'=>self::SIGNIN_USER],

            // sign up user
            [['email', 'password'], 'required', 'message'=>'Заполните поле', 'on'=>self::SIGNUP_USER],
            // [['phone'], 'required', 'message'=>'Заполните поле', 'on'=>self::UPDATE_USER],
            ['email', 'checkEmail', 'on'=>self::SIGNUP_USER],
            ['email', 'checkEmail', 'on'=>self::UPDATE_USER],
            // ['phone', 'checkPhone', 'on'=>self::SIGNUP_USER],
            // ['phone', 'checkPhone', 'on'=>self::UPDATE_USER],

            // recover password
            [['phone'], 'required', 'message'=>'Заполните поле', 'on'=>self::FORGOT_PASSWORD],
            ['phone', 'checkPhoneExists', 'on'=>self::FORGOT_PASSWORD],
            [['password', 'password_repeat'], 'required', 'message'=>'Заполните поле', 'on'=>self::RECOVER_PASSWORD],
            ['password', 'compare', 'compareAttribute'=>'password_repeat', 'message'=>"Пароли не совпадают", 'on'=>self::RECOVER_PASSWORD],

            // default validation
            // [['email'], 'email', 'message'=>'Не верный формат e-mail'],
            //[['email'], 'unique'],
            [['password', 'password_repeat'], 'string', 'min'=>6, 'message'=>'Пароль не может быть менее 6 символов'],
            ['password_repeat', 'compare', 'compareAttribute'=>'password', 'message'=>"Пароли не совпадают"],
            [['login', 'phone', 'email', 'name', 'lastname', 'fname', 'ip', 'token', 'birthday', 'type', 'address_fact', 'inn', 'mfo'], 'string'],
            [['role', 'status', 'region_id', 'gender', 'admin_style'], 'integer'],
            [['date', 'moderator_access', 'remember', 'device_token', 'department_id','is_edit', 'is_remove','is_edit', 'is_remove',   'is_part'], 'safe'],
            [['user_gallery'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'maxSize' => 2048000],
            [['imageFiles'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'maxSize' => 2048000]
        ];
    }

    public static function findIdentity($id){
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null){
        return static::findOne(['token'=>$token]);
    }

    public static function findByUsername($username){
        $user = static::findOne(['login'=>$username]);
        
        if (!$user) {
            $user = static::findOne(['phone'=>$username]);
        }

        if (!$user) {
            $user = static::findOne(['email'=>$username]);
        }

        if (!$user) {
            $user = static::findOne(['login'=>$username]);
        }

        return $user;
    }

    public function getId() {
        return $this->id;
    }

    public function getAuthKey(){
        return $this->authKey;
    }

    public function validateAuthKey($authKey){
        return $this->authKey === $authKey;
    }

    //helpers
    public function setPassword($password){
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    public function generatePassword($password) {
        return Yii::$app->security->generatePasswordHash($password);
    }

    public function generatePasswordResetToken(){
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();    
    }

    public function generateAuthKey(){
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function validatePassword($password){
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    public function checkPhoneExists($attribute, $params) {
        if (!$this->hasErrors()) {
            if (!preg_match("/^[\d]+$/", $this->phone)) {
                return $this->addError($attribute, 'Вводите только цифры');
            }
            if ((mb_strlen($this->phone) < 12) || (mb_strlen($this->phone) > 12)) {
                return $this->addError($attribute, 'Количество цифр должно быть 12');
            }

            $user = $this->findByUsername($this->phone);

            if (!$user) {
                return $this->addError($attribute, 'Такого номера в базе нет');
            }
        }

        return false;
    }

    // validate check password
    public function checkPassword($attribute, $params) {
        //return true;
        if (!$this->hasErrors()) {
            if ($this->login) {
                $user = $this->findByUsername($this->login);
            }
            if ($this->phone) {
                $user = $this->findByUsername($this->phone);
            }
            if ($this->email) {
                $user = $this->findByUsername($this->email);
            }

            $error_login = 'Не верный логин и/или пароль';
            if (!$user || !$user->validatePassword($this->password)) {
                return $this->addError($attribute, $error_login);
            }
        }

        return false;
    }

    // check exist login
    public function checkLogin($attribute, $params) {
        if (!$this->hasErrors()) {
            $user = $this->findByUsername($this->login);
            if ($user && ($user->id != $this->id)) {
                return $this->addError($attribute, 'Логин уже занят');
            }
        }

        return false;
    }

    // check exist email
    public function checkEmail($attribute, $params) {
        if (!$this->hasErrors()) {
            if ($this->email) {
                $user = $this->findByUsername($this->email);
                // echo $user->id.':'.$this->id;
                // die;
                if ($user && ($user->id != $this->id)) {
                    return $this->addError($attribute, 'E-mail уже занят');
                }
            }
        }

        return false;
    }

    // check exist phone
    public function checkPhone($attribute, $params) {
        if (!$this->hasErrors()) {
            if (!preg_match("/^[\d]+$/", $this->phone)) {
                return $this->addError($attribute, 'Вводите только цифры');
            }
            if ((mb_strlen($this->phone) < 12) || (mb_strlen($this->phone) > 12)) {
                return $this->addError($attribute, 'Количество цифр должно быть 12');
            }

            $user = $this->findByUsername($this->phone);

            if ($user && ($user->id != $this->id)) {
                return $this->addError($attribute, 'Номер телефона уже занят');
            }
        }

        return false;
    }

    // check exist phone
    public function checkPhoneAdmin($attribute, $params) {
        if (!$this->hasErrors()) {
            if (!preg_match("/^[\d]+$/", $this->phone)) {
                return $this->addError($attribute, 'Вводите только цифры');
            }
            if ((mb_strlen($this->phone) < 12) || (mb_strlen($this->phone) > 12)) {
                return $this->addError($attribute, 'Количество цифр должно быть 12');
            }

            $user = $this->findByUsername($this->phone);

            if ($user && ($user->id != $this->id) && ($user->status == 1)) {
                return $this->addError($attribute, 'Номер телефона уже занят');
            }
        }

        return false;
    }

    // check exist phone
    public function checkPhoneAuth($attribute, $params) {
        if (!$this->hasErrors()) {
            if (!preg_match("/^[\d]+$/", $this->phone)) {
                return $this->addError($attribute, 'Вводите только цифры');
            }
            // if ((mb_strlen($this->phone) < 12) || (mb_strlen($this->phone) > 12)) {
            //     return $this->addError($attribute, 'Количество цифр должно быть 12');
            // }

            $user = $this->findByUsername($this->phone);

            if ($user && ($user->id != $this->id) && ($user->status == 1)) {
                return $this->addError($attribute, 'Номер телефона уже занят');
            }
        }

        return false;
    }

    // save user
    public function saveUser($type = self::ROLE_INDUSTRY_USER, $status = 0) {
        $this->ip = $_SERVER['REMOTE_ADDR'];
        $this->role = $type;
//        $this->is_part = $is_part;
//        $this->department_id = $department_id;

        $this->status = $status;

        $current_image = $this->image ? $this->image : null;

        if (!$this->gender) {
            $this->gender = 1;
        }

        if ($this->save()) {
            if ($this->moderator_access) {
                ModeratorAccess::deleteAll('user_id = :user_id', ['user_id'=>$this->id]);

                $keys = array('user_id', 'moderator_id');
                $vals = array();
                foreach ($this->moderator_access as $key => $access) {
                    $vals[$key]['user_id'] = $this->id;
                    $vals[$key]['moderator_id'] = $access;
                }
                Yii::$app->db->createCommand()->batchInsert('b_moderator_access', $keys, $vals)->execute();
            }
            
            // upload file
            $image = new Images;
            // image
            if ($image->imageFiles = UploadedFile::getInstances($this, 'imageFiles')) {
                if ($current_image) {
                    $current_image->removeImageSize();
                }
                $image->uploadPhoto($this->id, 'user');
            }

            // gallery
            $gallery = new Images;
            if ($gallery->imageFiles = UploadedFile::getInstances($this, 'user_gallery')) {
                $gallery->uploadPhoto($this->id, 'user', 2);
            }

            return $this;
        }

        return false;
    }

    public function saveCode($phone = null) {
        $code = new SmsCode;
        
        $current_phone = $phone ? $phone : $this->phone;
        $model = SmsCode::find()->where(['phone'=>$current_phone]);

        if ($this->id) {
            $model->andWhere(['user_id'=>$this->id]);
            $code->user_id = $this->id;
        }

        $model = $model->one();

        if ($model) {
            $model->delete();
        }
        
        $code->phone = $current_phone;
        // $code->code = (string)$this->generateCode();
        $code->code = '000000';
        $code->sms_expire = strtotime('+3 minute');

        if ($code->save()) {
            $service = new Sms;
            $service->send($code->phone, "Tutortop.me\nВаш код: ".$code->code);
            return $code;
        }

        return false;
    }

    public function generateFileName() {
        return time()+mt_rand(0, 1000000);
    }

    public function generateCode() {
        return mt_rand(100000, 999999);
    }

    public function generateToken() {
        return Yii::$app->security->generateRandomString();
    }

    public function removeUser(){
        if ($this->image) {
            $this->image->removeImageSize();
        }

        return $this->delete();
    }

    public function login() {
        if ($this->login) {
            $user = $this->findByUsername($this->login);
        }

        if ($this->phone) {
            $user = $this->findByUsername($this->phone);
        }

        if ($this->email) {
            $user = $this->findByUsername($this->email);
        }

        if ($user) {
            if (Yii::$app->user->login($user, $this->remember ? 3600*24*30 : 0)) {
                return $user;
            }
        }
        return false;
    }

    public function changePassword() {
        $this->password ? $this->setPassword(Html::encode($this->password)) : $this->password = $this->getOldAttributes()['password'];
        return $this->save(false) ? true : false;
    }

    public function getPhoto($s = 'original') {
        if ($this->image) {
            $path = self::PHOTO_PATH.$this->image->object_id.'/'.$s.'/'.$this->image->photo;

            if (is_file($path)) {
                return '/'.$path;
            }
        }

        return self::PHOTO_DEFAULT;
    }

    public function getGallery($s = 'original') {
        $data = [];

        if ($this->photos) {
            foreach ($this->photos as $photo) {
                $data[] = [
                    'photo' => '/'.self::PHOTO_PATH.$photo->object_id.'/'.$s.'/'.$photo->photo,
                    // 'verified' => $photo->status
                ];
            }
        }

        return $data;
    }

    // relations
    public function getImage() {
        return $this->hasOne(Images::className(), ['object_id'=>'id'])->andOnCondition(['type'=>'user', 'main'=>1]);
    }

    public function getPhotos() {
        return $this->hasMany(Images::className(), ['object_id'=>'id'])->andOnCondition(['type'=>'user', 'main'=>2]);
    }

    public function getModeratorAccess() {
        return $this->hasMany(ModeratorAccess::className(), ['user_id'=>'id']);
    }

    public function getRegion()
    {
        return $this->hasOne(Category::className(), ['id' => 'region_id']);
    }

    public function getDepartment()
    {
        return $this->hasOne(BDepartment::className(), ['id' => 'department_id']);
    }
}