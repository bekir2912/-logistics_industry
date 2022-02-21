<?php 
namespace app\widgets\admin_report_menu;

use Yii;
use yii\bootstrap\Widget;

use app\models\report\Report;

class AdminReportMenu extends Widget{
	public function init() {}

	public function run() {
		$user = Yii::$app->user->identity;
		$action = Yii::$app->controller->action->id;
		return $this->render('admin-report-menu', [
			'user' => $user,
			'action' => $action
		]);
	}
}
?>