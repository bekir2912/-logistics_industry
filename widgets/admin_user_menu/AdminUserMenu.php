<?php 
namespace app\widgets\admin_user_menu;

use Yii;
use yii\bootstrap\Widget;

use app\models\user\User;

class AdminUserMenu extends Widget{
	public function init() {}

	public function run() {
		$user = Yii::$app->user->identity;

		$model = User::find()->with('image')->where(['id'=>Yii::$app->request->get('id')])->one();

		return $this->render('admin-user-menu', [
			'model' => $model,
			'user' => $user
		]);
	}
}
?>