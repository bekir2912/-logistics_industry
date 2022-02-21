<?php 
namespace app\widgets\admin_menu;

use Yii;
use yii\bootstrap\Widget;

use app\models\user\User;

class AdminMenu extends Widget{
	public function init() {}

	public function run() {
		$model = User::find()->with('image')->where(['id'=>Yii::$app->user->identity->id])->one();
		
		return $this->render('admin-menu', [
			'model' => $model
		]);
	}
}
?>