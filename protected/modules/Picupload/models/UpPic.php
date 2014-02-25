<?php
/**
 * Created by PhpStorm.
 * User: gg
 * Date: 13-12-26
 * Time: 上午4:57
 */

class UpPic {
    var $path = "./uploaded_pic/";
    var $ext_arr = array('gif', 'jpg', 'jpeg', 'png', 'bmp');
    var $max_size = 1000000;


    public function UpPic(){
        $this->checkDir("");
    }


    public function alert($msg){
        echo json_encode(array('error' => 1, 'message' => $msg));
        exit;
    }


    private function checkDir($path){
        $path = $this->path.$path;
//        echo "<br>".realpath($path);
        if (!file_exists($path)) {
            mkdir($path);
        }
    }


    private function getDir(){
        $dir = date("Ym");
        $this->checkDir($dir);
        $dir = $dir . "/" . date("d")."/";
        $this->checkDir($dir);
        return $this->path.$dir;
    }

    private function getName(){
        srand(getdate()[0]);
        $name = getdate()[0] . "_" .(int)rand(1000,9999);
        return $name;
    }

    public function getPath(){
        return $this->getDir() . $this->getName();
    }


} 