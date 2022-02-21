<?php

namespace app\models\stock;

use Yii;
use app\models\user\User;
use app\models\product\Product;
use app\models\stack\Stack;

/**
 * This is the model class for table "stock".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $name_ru
 * @property string|null $name_en
 * @property string|null $name_uz
 * @property string|null $description_ru
 * @property string|null $description_en
 * @property string|null $description_uz
 * @property string|null $address
 * @property string|null $lat
 * @property string|null $lng
 * @property int $status
 * @property int $sort
 * @property string $date
 * @property string|null $ip
 *
 * @property Product[] $products
 * @property ProductMoving[] $productMovings
 * @property ProductMoving[] $productMovings0
 * @property StockShelving[] $stockShelvings
 */
class Stock extends \yii\db\ActiveRecord
{
    public $login, $password;

    const SIGN_UP = 'sign_up';
    const UPDATE = 'update';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'stock';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name_ru'], 'required', 'message'=>'Заполните поле'],
            [['name_ru'], 'required', 'message'=>'Заполните поле', 'on'=>self::SIGN_UP],
            // ['login', 'checkLogin', 'on'=>self::SIGN_UP],

            // update
            [['name_ru'], 'required', 'message'=>'Заполните поле', 'on'=>self::UPDATE],
            // ['login', 'checkLogin', 'on'=>self::UPDATE],
            
            [['user_id', 'status', 'sort'], 'integer'],
            [['description_ru', 'description_en', 'description_uz', 'login', 'password', 'name_owner'], 'string'],
            [['date'], 'safe'],
            [['name_ru', 'name_en', 'name_uz', 'address', 'code', 'lat', 'lng', 'ip'], 'string', 'max' => 255],
        ];
    }

    // check exist login
    public function checkLogin($attribute, $params) {
        if (!$this->hasErrors()) {
            $user = User::findOne(['login'=>$this->login]);
            if ($user && ($user->id != $this->user_id)) {
                return $this->addError($attribute, 'Логин уже занят');
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'name_ru' => 'Name Ru',
            'name_en' => 'Name En',
            'name_uz' => 'Name Uz',
            'description_ru' => 'Description Ru',
            'description_en' => 'Description En',
            'description_uz' => 'Description Uz',
            'address' => 'Address',
            'lat' => 'Lat',
            'lng' => 'Lng',
            'status' => 'Status',
            'sort' => 'Sort',
            'date' => 'Date',
            'ip' => 'Ip',
        ];
    }

    public function fields() {
        $headers = Yii::$app->request->headers;
        $language = $headers->has('Content-Language') ? $headers->get('Content-Language') : 'ru';

        return [
            'id',
            'code',
            'name' => function() use($language) {return $this->{'name_'.$language} ? $this->{'name_'.$language} : $this->name_ru;},
            'description' => function() use($language) {return $this->{'description_'.$language} ? $this->{'description_'.$language} : $this->description_ru;},
            'address',
            'lat',
            'lng'
        ];
    }

    /**
     * Gets query for [[Products]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['stock_id' => 'id']);
    }

    /**
     * Gets query for [[ProductMovings]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProductMovingsA()
    {
        return $this->hasMany(ProductMoving::className(), ['stock_a_id' => 'id']);
    }

    /**
     * Gets query for [[ProductMovings0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProductMovingsB()
    {
        return $this->hasMany(ProductMoving::className(), ['stock_b_id' => 'id']);
    }

    /**
     * Gets query for [[StockShelvings]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStockShelvings()
    {
        return $this->hasMany(StockShelving::className(), ['stock_id' => 'id']);
    }

    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getStacks()
    {
        return $this->hasMany(Stack::className(), ['stock_id' => 'id']);
    }
}
