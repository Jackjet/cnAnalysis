<?php

/**
 * This is the model class for file upload.
 * @author JelCore
 * @version 2014-01-11
 */
class UploadFile extends CActiveRecord
{
	public $file;
	public function rules()
    {
        return array(
            array('image', 'file', 'types'=>'jpg, gif, png'),
        );
    }
}
?>