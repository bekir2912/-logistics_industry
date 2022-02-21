<?php 
namespace app\widgets\admin_shipment_menu;

use Yii;
use yii\bootstrap\Widget;

use app\models\shipment\Shipment;

class AdminShipmentMenu extends Widget{
	public function init() {}

	public function run() {
		$user = Yii::$app->user->identity;

		$model = Shipment::findOne(Yii::$app->request->get('id'));

		return $this->render('admin-shipment-menu', [
			'model' => $model,
			'user' => $user
		]);
	}
}
?>