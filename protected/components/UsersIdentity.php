<?php
/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 *@author JelCore
 *@revised 2014-01-04
 */
class UsersIdentity extends CUserIdentity
{
	private $_id; //用户ID 

	//password slat string
    private $slat = "Akr1WJB417G";


	/**
	*构造器 初始化验证数据
	*@var String $username 用户名或者用户邮箱
	*@var String $password 密码
	*/

	public function __construct( $username , $password )
	{
		parent::__construct( $username, $password );
	}






    public function authenticate()
    {
        $record = Users::model()->findByAttributes(array('username'=>$this->username));
        if($record===null)
            $this->errorCode=self::ERROR_USERNAME_INVALID;
        else if($record->passwd!==$this->encrypt($this->password))
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
        else
        {
            $this->_id=$record->uid;
            $this->setState('group', $record->group);
            $this->errorCode=self::ERROR_NONE;
        }
        return !$this->errorCode;
    }
 
    public function getId()
    {
        return $this->_id;
    }




    /**
     * Using to encrypt password
     * @return
     * md5 string : success
     * false : password is null
     */
    private  function encrypt($pw){
        global $slat;
        if ($pw != "" ){
            $pw = md5(md5($pw).$this->slat);
            return $pw;
        }
        else return false;
    }
}
?>