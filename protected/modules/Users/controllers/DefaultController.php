<?php
/**
*用户模块控制器
*@author yangliang
*@revised 2014-01-17 by JelCore
*/
class DefaultController extends Controller
{
    private $request,$session;
    private $sessionExpire; //session生存周期
    private $userauth;      //登录处理辅助类
    private $userop;        //用户操作类对象
    private $usergroupdefault = 3;//默认注册用户角色为普通用户
    private function _init(){
        $this->request = Yii::app()->request;
        $this->session = Yii::app()->session;

        $this->userop = new User();
        $thegroup = Groups::model()->find('permission=:permi',array(':permi'=>SNORMALJ));//获取普通用户的组别
        $this->usergroupdefault = $thegroup->gid;

        $this->userauth = new UsersAuth();
        $this->sessionExpire = 60 * 60 * 24 * 7; //session生存周期为一周
    }

    private $vcode;



	public function actionIndex()
	{
        $this->_init();
        if (!isset($_POST["ajax"])){
            echo "You don't have permission to access";
        }else {
            $ajax = $this->request->getParam("ajax");

                /**
                *验证码验证
                *@author JelCore
                *@revised 2013-12-31
                */
                /*begin*/
                if($ajax == "checkvcode")
                {
                    if (isset($_POST["vcode"])) 
                    {
                        $this->vcode = $this->session['vcode'];
                        if($this->vcode == strtolower($this->request->getParam('vcode')))
                        {
                            echo json_encode(array("flag"=>true));
                        }
                        else
                        {
                            echo json_encode(array("flag"=>false));
                        }
                    }else echo json_encode(array("flag"=>false)); 
                }
                /*end*/

                if ($ajax == "loginUsername"){
                    if (isset($_POST["username"])) {
                        $username = $this->request->getParam("username");
                        $passwd = $this->request->getParam("passwd");
                        $lastip = $this->request->userHostAddress;
                        $lastlogin = date('Y-m-d H:i:s');
                        $result = $this->userop->loginUsername($username,$passwd);
                        if ($result) {
                        
                            $info = $this->userop->getInfo(array("username"=>$username));

                            $this->userop->updateLoginInfo($info["uid"],array('ip'=>$lastip,'time'=>$lastlogin));

                            $isKeep = $this->request->getParam('checkUserName');
                           
                            if($isKeep == "true")                             //记住密码使用cookie保持登录
                            {
                                $this->userauth->keepCookieJ('uid',$info["uid"]);
                                $this->userauth->keepCookieJ('username',$info["username"]);
                                //echo json_encode(array("flag"=>true));
                            }
                            else                                               //否则使用session保持会话
                            {
                                $this->session->setTimeout($this->sessionExpire);
                                $this->session["uid"] = $info["uid"];
                                $this->session["username"] = $info["username"];
                                //echo json_encode(array("flag"=>true));
                            }

                            $this->userauth->authloadUser($info["username"],$passwd);         //初始化UserIdentity

                            $theflag = $this->userauth->doAuthentication($isKeep);            //进一步验证并进行授权
                            if($theflag === true)
                            {
                                //echo Yii::app()->user->group;
                                echo json_encode(array("flag"=>true,"group" => Yii::app()->user->group,));
                            }
                            else
                            {
                                echo $theflag;
                            }          

                        }
                        else echo json_encode(array("flag"=>false));
                    }else echo json_encode(array("flag"=>false));
                }

                if ($ajax == "loginEmail"){
                    if (isset($_POST["email"])){
                        $email =$this->request->getParam("email");
                        $passwd = $this->request->getParam("passwd");
                        $lastip = $this->request->userHostAddress;
                        $lastlogin = date('Y-m-d H:i:s');
                        $result = $this->userop->loginEmail($email,$passwd);
                        if($result){
                            
                            $info = $this->userop->getInfo(array("email"=>$email));

                            $this->userop->updateLoginInfo($info["uid"],array('ip'=>$lastip,'time'=>$lastlogin));

                            $isKeep = $this->request->getParam('checkUserName');
                           
                            if($isKeep == "true")
                            {
                                $this->userauth->keepCookieJ('uid',$info["uid"]);
                                $this->userauth->keepCookieJ('username',$info["username"]);
                                //echo json_encode(array("flag"=>true));
                            }
                            else
                            {
                                $this->session->setTimeout($this->sessionExpire);
                                $this->session["uid"] = $info["uid"];
                                $this->session["username"] = $info["username"];
                                //echo json_encode(array("flag"=>true));
                            }

                            $this->userauth->authloadUser($info["username"],$passwd);         //初始化UserIdentity

                            $theflag = $this->userauth->doAuthentication($isKeep);            //进一步验证
                            if($theflag === true)
                            {
                                //echo Yii::app()->user->group;
                                echo json_encode(array("flag"=>true,"group" => Yii::app()->user->group,));
                            }
                            else
                            {
                                echo $theflag;
                            }

                        }
                        else echo json_encode(array("flag"=>false));
                    }else echo json_encode(array("flag"=>false));
                }

            if ($ajax == "checkUsername"){
                $userName = "";
                if (isset($_POST["username"])) $userName = $this->request->getParam("username");
                $result = $this->userop->checkUsername($userName);
                if ($result) echo json_encode(array("flag"=>true));
                else echo json_encode(array("flag"=>false));
            }

            if ($ajax == "checkEmail"){
                $email = "";
                if (isset($_POST["email"])) $email = $this->request->getParam("email");
                $result = $this->userop->checkEmail($email);
                if ($result) echo json_encode(array("flag"=>true));
                else echo json_encode(array("flag"=>false));
            }


            //注册动作
            if ($ajax == "reg"){

                if (isset($_POST["username"],$_POST["passwd"],$_POST["email"],$_POST["vcode"])){
                    $userName =  $this->request->getParam("username");
                    $passwd = $this->request->getParam("passwd");
                    $email = $this->request->getParam("email");
                    $vcode = $this->request->getParam("vcode");
                    $name = "";$address = "";$phone = "";
                    if (isset( $_POST["name"])) $name =  $this->request->getParam("name");if($name=="" || $name == null)$name="新用户";
                    if (isset( $_POST["address"])) $address =  $this->request->getParam("address");
                    if (isset( $_POST["phone"])) $phone =  $this->request->getParam("phone");
                    $temp = array('username'=>$userName,"passwd"=>$passwd,"email"=>$email,"group"=>$this->usergroupdefault,
                        "name"=>$name,"address"=>$address,"phone"=>$phone);
                    if ($this->userop->reg($temp)) echo json_encode(array("flag"=>true));
                    else echo json_encode(array("flag"=>false));
                }else echo json_encode(array("flag"=>false));
            }

            if ($ajax == "getInfo"){
                if (isset($this->session["uid"])){
                    $temp = $this->userop->getInfo(array("uid"=>7));
                    echo json_encode($temp);
                }else echo json_encode(array("flag"=>false,"error"=>"未登录"));
            }

            if ($ajax == "getLogin"){
                if(Yii::app()->user->checkAccess(RADMINJ) || Yii::app()->user->checkAccess(RSYSJ))
                {
                    $backUrl = Yii::app()->createUrl('backAn');
                }
                else
                {
                    $backUrl = Yii::app()->createUrl('backAn/MyRes');
                }

                if ($this->userauth->checkCookieJ("uid")){
                    $info = $this->userop->getInfo(array("username"=>$this->userauth->getCookieJ("username")));
                    $usernick = $info['name'];
                    
                    echo json_encode(array(
                        "flag"=>true,
                        "uid"=>$this->userauth->getCookieJ("uid"),
                        "username"=>$usernick,
                        "group" => Yii::app()->user->group,
                        "backurl" => $backUrl,
                    ));
                }else if (isset($this->session["uid"])) 
                {
                    $info = $this->userop->getInfo(array("username"=>$this->session["username"]));
                    $usernick = $info['name'];
                     echo json_encode(array(
                        "flag"=>true,
                        "uid"=>$this->session["uid"],
                        "username"=>$usernick,
                        "group" => Yii::app()->user->group,
                        "backurl" => $backUrl,
                    ));
                }
                else echo json_encode(array("flag"=>false));
            }

            if ($ajax == "logout"){
                 if ($this->userauth->checkCookieJ("uid") && $this->userauth->checkCookieJ("username"))
                 {
                    $this->userauth->destroyCookieJ('uid');
                    $this->userauth->destroyCookieJ('username');
                 }
                 else
                 {
                     $this->session->clear();
                     $this->session->destroy();
                 }
                $lasturl = urlencode($this->request->getParam('lasturl'));
                $randcode = rand();
                $ssse = new CHttpSession;  
                $ssse->open();
                $ssse['logoutrand'] = $randcode;
                echo json_encode(array("flag"=>true,"returnurl"=>Yii::app()->createUrl('HomeAn/LogoutSucc',array('lasturl'=>$lasturl,"rncd"=>$randcode))));
            }

            if ($ajax == "update"){
                if (isset($this->session["uid"])){
                    $name = "";
                    $address = "";
                    $phone = "";
                    $passwd = "";
                    if (isset($_POST["name"])){
                        $name = $this->request->getParam("name");
                    }
                    if (isset($_POST["address"])){
                        $address = $this->request->getParam("address");
                    }
                    if (isset($_POST["phone"])){
                        $phone = $this->request->getParam("phone");
                    }
                    if (isset($_POST["passwd"])){
                        $passwd = $this->request->getParam("passwd");
                        $oldPasswd = $this->request->getParam("oldpasswd");
                        $rePasswd = $this->request->getParam("repasswd");
                        if (!(($this->userop->checkPasswd($this->session["uid"],$oldPasswd))and($passwd==$rePasswd))){
                            echo json_encode(array("flag"=>false,"error"=>"旧密码有误或两次输入的密码不一致"));
                        }
                    }
                    $info = array("uid"=>$this->session["uid"],
                        "name"=>$name,
                        "address"=>$address,
                        "phone"=>$phone,
                        "passwd"=>$passwd,
                    );
                    if ($this->userop->updateInfo($info)) echo json_encode(array("flag"=>true));
                    else echo json_encode(array("flag"=>false,"error" => "更新失败，请检查是否有非法字符或资料已更新"));

                }else return json_encode(array("flag"=>false,"error"=>"尚未登录"));
            }

            if ($ajax == "alterauth")
            {
                if(Yii::app()->user->checkAccess(RSYSJ))
                {
                    $info=array();
                    $info['uid'] = $this->request->getParam('back_uid');
                    $info['group'] = $this->request->getParam('back_group');
                    $result = $this->userop ->alterAuthority($info);
                    if($result === true)
                    {
                        echo json_encode(array("flag"=>true,"berror" => ""));
                    }
                    else
                    {
                        echo json_encode(array("flag"=>false,"berror" => $result));
                    }
                }
                else
                {
                    echo json_encode(array("flag"=>false,"berror" => "You don't have permission to access !"));
                }
            }
            if ($ajax == "deleteuser")
            {
                if(Yii::app()->user->checkAccess(RSYSJ)||Yii::app()->user->checkAccess(RADMINJ))
                {
                    $info=array();
                    $info['uid'] = $this->request->getParam('back_uid');
                    $result = $this->userop ->deleteUserJ($info);
                    if($result === true)
                    {
                        echo json_encode(array("flag"=>true,"berror" => ""));
                    }
                    else
                    {
                        echo json_encode(array("flag"=>false,"berror" => $result));
                    }
                }
                else
                {
                    echo json_encode(array("flag"=>false,"berror" => "You don't have permission to access !"));
                }
            }
            if($ajax == 'confirmop')
            {
                if(Yii::app()->user->checkAccess(RSYSJ)||Yii::app()->user->checkAccess(RADMINJ))
                {

                    $passwd = $this->request->getParam('passwd');
                    if(!isset($passwd)) echo json_encode(array("flag"=>false,'berror'=>'The Paasword Invalid !'));
                    if ($this->userauth->checkCookieJ("username"))
                    {
                        $username = $this->userauth->getCookieJ("username");
                         $result = $this->userop->loginUsername($username,$passwd);
                         if($result)
                            echo json_encode(array("flag"=>true));
                        else
                            echo json_encode(array("flag"=>false,'berror'=>'密码错误！！'));

                    }else if (isset($this->session["username"])) 
                    {
                         $username = $this->session["username"];
                         $result = $this->userop->loginUsername($username,$passwd);
                         if($result)
                            echo json_encode(array("flag"=>true));
                        else
                            echo json_encode(array("flag"=>false,'berror'=>'密码错误！！'));
                    }
                    else
                    {
                        echo json_encode(array("flag"=>false,'berror'=>'您没有登录或者登录凭证已经失效！'));
                    }
                }
                else
                {
                    echo json_encode(array("flag"=>false,"berror" => "You don't have permission to access !"));
                }
            }


        }
	}

}