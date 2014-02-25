<?php
/**
*@description The Model for File Upload operation 
*@author JelCore
*@revised 2014-01-11
*/
?><?php
class Filesop
{
	/*私有属性*/
	private $db_anfile     = null;//文件数据库对象
	private $db_updatefile = null;//solr服务DB操作对象-文件
	private $db_updateart  = null;//solr服务DB操作对象-文章
	private $db_article    = null;//文章数据库对象
	private $usersop       = null;//用户操作对象
	private $backtool      = null;//后台工具对象

	private $extArr = array();//支持的文件类型
	private $maxSize = 0;  //大小限制

	private $savePathPrefix = "";//本机存储路径前缀
	private $savePathPrefixDB = "";//数据库存储路径前缀
	/*构造和析构器*/
	public function __construct(  )
	{
		$this->db_anfile  = new AnFiles();
		$this->db_article = new Article();
		$this->db_updateart = new Updatearticles();
		$this->usersop    = new Usersop();
		$this->backtool   = new BackTool();
		$this->db_updatefile = new Updatefiles();

		$this->maxSize = Yii::app()->params['FileAllowSize'];
		$this->extArr = Yii::app()->params['FileAllowType'];
		$this->savePathPrefix = Yii::app()->params['FileSavePathPrefixAbs'];
		$this->savePathPrefixDB = Yii::app()->request->hostInfo.str_replace('index.php','',Yii::app()->baseUrl) . '/uploadfiles/';//配置上传文件资源URL
 	}

 	/**
 	*存储新文件
 	*@var CUploadedFile[] $files 上传的文件组;array $info 附属参数
 	*@return boolean true if succeed;String $errorcode if failed
 	*
 	*/
 	public function doUpload($files,$info)
 	{
 		$fid = 0;//文件ID
 		$newAID = 0;//文章ID
 		$fcount = 1;//附件序号
 		$saveContent = "";//文件所属文章的内容
 		if(count($files) == 0)
 		{
 			return $errorcode = "文件上传内容为空！请检查！";
 		}
 		else
 		{
 			if(!isset($info['title'])||!isset($info['from'])||!isset($info['fromlink'])||!isset($info['author'])||!isset($info['typeid']))
 			{
 				return $errorcode = "必填项没有填写完整！请检查！";
 			}
 			else
 			{
 				if(empty($info['title'])||empty($info['from'])||empty($info['fromlink'])||empty($info['author'])||empty($info['typeid']))
 				{
 					return $errorcode = "必填项有空值！请检查！";
 				}
 				else
 				{	
 					$criteria=new CDbCriteria;
					$criteria->select='max(aid) AS maxColumn';
					$row = Article::model()->find($criteria);
					$newAID = $row->maxColumn;
					$newAID++;

 					$this->db_article->aid = $newAID;
 					$this->db_article->uid = $this->backtool->getUID();
 					$this->db_article->title = $info['title'];
 					$this->db_article->from  = $info['from'];
 					$this->db_article->uploadtime  = $info['uploadtime'];
 					$this->db_article->fromlink  = $info['fromlink'];
 					$this->db_article->author  = $info['author'];
 					$this->db_article->isallow = 0;
 					$this->db_article->typeid  = $info['typeid'];
 					$this->db_article->point  = 0;
 					$this->db_article->link = Yii::app()->createUrl('homeAn/ReadPost',array('pid'=>$newAID));

 					$this->db_updateart->aid = $newAID;
 					$this->db_updateart->issucc = 0;

 				}
 			}
 			$filesarr = array();
 			if(count($files) == 1)$filesarr[] = $files;
 			foreach ($filesarr as $key => $onefile) 
 			{

 				if($files->size > $this->maxSize)return $errorcode = "文件大小超过限制！请检查！";
 				if(!$this->checkFileExt($onefile))return $errorcode = "此类文件不支持上传！请检查！";
 				if(AnFiles::model()->count() == 0)
 				{
 					$fid = 1;
 				}
 				else
 				{
 					$criteria=new CDbCriteria;
					$criteria->select='max(fid) AS maxColumn';
					$row = AnFiles::model()->find($criteria);
					$fid = $row->maxColumn;
					$fid++;
 				}
 				$doctype = $onefile->extensionName;
 				
 				$Loca = $this->newDir($doctype);


 				$newnameLC = $Loca.date('YmdHis')."$fid".'.'.$doctype;												 //服务器存储目录
 				$newnameDB = $this->savePathPrefixDB.$doctype.'/'.date('Ymd').'/'.date('YmdHis')."$fid".'.'.$doctype;//数据库存储URL
 				

 				$this->db_anfile->fid = $fid;
 				$this->db_anfile->filepath = $newnameDB;
 				$this->db_anfile->filename = $onefile->name;
 				$this->db_anfile->fileext = $doctype;
 				$this->db_anfile->filesize = $onefile->size;
 				$this->db_anfile->uploadtime = date('Y-m-d H:i:s');
 				$this->db_anfile->uploader   = $this->backtool->getUID();
 				$this->db_anfile->aid        = $newAID;
 				$this->db_anfile->isallow    = 0;

 				$this->db_updatefile->fid = $fid;
 				$this->db_updatefile->issucc = 0;


 				if($onefile->saveAs($newnameLC))
 				{
 					$this->db_anfile->save();
 					$this->db_updatefile->save();
 					$saveContent = $saveContent ." "."<a href=\"".$newnameDB."\">"."附件$fcount</a>";
 					$fcount++;
 				}
 				else
 				{
 					return $errorcode = "无法保存文件，请联系技术人员！";
 				}
 			}
 			$this->db_article->content = $saveContent;
 			$this->db_article->save();
 			$this->db_updateart->save();
 			return true;
 		}
 	}

 	/**
 	*检测扩展名是否符合要求
 	*@var CUploadedFile $file
 	*@return boolean true if pass;false if not pass
 	*/
 	private function checkFileExt($file)
 	{
 		foreach ($this->extArr as $key => $oneext) 
 		{
 			if($oneext == $file->extensionName) return true;
 		}
 		return false;
 	}

 	/**
 	*创建文件保存目录
 	*@var String $doctype 文件类型
 	*@return String $Loca2 or null
 	*/
 	private function newDir($doctype)
 	{
 		$Loca1 = $this->savePathPrefix.$doctype.'/';
 		$Loca2 = $Loca1.date('Ymd').'/'; //最终目录

		if(!is_dir($Loca1))     //第一步判断是否存在第一级目录
		{
			
			if(!mkdir($Loca1)) //不存在则创建第一级目录
			{
				return null;
			}
			else
			{

				if(!is_dir($Loca2))   						  //检测最终目录是否存在
				{
					if(!mkdir($Loca2))                        //不存在则创建
					return null;
				}
			}
		}
		else
		{
			if(!is_dir($Loca2))   //若存在则直接创建最终目录
			{
				if(!mkdir($Loca2))
				return null;
			}
		}
		return $Loca2;
 	}	
}
?>