<?php

/**
 * This is the model class for table "an_articletype".
 *
 * The followings are the available columns in table 'an_articletype':
 * @property string $typeid
 * @property string $typename
 * @property string $typeslug
 * @property integer $typecount
 * @property string $note
 */
class Articletype extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public $maxColumn;//max column cache
	public function tableName()
	{
		return 'an_articletype';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('typename', 'required'),
			array('typecount', 'numerical', 'integerOnly'=>true),
			array('typename, note', 'length', 'max'=>32),
			array('typeslug', 'length', 'max'=>128),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('typeid, typename, typeslug, typecount, note', 'safe', 'on'=>'search'),
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
			'typeid' => 'Typeid',
			'typename' => 'Typename',
			'typeslug' => 'Typeslug',
			'typecount' => 'Typecount',
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

		$criteria->compare('typeid',$this->typeid,true);
		$criteria->compare('typename',$this->typename,true);
		$criteria->compare('typeslug',$this->typeslug,true);
		$criteria->compare('typecount',$this->typecount);
		$criteria->compare('note',$this->note,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Articletype the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
