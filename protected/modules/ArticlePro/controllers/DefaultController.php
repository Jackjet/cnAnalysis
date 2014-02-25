<?php
/**
*提供文章的管理AJAX支持
*@author JelCore
*@version 2014-01-10
*/
class DefaultController extends Controller
{
	/*private object*/
	private $request = null;//客户端请求

	private $artopb  = null;//文章操作对象

	/*private method*/


	/**
	*TO DO the initializing jobs
	*
	*/
	private function Init_()
	{
		$this->request = Yii::app()->request;
		$this->artopb = new Articleopb();
	}


	public function actionIndex()
	{
		$this->Init_();
		$thejson = "";
		if(Yii::app()->user->checkAccess(RADMINJ) || Yii::app()->user->checkAccess(RSYSJ)||Yii::app()->user->checkAccess(RNORMALJ))
		{
			if(isset($_POST['ajax']))
			{
				if($this->request->getParam('ajax') === 'editpost')
				{
					$info = array();
					$info['aid'] = $this->request->getParam('back_aid');
					$info['title'] = $this->request->getParam('back_title');
					$info['from'] = $this->request->getParam('back_from');
					$info['fromlink'] = $this->request->getParam('back_fromlink');
					$info['author'] = $this->request->getParam('back_author');
					$info['typeid']   = $this->request->getParam('back_typeid');
					$info['content'] = $this->request->getParam('back_editor_id');
					$result = $this->artopb->updateArticle($info);
					if( $result === true)
					{
						$thejson = json_encode(array('flag'=>true,'berror'=>""));
					}
					else
					{
						$thejson = json_encode(array('flag'=>false,'berror'=>$result));
					}
				}
				else if($this->request->getParam('ajax') === 'dopass')
				{
					if(Yii::app()->user->checkAccess(RADMINJ) || Yii::app()->user->checkAccess(RSYSJ))
					{
						$info = array();
						$info['aid'] = $this->request->getParam('back_aid');
						$info['dotype'] = 'dopass';
						$result = $this->artopb->doVerify($info);
						if($result === true)
						{
							$thejson = json_encode(array('flag'=>true,'berror'=>""));
						}
						else
						{
							$thejson = json_encode(array('flag'=>false,'berror'=>$result));
						}

					}
					else
					{
						$thejson = json_encode(array('flag'=>false,'berror'=>"You Have No Rights To This Operation !"));
					}
					
				}
				else if($this->request->getParam('ajax') === 'donopass')
				{
					if(Yii::app()->user->checkAccess(RADMINJ) || Yii::app()->user->checkAccess(RSYSJ))
					{
						$info = array();
						$info['aid'] = $this->request->getParam('back_aid');
						$info['dotype'] = 'donopass';
						$result = $this->artopb->doVerify($info);
						if($result === true)
						{
							$thejson = json_encode(array('flag'=>true,'berror'=>""));
						}
						else
						{
							$thejson = json_encode(array('flag'=>false,'berror'=>$result));
						}

					}
					else
					{
						$thejson = json_encode(array('flag'=>false,'berror'=>"You Have No Rights To This Operation !"));
					}
					
				}
				else if($this->request->getParam('ajax') === 'dodelete')
				{
					if(Yii::app()->user->checkAccess(RADMINJ) || Yii::app()->user->checkAccess(RSYSJ))
					{
						$info['aid'] = array();
						$info['aid'] = $this->request->getParam('back_aid');
						$result = $this->artopb->deleteArticle($info);
						
						if($result['artdel'] && $result['filedel'])
						{
							$thejson = json_encode(
													array(
															'flag'=>true,'berror'=>"",
												            'backUrl'=>Yii::app()->createUrl('backAn/ResManage',array('tid'=>'NonVerifyedArticles')),
												            'addinfo' => '文章删除成功！' . $result['successcode'],
												    	)
												);
						}
						else if($result['artdel'] && !$result['filedel'])
						{
							$thejson = json_encode(
													array(
															'flag'=>true,'berror'=>"",
												            'backUrl'=>Yii::app()->createUrl('backAn/ResManage',array('tid'=>'NonVerifyedArticles')),
												            'addinfo' => '文章删除成功！'
												    	)
												  );
						}
						else
						{

							$thejson = json_encode(array('flag'=>false,'berror'=>$result['errorcode']));
						}
					}
					else
					{
						$thejson = json_encode(array('flag'=>false,'berror'=>"You Have No Rights To This Operation !"));
					}
				}
				else
				{
					$thejson = json_encode(array('flag'=>false,'berror'=>"Operation Undefined !"));
				}
			}
			else
			{
				$thejson = json_encode(array('flag'=>false,'berror'=>"Operation Parameter Unset !"));
			}
		}
		else
		{
			$thejson = json_encode(array('flag'=>false,'berror'=>"Access Forbidden !"));
		}






		$this->render('index',array('json'=>$thejson));
	}
}
?>