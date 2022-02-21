<?php

namespace app\models\product;

use Yii;
use yii\web\UploadedFile;

use app\models\Images;
use app\models\Category;
use app\models\stock\Stock;
use app\models\stack\Stack;
use app\models\stack\StackShelving;
use app\models\shop\ShopProduct;

use moonland\phpexcel\Excel;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property int|null $stock_id
 * @property string|null $name_ru
 * @property string|null $name_en
 * @property string|null $name_uz
 * @property string|null $description_ru
 * @property string|null $description_en
 * @property string|null $description_uz
 * @property string|null $article
 * @property string|null $model
 * @property float|null $price_buy
 * @property float|null $price_sale
 * @property int|null $amount
 * @property string|null $manufacturer
 * @property string|null $qr
 * @property int $status
 * @property int $sort
 * @property string $date
 * @property string|null $ip
 *
 * @property Stock $stock
 * @property ProductComing[] $productComings
 * @property ProductInventory[] $productInventories
 * @property ProductMoving[] $productMovings
 * @property ProductShipment[] $productShipments
 * @property ProductWriteoff[] $productWriteoffs
 */
class Product extends \yii\db\ActiveRecord
{
    public $imageFiles = [];
    public $file;

    const FILE_PATH = 'uploads/file/';


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['stock_id', 'amount', 'status', 'sort', 'provider_id', 'manufacturer_id', 'unit_id', 'region_id', 'category_id', 'status_upload', 'stack_id', 'shelf_id'], 'integer'],
            [['description_ru', 'description_en', 'description_uz', 'method_mark', 'tax', 'term', 'inventory', 'bonus', 'tnvd_code', 'property', 'limit', 'provider'], 'string'],
            [['price_buy', 'price_sale', 'amount_limit'], 'number'],
            [['date', 'file'], 'safe'],
            [['name_ru', 'name_en', 'name_uz', 'article', 'model', 'qr', 'ip'], 'string', 'max' => 255],
            [['stock_id'], 'exist', 'skipOnError' => true, 'targetClass' => Stock::className(), 'targetAttribute' => ['stock_id' => 'id']],
            [['imageFiles'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'maxSize' => 2048000],
            [['file'], 'file', 'skipOnEmpty' => true, 'extensions' => 'xls, xlsx']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'stock_id' => 'Stock ID',
            'name_ru' => 'Name Ru',
            'name_en' => 'Name En',
            'name_uz' => 'Name Uz',
            'description_ru' => 'Description Ru',
            'description_en' => 'Description En',
            'description_uz' => 'Description Uz',
            'article' => 'Article',
            'model' => 'Model',
            'price_buy' => 'Price Buy',
            'price_sale' => 'Price Sale',
            'amount' => 'Amount',
            'manufacturer' => 'Manufacturer',
            'qr' => 'Qr',
            'status' => 'Status',
            'sort' => 'Sort',
            'date' => 'Date',
            'ip' => 'Ip',
        ];
    }

    public function saveObject() {
        $current_image = $this->image ? $this->image : null;
        $this->qr = md5(uniqid()+microtime());
        if ($this->save()) {
            $image = new Images;
            if ($image->imageFiles = UploadedFile::getInstances($this, 'imageFiles')) {
                if ($current_image) {
                    $current_image->removeImageSize();
                }
                $image->uploadPhoto($this->id, 'product');
            }

            return true;
        }

        return false;
    }

    public function removeObject(){
        if ($this->image) {
            $this->image->removeImageSize();
        }

        return $this->delete();
    }

    public function getPhoto($s = 'original') {
        if ($this->image && $this->image->photo) {
            $path = Images::PHOTO_PRODUCT_PATH.$this->image->object_id.'/'.$s.'/'.$this->image->photo;
            if (is_file($path)) {
                return '/'.$path;
            }
        }
        return Images::PHOTO_DEFAULT;
    }

    public function getImage() {
        return $this->hasOne(Images::className(), ['object_id'=>'id'])->andOnCondition(['type'=>'product']);
    }

    public function fields() {
        $headers = Yii::$app->request->headers;
        $language = $headers->has('Content-Language') ? $headers->get('Content-Language') : 'ru';

        return [
            'id',
            'region',
            'stock',
            'unit',
            'name' => function() use($language) {return $this->{'name_'.$language} ? $this->{'name_'.$language} : $this->name_ru;},
            'description' => function() use($language) {return $this->{'description_'.$language} ? $this->{'description_'.$language} : $this->description_ru;},
            'article',
            'model',
            'price_buy',
            'price_sale',
            'amount',
            'manufacturer',
            'method_mark',
            'property',
            'bonus',
            'tax',
            'qr'
        ];
    }

    /**
     * Gets query for [[Stock]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStock()
    {
        return $this->hasOne(Stock::className(), ['id' => 'stock_id']);
    }

    public function getStack()
    {
        return $this->hasOne(Stack::className(), ['id' => 'stack_id']);
    }

    public function getStackShelf()
    {
        return $this->hasOne(StackShelving::className(), ['id' => 'shelf_id']);
    }



    /**
     * Gets query for [[ProductShipments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProductShipments()
    {
        return $this->hasMany(ProductShipment::className(), ['product_id' => 'id']);
    }

    public function getManufacturer()
    {
        return $this->hasOne(Category::className(), ['id' => 'manufacturer_id']);
    }

    public function getUnit()
    {
        return $this->hasOne(Category::className(), ['id' => 'unit_id']);
    }

    public function getRegion()
    {
        return $this->hasOne(Category::className(), ['id' => 'region_id']);
    }

    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    public function getShopProduct()
    {
        return $this->hasOne(ShopProduct::className(), ['product_id' => 'id']);
    }
}
