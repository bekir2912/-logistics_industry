<?php

namespace app\models;

use Yii;
use yii\helpers\Html;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;

use app\models\Images;
use app\models\user\UserDiscipline;
use app\models\page\Page;
use app\models\course\Course;

/**
 * This is the model class for table "category".
 *
 * @property int $id
 * @property int $parent_id
 * @property string $type
 * @property string $name
 * @property int $sort
 * @property string $date
 */
class Category extends \yii\db\ActiveRecord
{
    public $imageFiles = [];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name_ru'], 'required', 'message'=>'Заполните поле'],
            [['parent_id', 'sort', 'status', 'main'], 'integer'],
            [['date'], 'safe'],
            [['price'], 'number'],
            [['type', 'name_mini', 'name_ru', 'name_uz', 'name_en', 'description_ru', 'description_uz', 'description_en'], 'string', 'max' => 255],
            [['imageFiles'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, svg', 'maxSize' => 2048000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => 'Parent ID',
            'type' => 'Type',
            'name' => 'Name',
            'sort' => 'Sort',
            'date' => 'Date',
        ];
    }

    public function saveCategory(){
        $model = $this;

        if (array_key_exists('id', Yii::$app->request->post()['Category'])) {
            $model = self::findOne(Yii::$app->request->post()['Category']['id']);
            $model->name_ru = Html::encode($this->name_ru);
            $model->name_uz = Html::encode($this->name_uz);
            $model->name_en = Html::encode($this->name_en);
            $model->description_ru = Html::encode($this->description_ru);
            $model->description_uz = Html::encode($this->description_uz);
            $model->description_en = Html::encode($this->description_en);
            $model->price = $this->price;
        }

        if ($model->image) {
            $current_image = $model->image;
        }

        if ($model->save()) {
            $image = new Images;

            if ($image->imageFiles = UploadedFile::getInstances($this, 'imageFiles')) {
                if ($current_image) {
                    $current_image->removeImageSize();
                }
                $image->uploadPhoto($model->id, 'category');
            }
        }

        return true;
    }

    public function getPhoto($s = 'original') {
        if ($this->image && $this->image->photo) {
            $path = Images::PHOTO_CATEGORY_PATH.$this->image->object_id.'/'.$s.'/'.$this->image->photo;
            if (is_file($path)) {
                return '/'.$path;
            }
        }
        return Images::PHOTO_DEFAULT;
    }

    public function getCategories(array $elements, $parentId = 0, $up = false) {
        $branch = array();

        if ($up === false) {
            foreach ($elements as $element) {
                if ($element['parent_id'] == $parentId) {
                    $children = $this->getCategories($elements, $element['id']);
                    if ($children) {
                        $element['children'] = $children;
                    }
                    $branch[] = $element;
                }
            }
        } else {
            foreach ($elements as $element) {
                if ($element['id'] == $parentId) {
                    $parent = $this->getCategories($elements, $element['parent_id'], true);
                    if ($parent) {
                        $element['parent'] = $parent;
                    }
                    $branch[] = $element;
                    $this->ids[] = $element['id'];
                }
            }
        }

        return $branch;
    }

    public function urlLanguage() {
        return Yii::$app->urlManager->createUrl(['/main/change-language', 'id'=>$this->id]);
    }

    public function clearData($data) {
        return strip_tags(html_entity_decode(strip_tags($data)));
    }

    public function fields() {
        $headers = Yii::$app->request->headers;
        $language = $headers->has('Content-Language') ? $headers->get('Content-Language') : 'ru';

        return [
            'id',
            'name' => function() use($language) {return $this->{'name_'.$language} ? $this->{'name_'.$language} : $this->name_ru;},
            'description' => function() use($language) {return $this->{'name_'.$language} ? $this->{'name_'.$language} : $this->description_ru;},
            'photo',
            'price'
        ];
    }

    // relations
    public function getImage() {
        return $this->hasOne(Images::className(), ['object_id'=>'id'])->andOnCondition(['type'=>'category']);
    }

    public function getChilds() {
        return $this->hasMany(self::className(), ['parent_id' => 'id']);
    }

    public function getParent() {
        return $this->hasOne(self::className(), ['id' => 'parent_id']);
    }

    public function getNews() {
        return $this->hasMany(News::className(), ['category_id' => 'id'])->orderBy('id desc');
    }

    public function getPages() {
        return $this->hasMany(Page::className(), ['category_id' => 'id']);
    }

    public function getUserDiscipline() {
        return $this->hasMany(UserDiscipline::className(), ['category_id' => 'id']);
    }

    public function getCourses() {
        return $this->hasMany(Course::className(), ['category_id' => 'id']);
    }
}
