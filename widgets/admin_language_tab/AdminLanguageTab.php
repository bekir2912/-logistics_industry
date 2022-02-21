<?php 
namespace app\widgets\admin_language_tab;

use Yii;
use yii\bootstrap\Widget;

use app\models\User;

class AdminLanguageTab extends Widget{
	public function init() {}

	public function run() {
		return $this->render('admin-language-tab');
	}
}
?>