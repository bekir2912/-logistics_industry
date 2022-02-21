<?php 
namespace app\widgets\admin_product_menu;

use Yii;
use yii\bootstrap\Widget;

use app\models\product\Product;

class AdminProductMenu extends Widget{
	public function init() {}

	public function run() {
		$user = Yii::$app->user->identity;

		$model = Product::find()->with('image')->where(['id'=>Yii::$app->request->get('id')])->one();

		return $this->render('admin-product-menu', [
			'model' => $model,
			'user' => $user
		]);
	}
}
?>