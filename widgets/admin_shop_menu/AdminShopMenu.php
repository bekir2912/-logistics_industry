<?php 
namespace app\widgets\admin_shop_menu;

use Yii;
use yii\bootstrap\Widget;

use app\models\shop\Shop;
use app\models\shipment\Shipment;

class AdminShopMenu extends Widget{
	public function init() {}

	public function run() {
		$user = Yii::$app->user->identity;

		$model = Shop::findOne(1);

		$shipments = Shipment::find()->where(['status'=>0])->count();

		return $this->render('admin-shop-menu', [
			'model' => $model,
			'user' => $user,
			'shipments' => $shipments
		]);
	}
}
?>