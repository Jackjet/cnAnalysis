<?php

class DefaultController extends Controller
{
	private $request = null;//客户端请求
	private $antopb = null;//数据操作模型

	private function InitAn()
	{
		$this->request = Yii::app()->request;
		$this->antopb  = new Anantopb();
	}
	public function actionIndex()
	{
		$this->InitAn();

		$newdomain = $this->request->getParam('newdomain');
		$newurl    = $this->request->getParam('newurl');

		$result = $this->antopb->addAntMiss(array('domain'=>$newdomain,'url'=>$newurl));

		if($result === true )
		{
			$thejson = json_encode(array('flag'=>true,'berror'=>""));
		}
		else
		{
			$thejson = json_encode(array('flag'=>false,'berror'=>$result));
		}

		$this->render('index',array('thejson'=>$thejson));
	}
}