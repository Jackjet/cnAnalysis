<?php

/**
 * This is the model class for table "an_files".
 *
 * The followings are the available columns in table 'an_files':
 * @property string $fid
 * @property string $filepath
 * @property string $filename
 * @property string $fileext
 * @property integer $filesize
 * @property string $tags
 * @property string $uploadtime
 * @property integer $uploader
 * @property integer $aid
 * @property integer $isallow
 * @property string $note
 */
class Files extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'an_files';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('filepath, filename, fileext, filesize, uploadtime, uploader, isallow', 'required'),
			array('filesize, uploader, aid, isallow', 'numerical', 'integerOnly'=>true),
			array('filepath, filename', 'length', 'max'=>64),
			array('fileext, note', 'length', 'max'=>16),
			array('tags', 'length', 'max'=>128),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('fid, filepath, filename, fileext, filesize, tags, uploadtime, uploader, aid, isallow, note', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'fid' => 'Fid',
			'filepath' => 'Filepath',
			'filename' => 'Filename',
			'fileext' => 'Fileext',
			'filesize' => 'Filesize',
			'tags' => 'Tags',
			'uploadtime' => 'Uploadtime',
			'uploader' => 'Uploader',
			'aid' => 'Aid',
			'isallow' => 'Isallow',
			'note' => 'Note',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('fid',$this->fid,true);
		$criteria->compare('filepath',$this->filepath,true);
		$criteria->compare('filename',$this->filename,true);
		$criteria->compare('fileext',$this->fileext,true);
		$criteria->compare('filesize',$this->filesize);
		$criteria->compare('tags',$this->tags,true);
		$criteria->compare('uploadtime',$this->uploadtime,true);
		$criteria->compare('uploader',$this->uploader);
		$criteria->compare('aid',$this->aid);
		$criteria->compare('isallow',$this->isallow);
		$criteria->compare('note',$this->note,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Files the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
