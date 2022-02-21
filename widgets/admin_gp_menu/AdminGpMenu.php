<?php 
namespace app\widgets\admin_gp_menu;

use Yii;
use yii\bootstrap\Widget;

use app\models\gp\Gp;

class AdminGpMenu extends Widget{
	public function init() {}

	public function run() {
		$user = Yii::$app->user->identity;

		$model = Gp::find()->with('image')->where(['id'=>Yii::$app->request->get('id')])->one();

		return $this->render('admin-gp-menu', [
			'model' => $model,
			'user' => $user
		]);
	}
}
?>