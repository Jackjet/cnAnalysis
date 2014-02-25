<?php

class DefaultController extends Controller
{
    private $uppic ;
    private function _init(){
        $this->uppic = new UpPic();
    }
	public function actionIndex()
	{
        $this->_init();
        if (isset(Yii::app()->session["uid"])) {
            if ((!(empty($_FILES)))) {
                $file_name = $_FILES['imgFile']['name'];
                $tmp_name = $_FILES['imgFile']['tmp_name'];
                $file_size = $_FILES['imgFile']['size'];
                if (!$file_name) {
                    $this->uppic->alert("请选择文件");
                }elseif (@is_dir($this->uppic->path) === false) {
                    $this->uppic->alert("上传目录不存在。");
                }elseif (@is_writable($this->uppic->path) === false) {
                    $this->uppic->alert("上传目录没有写权限。");
                }
                elseif (@is_uploaded_file($tmp_name) === false) {
                    alert("上传失败。");
                }
                elseif ($file_size > $this->uppic->max_size) {
                    $this->uppic->alert("上传文件大小超过限制。");
                }

                $temp_arr = explode(".", $file_name);
                $file_ext = array_pop($temp_arr);
                $file_ext = trim($file_ext);
                $file_ext = strtolower($file_ext);
                if (!in_array($file_ext,$this->uppic->ext_arr)){
                    $this->uppic->alert("上传文件类型错误。\n只允许" . implode(",", $this->uppic->ext_arr) . "格式。");
                }
                $new_file_name = $this->uppic->getPath() . "." . $file_ext;
                if (move_uploaded_file($tmp_name, $new_file_name) === false) {
                    $this->uppic->alert("上传文件失败。");
                }
                @chmod($new_file_name, 0644);
                $local = $_SERVER["HTTP_HOST"].$_SERVER['PHP_SELF'];
                $temp = explode("/",$local);
                for ($b = 0;$b<2;$b++) array_pop($temp);
                $local = "http://".implode("/", $temp)."/".$new_file_name;
                echo json_encode(array('error' => 0, 'url' => $local));


            }
        }
	}



}