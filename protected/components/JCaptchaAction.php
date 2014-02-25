<?php
/**
*继承CCaptchaAction实现扩展功能
*@author JelCore
*@revised 2013-12-30
*/
?><?php
class JCaptchaAction extends CCaptchaAction
{
	private $sessionidex = 'vcode';//session中存放验证码的索引

	/**
	 * 重载CCaptchaAction run方法
	 */
	public function run()
	{
		if(isset($_GET[parent::REFRESH_GET_VAR]))  // AJAX request for regenerating code
		{
			$code=parent::getVerifyCode(true);
			echo CJSON::encode(array(
				'hash1'=>parent::generateValidationHash($code),
				'hash2'=>parent::generateValidationHash(strtolower($code)),
				// we add a random 'v' parameter so that FireFox can refresh the image
				// when src attribute of image tag is changed
				'url'=>parent::getController()->createUrl(parent::getId(),array('v' => uniqid())),
			));
		}
		else
			parent::renderImage(parent::getVerifyCode());

		$_SESSION[$this->sessionidex] = parent::getVerifyCode();//将验证码装入session

		Yii::app()->end();
	}
}
?>