<?php

namespace app\models\industry\handbook;

use app\models\gp\Gp;
use app\models\industry\AllDeffect;
use app\models\industry\BufferZone;
use app\models\industry\DepartmentElectro;
use app\models\industry\DepartmentMechanical;
use app\models\industry\DepartmentPaiting;
use app\models\industry\DepartmentPlastic;
use app\models\industry\DepartmentSizing;
use app\models\industry\DepartmentStamping;
use app\models\industry\DepartmentTest;
use Yii;
use yii\base\BaseObject;
use yii\web\UploadedFile;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;


class BDepartment extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'b_departments';
    }


    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                // если вместо метки времени UNIX используется datetime:
                // 'value' => new Expression('NOW()'),
            ],
        ];
    }



    public function rules()
    {
        return [
            [['name_ru'], 'required'],
            [['status', 'sort', 'created_at', 'updated_at'], 'integer'],
            [['name_ru', 'name_en', 'name_uz'], 'string', 'max' => 255],
        ];
    }


    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name_ru' => Yii::t('app', 'Название Ru'),
            'name_en' => Yii::t('app', 'Название En'),
            'name_uz' => Yii::t('app', 'Название Uz'),
            'status' => Yii::t('app', 'Статус'),
            'sort' => Yii::t('app', 'Сортировака'),
            'created_at' => Yii::t('app', 'Дата создания'),
            'updated_at' => Yii::t('app', 'Дата обновления'),
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
                $image->uploadPhoto($this->id, 'gp');
            }

            return true;
        }

        return false;
    }

    public function getBufferZoneFrom()
    {
        return $this->hasMany(BufferZone::className(), ['from_department_id' => 'id']);
    }

    public function getBufferZoneTo()
    {
        return $this->hasMany(BufferZone::className(), ['to_department_id' => 'id']);
    }

    public function getBDeffect()
    {
        return $this->hasMany(BDeffect::className(), ['department_id' => 'id']);
    }

    public function getAllDeffect()
    {
        return $this->hasMany(AllDeffect::className(), ['department_id' => 'id']);
    }

    public function getBDepartmentElectros()
    {
        return $this->hasMany(DepartmentElectro::className(), ['department_id' => 'id']);
    }

    public function getBDepartmentGps()
    {
        return $this->hasMany(Gp::className(), ['department_id' => 'id']);
    }

    public function getBDepartmentMechanicals()
    {
        return $this->hasMany(DepartmentMechanical::className(), ['department_id' => 'id']);
    }

    public function getBDepartmentPaitings()
    {
        return $this->hasMany(DepartmentPaiting::className(), ['department_id' => 'id']);
    }

    public function getBDepartmentSizings()
    {
        return $this->hasMany(DepartmentSizing::className(), ['department_id' => 'id']);
    }

    public function getBDepartmentStampings()
    {
        return $this->hasMany(DepartmentStamping::className(), ['department_id' => 'id']);
    }
    public function getBDepartment()
    {
        return $this->hasMany(DepartmentPlastic::className(), ['department_id' => 'id']);
    }

    public function getBDepartmentTests()
    {
        return $this->hasMany(DepartmentTest::className(), ['department_id' => 'id']);
    }

    /**
     * Gets query for [[BDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBDetails()
    {
        return $this->hasMany(BDetails::className(), ['department_id' => 'id']);
    }

    /**
     * Gets query for [[BLines]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBLines()
    {
        return $this->hasMany(BLine::className(), ['department_id' => 'id']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['department_id' => 'id']);
    }
}
