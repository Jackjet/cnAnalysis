<?php
/**
*@description The Model for User operation 
*@author JelCore
*@revised 2014-01-08
*/
?><?php
class Usersop
{


	/*私有变量*/
	private $MinUNameLength = 6;//用户名最小长度
	private $MaxUNameLength = 9;//用户名最大长度
	//用户默认组别
	private $usergroupdefault = "";
	//password slat string
    private $slat = "Akr1WJB417G";

	/*私有操作对象*/
	private $backtool = null;//后台工具类
	private $db_users = null;//用户表模型对象
	/*构造器*/
	public function __construct()
	{
		$this->backtool = new BackTool();
		$this->db_users = new Users();

		$thegroup = Groups::model()->find('permission=:permi',array(':permi'=>SNORMALJ));//获取普通用户的组别
        $this->usergroupdefault = $thegroup->gid;
	}
	/************************public method*****************************/
	/**
	*按指定条件查找用户群
	*@var String $type 查询的字段;String $conditon 查询的条件
	*@return 用户群(CActiveRecordset)
	*/
	public function FindUsers($type,$condition,$optype="edit",$limit = 50)
	{
		$result = array();
		$users = null;
		$pagecount = Yii::app()->params['BackShowEditUsers'];
		if($condition === "*")//查找全部
		{
			$criteria = new CDbCriteria();
			$criteria->limit = $limit;    					//取$limit条数据，如果$limit小于0，则不作处理
			$criteria = $this->SecureFilter($criteria,$optype);//安全过滤处理
			
			/*分页处理*/
			$count = Users::model()->count($criteria);    //count() 函数计算数组中的单元数目或对象中的属性个数
			$pager = new CPagination($count);			    //分页类
			$pager->pageSize = $pagecount;                  //每页显示的行数
			$pager->applyLimit($criteria);
			$users = Users::model()->findAll($criteria);	//查询所有的数据


			/*存储分页数据*/
			$result['pager'] = $pager;
		}
		else
		{
			

			/*定制查询*/
			$criteria = new CDbCriteria();
			$criteria->addSearchCondition($type,$condition);
			$criteria->limit = $limit;    					//取$limit条数据，如果$limit小于0，则不作处理
			$criteria = $this->SecureFilter($criteria,$optype);//安全过滤处理

			/*分页处理*/
			$count = Users::model()->count($criteria);    //count() 函数计算数组中的单元数目或对象中的属性个数
			$pager = new CPagination($count);			    //分页类
			$pager->pageSize = $pagecount;                  //每页显示的行数
			$pager->applyLimit($criteria);
			$users = Users::model()->findAll($criteria);	//查询所有的数据


			/*存储分页数据*/
			$result['pager'] = $pager;
		}
		
		$result['users'] = $users;
		return $result;//返回结果

	}

	/**
	*按给定ID查找用户提供编辑
	*@var Integer $uid 用户ID
	*@return 特定用户(CActiveRecordset)
	*/
	public function GetUserByID($uid)
	{
		if($uid === null || $uid === 0)
		{
			return null;
		}
		$theuser = Users::model()->findBySql("SELECT uid,name,phone,address FROM an_users WHERE uid = :uid",array(':uid'=>$uid));
		return $theuser;
	}


	/**
	*或者特定ID的用户名
	*@var Integer $uid 用户ID
	*/
	public function GetNameByID($uid)
	{
		$theuser = Users::model()->find('uid=:uid',array(':uid'=>$uid));
		$name    = $theuser->name;
		return $name;
	}

	/**
	*添加一个用户
	*@var Array $info 用户数据
	*@return boolean true if succeed;String $errorcode if failed
	*/
	public function NewUser($info)
	{
		if($this->backtool->checkUserAccess(RSYSJ) || $this->backtool->checkUserAccess(RADMINJ))
		{
			if($this->checkRegInfo($info) === true)
			{
				$this->db_users->username = $info['username'];
				$this->db_users->email    = $info['email'];
				$this->db_users->passwd = $this->encrypt($info['passwd']);
				$this->db_users->group = $this->usergroupdefault;
				$this->db_users->regtime = date('Y-m-d H:i:s');
				$this->db_users->save();
				return true;
			}
			else
			{
				return ($errorcode = $this->checkRegInfo($info));
			}
		}
		else
		{
			return ($errorcode = "You Have No Rights To This Operation !");
		}
	}

	/**
	*更新用户信息
	*@var array $info 用户信息
	*@
	*
	*/
	public function UpdateUser($info)
	{
		if($this->backtool->checkUserAccess(RSYSJ) || $this->backtool->checkUserAccess(RADMINJ))
		{
			$this->db_users = Users::model()->find('uid=:uid',array(':uid'=>$info['uid']));
			$uname = $this->db_users->username;
			$condn = 'username';

			$username = $info['name'];
			$userphone = $info['phone'];
			$useraddress = $info['address'];
			if($username != null && $username != '')
			{
				if(mb_strlen($username) > 40)
					return "姓名超过40个字！！请重试！！！";
			}
			if($userphone != '' && $userphone != null)
			{
				if(!preg_match("/^13[0-9]{1}[0-9]{8}$|15[0189]{1}[0-9]{8}$|189[0-9]{8}$/",$userphone))
				return "手机号码无效！！请重试！！！";
			}
			if($useraddress != null && $useraddress != '')
			{
				if(mb_strlen($useraddress) > 40)
					return "地址超过256个字！！请重试！！！";
			}



			$this->db_users->name = ($username === null || $username === '')?'':$username;
			$this->db_users->phone = ($userphone === null || $userphone === '')?'':$userphone;;
			$this->db_users->address = ($useraddress === null || $useraddress === '')?'':$useraddress;;

			$result = $this->db_users->save();
			if($result)
			{
				$this->backtool->saveSearchData('searchdata',$uname);				//记录用户搜索历史
				$this->backtool->saveSearchData('searchtype',$condn);
				return $result;
			}
			else
				return "系统错误！DB保存失败！请联系管理员！";
		}
		else
		{
			return '权限验证失败！可能是您没有该操作权限！';
		}
	}

	/**
	*安全操作过滤
	*@var $handel 待处理的CDbCriteria;String $optype 目标操作类型
	*@return CDbCriteria $criteria 处理过后的CDbCriteria
	*/
	public function SecureFilter($handel,$optype='edit')
	{
		if($handel === null)
		{
			return false;
		}
		if($this->backtool->checkUserAccess(RSYSJ))//具有系统管理员权限，返回所有结果
		{
			if($optype == 'edit')
			return $handel;
			if($optype == 'delete')//自己不能删除自己
			{
				$uid = $this->backtool->getUID();
				$handel->addNotInCondition('uid', array($uid));
				return $handel;
			}
			
		}
		if($this->backtool->checkUserAccess(RADMINJ))//普通管理员权限，过滤系统管理员信息和自己的信息
		{
			if($optype == 'edit')
			{
				$handel->addNotInCondition('group', array(RSYSJ));
				return $handel;
			}
			if($optype == 'delete')//自己不能删除自己
			{
				$uid = $this->backtool->getUID();
				$handel->addNotInCondition('group', array(RSYSJ));
				$handel->addNotInCondition('uid', array($uid));
				return $handel;
			}
			
		}
	}




	/*******************************************私有方法*******************************************************/

	/**
	*校验用户的注册信息是否有效
	*@var array $info
	*@return boolean true if valid;String $errorcode if invalid
	*/
	public function checkRegInfo($info)
	{
		if(isset($info['username'])&&isset($info['email'])&&isset($info['passwd'])&&isset($info['repasswd']))
		{
			if(!preg_match('/^[a-z\d_]{6,11}$/i', $info['username']))                  //检测用户名为字母和数字下划线组合长度6-11
			{
				return ($errorcode = "用户名无效！");
			}
			if(!preg_match('/^[a-z\d_]{6,15}$/i', $info['passwd']))                    //检测密码为字母和数字下划线组合长度6-15
			{
				return ($errorcode = "密码无效");
			}
			if($this->backtool->validEmail($info['email']))
			{
				if($info['passwd'] !== $info['repasswd'])
				{
					return ($errorcode = "两次密码不匹配！");
				}
				return true;	
			}
			else
			{
					return ($errorcode = "Email 无效！");
			}
		}
		else
		{
			return ($errorcode = "请将必填内容填写！再重试！");
		}
	}

	 /**
     * Using to encrypt password
     * @author gg
     * @return
     * md5 string : success
     * false : password is null
     */
    private  function encrypt($pw)
    {
        if ($pw != "" )
        {
            $pw = md5(md5($pw).$this->slat);
            return $pw;
        }
        else return false;
    }

}
?>