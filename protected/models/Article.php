<?php

/**
 * This is the model class for table "an_article".
 *
 * The followings are the available columns in table 'an_article':
 * @property string $aid
 * @property string $uid
 * @property string $title
 * @property string $from
 * @property string $fromlink
 * @property string $author
 * @property integer $isallow
 * @property string $uploadtime
 * @property string $content
 * @property string $typeid
 * @property string $link
 * @property integer $point
 * @property string $modifiedtime
 */
class Article extends CActiveRecord
{
	public $maxColumn;//max column cache
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'an_article';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('uid, title, from, fromlink, author, isallow, uploadtime, typeid', 'required'),
			array('isallow, point', 'numerical', 'integerOnly'=>true),
			array('uid, typeid', 'length', 'max'=>10),
			array('title, fromlink, link', 'length', 'max'=>128),
			array('from', 'length', 'max'=>64),
			array('author', 'length', 'max'=>32),
			array('content, modifiedtime', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('aid, uid, title, from, fromlink, author, isallow, uploadtime, content, typeid, link, point, modifiedtime', 'safe', 'on'=>'search'),
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
			'aid' => 'Aid',
			'uid' => 'Uid',
			'title' => 'Title',
			'from' => 'From',
			'fromlink' => 'Fromlink',
			'author' => 'Author',
			'isallow' => 'Isallow',
			'uploadtime' => 'Uploadtime',
			'content' => 'Content',
			'typeid' => 'Typeid',
			'link' => 'Link',
			'point' => 'Point',
			'modifiedtime' => 'Modifiedtime',
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

		$criteria->compare('aid',$this->aid,true);
		$criteria->compare('uid',$this->uid,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('from',$this->from,true);
		$criteria->compare('fromlink',$this->fromlink,true);
		$criteria->compare('author',$this->author,true);
		$criteria->compare('isallow',$this->isallow);
		$criteria->compare('uploadtime',$this->uploadtime,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('typeid',$this->typeid,true);
		$criteria->compare('link',$this->link,true);
		$criteria->compare('point',$this->point);
		$criteria->compare('modifiedtime',$this->modifiedtime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Article the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
