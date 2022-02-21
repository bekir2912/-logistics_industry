<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "file".
 *
 * @property int $id
 * @property int $object_id
 * @property string $type
 * @property string $url
 * @property int $main
 * @property int $sort
 */
class File extends \yii\db\ActiveRecord
{
    public $files = [];

    // constants
    const FILE_TENDER = 'uploads/tender/';
    const FILE_POLICE = 'uploads/police/';

    // objects
    public $object = array(
        'tender' => self::FILE_TENDER,
        'police' => self::FILE_POLICE
    );

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'file';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['object_id', 'main', 'sort'], 'integer'],
            [['type', 'url'], 'string', 'max' => 255],
            [['files'], 'file', 'skipOnEmpty' => true, 'extensions' => 'zip, rar, doc, docx, xls, xlsx, pdf, txt, psd', 'maxSize' => 2048000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'object_id' => 'Object ID',
            'type' => 'Type',
            'url' => 'Url',
            'main' => 'Main',
            'sort' => 'Sort',
        ];
    }

    public function upload($object_id, $type, $main = 1) {
        if (!array_key_exists($type, $this->object)) {
            return false;
        }

        $path = $this->object[$type];

        if (!is_dir($path.$object_id)) {
            mkdir($path.$object_id, 0755);
        }

        if (!is_dir($path.$object_id.'/file')) {
            mkdir($path.$object_id.'/file', 0755);
        }

        foreach ($this->files as $k => $v) {
            $rnd = mt_rand(0, 1000000);
            $name = time() + $rnd.'.'.$v->extension;
            $file = $path.$object_id.'/file/'.$name;
            
            $v->saveAs($file);

            if ($this->url) {
                $file = $path.$object_id.'/file/'.$this->url;
                if (is_file($file)) {
                    unlink($file);
                }
                Yii::$app->db->createCommand()->update('file', ['url'=>$name], ['id'=>$this->id])->execute();
            } else {
                Yii::$app->db->createCommand()->insert('file', ['type'=>$type, 'object_id'=>$object_id, 'url'=>$name, 'main'=>$main, 'sort'=>0])->execute();
            }
        }

        return true;
    }

    public function remove() {
        $path = $this->object[$this->type];

        $file = $path.$this->object_id.'/file/'.$this->url;

        if (is_file($file)) {
            unlink($file);
        }

        return $this->delete();
    }
}
