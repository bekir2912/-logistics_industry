<?php

namespace app\models;

use Yii;
use yii\imagine\Image;
use app\models\user\User;

class Images extends \yii\db\ActiveRecord {
    const PHOTO_USER_PATH = 'uploads/user/';
    const PHOTO_CATEGORY_PATH = 'uploads/category/';
    const PHOTO_PRODUCT_PATH = 'uploads/product/';
    const PHOTO_GP_PATH = 'uploads/gp/';
    const PHOTO_DEFAULT = '/assets_files/img/photo.png';

    public $imageFiles = [];

    public $object = array(
        'user' => self::PHOTO_USER_PATH,
        'category' => self::PHOTO_CATEGORY_PATH,
        'product' => self::PHOTO_PRODUCT_PATH,
        'gp' => self::PHOTO_GP_PATH
    );

    public $image_sizes = array(
        '50'=>'50',
        '100'=>'100',
        '150'=>'150',
        '200'=>'200',
        '250'=>'250',
        '300'=>'300');

    public static function tableName() {
        return 'image';
    }

    public function rules() {
        return [
            [['object_id', 'main', 'sort', 'status'], 'integer'],
            [['type', 'photo'], 'string', 'max' => 255],
            [['imageFiles'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, svg', 'maxSize' => 2048000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'object_id' => 'Object ID',
            'type' => 'Type',
            'photo' => 'Photo',
            'main' => 'Main',
            'sort' => 'Sort',
        ];
    }

    public function uploadPhoto($object_id, $type, $main = 1, $type_image = null, $check = true){
        if (!array_key_exists($type, $this->object)) {
            return false;
        }

        $path = $this->object[$type];

        if (is_array($object_id)) {
            foreach ($object_id as $v) {
                if (!is_dir($path.$v)) {
                    mkdir($path.$v, 0755);
                }

                if (!is_dir($path.$v.'/original')) {
                    mkdir($path.$v.'/original', 0755);
                }
            }
        } else {
            if (!is_dir($path.$object_id)) {
                mkdir($path.$object_id, 0755);
            }

            if (!is_dir($path.$object_id.'/original')) {
                mkdir($path.$object_id.'/original', 0755);
            }
        }

        foreach ($this->imageFiles as $key => $file) {
            $file = is_array($file) ? $file[$key] : $file;

            if (is_array($object_id)) {
                $id = $object_id[$key];
            } else {
                $id = $object_id;
            }
            $rnd = mt_rand(0, 1000000);
            $name = time() + $rnd.'.'.$file->extension;
            $original = $path.$id.'/original/'.$name;
            
            $file->saveAs($original);

            if (($file->extension != 'mp4') && ($file->extension != 'svg')) {
                foreach ($this->image_sizes as $key => $img) {
                    if (!is_dir($path.$id.'/'.$key.'x'.$img)) {
                        mkdir($path.$id.'/'.$key.'x'.$img, 0755);
                    }
                    Image::thumbnail($original, $key, $img)->save(Yii::getAlias($path.$id.'/'.$key.'x'.$img.'/'.$name), ['quality' => 80]);
                }
            }
            $status = (Yii::$app->user->identity->role == User::ROLE_ADMIN) ? 1 : 0;
            if ($this->photo && ($check == true)) {
                $original = $path.$id.'/original/'.$this->photo;
                if (is_file($original)) {
                    unlink($original);
                }
                if (($file->extension != 'mp4') && ($file->extension != 'svg')) {
                    foreach ($this->image_sizes as $key => $img) {
                        $photo = $path.$id.'/'.$key.'x'.$img.'/'.$this->photo;
                        if (is_file($photo)) {
                            unlink($photo);
                        }
                    }
                }

                Yii::$app->db->createCommand()->update('image', ['photo'=>$name], ['id'=>$this->id])->execute();
            } else {
                Yii::$app->db->createCommand()->insert('image', ['type'=>$type_image ? $type_image : $type, 'object_id'=>$id, 'photo'=>$name, 'main'=>$main, 'sort'=>0, 'status'=>$status])->execute();
            }
        }

        return true;
    }

    public function removeImage() {
        $path = $this->object[$this->type];

        $image = $path.$this->object_id.'/'.$this->photo;

        if (is_file($image)) {
            unlink($image);
        }

        return $this->delete();
    }

    public function removeImageSize() {
        $path = $this->object[$this->type];

        $original = $path.$this->object_id.'/original/'.$this->photo;

        if (is_file($original)) {
            unlink($original);
        }

        $dir_empty = false;

        foreach ($this->image_sizes as $k => $v) {
            $image = $path.$this->object_id.'/'.$k.'x'.$v.'/'.$this->photo;
            if (is_file($image)) {
                unlink($image);
            }

            $dir_empty = (glob($image.'*')) ? false : true;
        }

        $dir_empty = false;

        if ($dir_empty === true) {
            if (!is_file($path.$this->object_id.'/original/'.$this->photo)) {
                foreach ($this->image_sizes as $k => $v) {
                    rmdir($path.$this->object_id.'/'.$k.'x'.$v);
                }
                rmdir($path.$this->object_id.'/original');
                rmdir($path.$this->object_id);
            }
        }

        return $this->delete() ? true : false;
    }

    public function getPhoto($type, $size = 'original') {
        $path = 'uploads/'.$type.'/'.$this->object_id.'/'.$size.'/'.$this->photo;

        if (is_file($path)) {
            return '/'.$path;
        }

        return self::PHOTO_DEFAULT;
    }

    public function fields() {
        return ['id', 'photo'];
    }
}
