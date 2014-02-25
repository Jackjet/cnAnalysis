<?php

class User {
    private $db_user;
    //password slat string
    private $slat = "Akr1WJB417G";

    public function User(){
        $this->init();
    }
    public function init(){
        $this->db_user = new Users();
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


    /**
     * check if the username is registered
     * @param $userName
     * @return bool
     * true : unregistered
     * false : registered
     */
    public function checkUsername($userName){
        $rtn = $this->db_user->model()->exists('username=:username',array(':username'=>$userName));
        return !$rtn;
    }


    /**
     * check if the email is registered
     * @param $email
     * @return bool
     * true : unregistered
     * false : registered
     */
    public function checkEmail($email){
        $rtn = $this->db_user->model()->exists('email=:email',array(':email'=>$email));
        return !$rtn;
    }


    public function reg($userInfoArr){
        if (!isset($userInfoArr['username'],$userInfoArr['passwd'],$userInfoArr['email'],$userInfoArr['group'])) return false;
        if (!($this->checkUsername($userInfoArr['username'])and($this->checkEmail($userInfoArr['email'])))) return false;
        $dbConn = new Users();
        $dbConn->username = $userInfoArr['username'];
        $dbConn->passwd = $this->encrypt($userInfoArr["passwd"]);
        $dbConn->email = $userInfoArr["email"];
        $dbConn->regtime = date("Y-m-d H:i:s");
        $dbConn->group = $userInfoArr["group"];
        if (isset($userInfoArr["name"])) $dbConn->name = $userInfoArr["name"];
        if (isset($userInfoArr["phone"])) $dbConn->phone = $userInfoArr["phone"];
        if (isset($userInfoArr["address"])) $dbConn->address = $userInfoArr["address"];

        return $dbConn->save();
    }

    public function loginUsername($userName,$passWD){
        $passWD = $this->encrypt($passWD);
        return $this->db_user->model()->exists("username=:username and passwd=:passwd",array(':username'=>$userName,
                "passwd"=>$passWD));
    }

    public function loginEmail($email,$passWD){
        $passWD = $this->encrypt($passWD);
        return $this->db_user->model()->exists("email=:email and passwd=:passwd",array(':email'=>$email,
            "passwd"=>$passWD));
    }

    public function updateInfo($userInfoArr){
        if (!isset($userInfoArr["uid"])) return false;
        if ((isset($userInfoArr["passwd"]))and($userInfoArr["passwd"] == "")) unset($userInfoArr["passwd"]);
        if (!($this->db_user->model()->exists("uid=:uid",array(":uid"=>$userInfoArr["uid"])))) return false;
        $updater = array("group","passwd","name","phone","address","lastlogin","lastip",);
        $updatearr = array();
        $update = array();
        foreach ($updater as $value)
            if (isset($userInfoArr[$value])){array_push($update,$value);$updatearr[$value]=$userInfoArr[$value];}
        if (isset($updatearr["passwd"])) $updatearr["passwd"] = $this->encrypt($updatearr["passwd"]);
        if (count($update)==0) return false;
        if (!($this->db_user->model()->exists("uid=:uid",array(":uid"=>$userInfoArr["uid"])))) return false;
        if ($this->db_user->model()->updateByPk($userInfoArr["uid"],$updatearr)==1)return true;
        else return false;
    }


    public function deleteUser($uid){
        if (!($this->db_user->model()->exists("uid=:uid",array(":uid"=>$uid)))) return false;
        return $this->db_user->model()->deleteByPk($uid);
    }

    public function  getInfo($userInfo){
        if (isset($userInfo["uid"])){
            if (!($this->db_user->model()->exists("uid=:uid",array(":uid"=>$userInfo["uid"])))) return false;
            $result = $this->db_user->model()->find("uid=:uid",array(":uid"=>$userInfo["uid"]))->attributes;
            unset($result["passwd"]);
            return $result;
        }
        elseif (isset($userInfo["username"])){
            if (!($this->db_user->model()->exists("username=:username",array(":username"=>$userInfo["username"])))) return false;
            $result = $this->db_user->model()->find("username=:username",array(":username"=>$userInfo["username"]))->attributes;
            unset($result["passwd"]);
            return $result;
        }
        elseif (isset($userInfo["email"])){
            if (!($this->db_user->model()->exists("email=:email",array(":email"=>$userInfo["email"])))) return false;
            $result = $this->db_user->model()->find("email=:email",array(":email"=>$userInfo["email"]))->attributes;
            unset($result["passwd"]);
            return $result;
        }
    }

    public function checkPasswd($uid, $oldPasswd){
        $result = $this->db_user->model()->find(array(
            "select"=>"passwd",
            "condition"=>"uid=:uid",
            "params"=>array(":uid"=>$uid),
        ))->attributes;
        if ($result["passwd"]==$this->encrypt($oldPasswd)) return true;
        else return false;
    }
    
    /**
    *@description 根据user id 获得 username
    *@author JelCore
    *@revised 2013-12-28
    */
    public function GetUNameByUID($uid = 1)
    {
        $username = an_users::model()->find('uid=:uid', array(':uid'=>$uid))->username;
        return $username;
    }
} 