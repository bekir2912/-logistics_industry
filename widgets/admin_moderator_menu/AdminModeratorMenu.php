<?php 
namespace app\widgets\admin_moderator_menu;

use Yii;
use yii\bootstrap\Widget;

use app\models\user\User;

class AdminModeratorMenu extends Widget{
	public function init() {}

	public function run() {
		$model = User::find()->with('image')->where(['id'=>Yii::$app->request->get('id')])->one();

		return $this->render('admin-moderator-menu', [
			'model' => $model
		]);
	}
}
?>