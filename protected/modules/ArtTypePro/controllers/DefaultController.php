<?php
/**
*类别维护的AJAX支持控制器
*@author JelCore
*@version 2014-01-30
*/
class DefaultController extends Controller
{
	/*priavte var*/
	private $arttypeopb    = null;//文章类别操作模型对象
	private $request;//客户端请求 YII封装

	private function _InitializeJ()
	{
		$this->request = Yii::app()->request;
		$this->arttypeopb = new ArtTypeopb();
	}
	public function actionIndex()
	{
		$theJson = json_encode(array());
		if(!Yii::app()->user->checkAccess(RADMINJ))//只有系统管理员可以维护类别
		{
			$theJson = json_encode(array('flag'=>false,'berror'=>'非法的请求！你无权进行这项操作！'));
		}
		else
		{
			$this->_InitializeJ();

			if(!isset($_POST['ajax'])) {exit('Access Ilegal!');}
			else
			{
				$theajax = $this->request->getParam('ajax');

				if($theajax == 'edittype')
				{
					$tid   = $this->request->getParam('typeid');
					$tname = $this->request->getParam('typename');
					$result = $this->arttypeopb->doEditType($tid,$tname);
					if($result === true)
					{
						$theJson = json_encode(array('flag'=>true,'berror'=>''));
					}
					else
						$theJson = json_encode(array('flag'=>false,'berror'=>$result));
				}
				else if($theajax == 'addtype')
				{
					$newtid   = $this->request->getParam('newtypeid');
					$newtname = $this->request->getParam('newtypename');
					$ptid     = $this->request->getParam('parenttypeid');
					$result = $this->arttypeopb->doAddNewType($newtid,$newtname,$ptid);
					if($result === true)
					{
						$theJson = json_encode(array('flag'=>true,'berror'=>''));
					}
					else
					{
						$theJson = json_encode(array('flag'=>false,'berror'=>$result));
					}
				}
				else if($theajax == 'deletetype')
				{
					$tid = $this->request->getParam('typeid');
					$ptid = $this->request->getParam('parenttypeid');

					$result = $this->arttypeopb->doDeleteType($tid,$ptid);

					if($result === true)
					{
						$theJson = json_encode(array('flag'=>true,'berror'=>''));
					}
					else
					{
						$theJson = json_encode(array('flag'=>false,'berror'=>$result));
					}
				}
				else if($theajax == 'getnewtypeid')
				{
					$newtypeid = $this->arttypeopb->getNewTypeID();

					$theJson = json_encode(array('flag'=>true,'berror'=>'','newtid'=>$newtypeid));
				}
				else if($theajax == 'getpostsnum')
				{
					$tid  = $this->request->getParam('typeid');

					$result = $this->arttypeopb->getPostsNum($tid);
					if($result['sign'] === true)
					{
						$potsnum = $result['postsnum'];
						$theJson = json_encode(array('flag'=>true,'berror'=>'','postsnum'=>$potsnum));
					}
					else
					{
						$theJson = json_encode(array('flag'=>false,'berror'=>$result['sign']));
					}
					

				}
				else
				{
					$theJson = json_encode(array('flag'=>false,'berror'=>'未定义的操作！'));
				}
			}
		}
		
		$this->render('index',array('theJson'=>$theJson));
	}
}