<?php
/**
*@description The Model for File operation backstage
*@author JelCore
*@revised 2014-01-17
*/
?><?php
class Filesopb
{
	/*private field*/
	private $db_file = null;
	private $db_updatefile = null;//solr全文搜索检测表操作文件模型
	private $db_save_path = "";
	public function __construct()
	{
		$this->db_file = new Files();
		$this->hostpath = Yii::app()->request->hostInfo;
		$this->db_updatefile = new Updatefiles();
	}


	/**********************************************************************************************************/
	/**
	*删除服务器上与文件相关的附件
	*@var Integer $aid 文章ID
	*@return boolean true if succeed else false if failed or no files; String $errorcode if there are errors
	*
	*/
	public function deleteFiles($aid)
	{	
		if(!isset($aid) || empty($aid)) return $errorcode = "The Post ID Invalid !";
		if(Files::model()->count('aid=:aid',array(':aid'=>$aid)) > 0)
		{
			$files = Files::model()->findAll('aid=:aid',array(':aid'=>$aid));
			foreach($files as $key => $onefile)
			{
				$fid = $onefile->fid;
				$des = str_replace($this->hostpath, $_SERVER['DOCUMENT_ROOT'], $onefile->filepath);//URL转换为本地路径
				if(file_exists($des))							    //保证文件夹有操作权限		
				{
   					if(unlink($des))                                //删除文件
   					{
   						$this->db_file = Files::model()->findByPk($fid);
  						$this->db_file->delete();

  						$this->db_updatefile = Updatefiles::model()->find('fid=:fid',array(':fid'=>$fid));
  						if($this->db_updatefile)
  						{
  							$udid = $this->db_updatefile->udid;
  							$res  = Updatefiles::model()->findByPk($udid);
  							if($res)$res->delete();
  						}
   					}
   					
  				}
  				
			}
			return true;
		}
		else
		{
			return false;
		}
		
	}
}
?>