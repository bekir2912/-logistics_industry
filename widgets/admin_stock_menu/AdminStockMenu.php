<?php 
namespace app\widgets\admin_stock_menu;

use Yii;
use yii\bootstrap\Widget;

use app\models\stock\Stock;

class AdminStockMenu extends Widget{
	public function init() {}

	public function run() {
		$user = Yii::$app->user->identity;

		$model = Stock::find()->where(['id'=>Yii::$app->request->get('id')])->one();

		return $this->render('admin-stock-menu', [
			'model' => $model,
			'user' => $user
		]);
	}
}
?>