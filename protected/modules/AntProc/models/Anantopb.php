<?php
/**
*爬虫AJAX操作类
*@author JelCore
*@version 2014-02-24
*/
?><?php
class Anantopb
{
	private $db_ant = null;//爬虫DB模型对象

	public function __construct()
	{
		$this->db_ant = new AnAnt();
	}

	/**
	*
	*
	*
	*/
	public function addAntMiss($data)
	{
		if(isset($data['domain']) && isset($data['url']))
		{
			$domain = $data['domain'];
			$url = $data['url'];
			if(!preg_match('/(http:\/\/)/is', $url)) return "输入的URL是非法的！请重试！";
			if(strpos($url, $domain) !== false)
			{
				if(AnAnt::model()->exists('url=:ul',array(':ul'=>$url))) return "已经存在该任务！！请重试！";
				else
				{
					$this->db_ant->domain = $domain;
					$this->db_ant->url = $url;
					$this->db_ant->addtime = date('Y-m-d H:i:s');
					if(!$this->db_ant->save()){ return "DB保存失败！请联系管理员！！";}
					else
						return true;
				}
			}
			else
			{
				return "输入的入口URL和域名不匹配！请重试！";
			}
		}
		else
		{
			return "Miss Data ! Domain and  Url is required !";
		}
	}
}
?>