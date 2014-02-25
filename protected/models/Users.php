<?php

/**
 * This is the model class for table "an_users".
 *
 * The followings are the available columns in table 'an_users':
 * @property integer $uid
 * @property string $username
 * @property string $passwd
 * @property string $email
 * @property string $name
 * @property string $phone
 * @property string $address
 * @property integer $group
 * @property string $lastlogin
 * @property string $regtime
 * @property string $lastip
 * @property string $note
 */
class Users extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'an_users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, passwd, email, group, regtime', 'required'),
			array('group', 'numerical', 'integerOnly'=>true),
			array('username', 'length', 'max'=>40),
			array('passwd', 'length', 'max'=>65),
			array('email', 'length', 'max'=>64),
			array('name', 'length', 'max'=>32),
			array('phone, lastip, note', 'length', 'max'=>16),
			array('address', 'length', 'max'=>256),
			array('lastlogin', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('uid, username, passwd, email, name, phone, address, group, lastlogin, regtime, lastip, note', 'safe', 'on'=>'search'),
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
			'uid' => 'Uid',
			'username' => 'Username',
			'passwd' => 'Passwd',
			'email' => 'Email',
			'name' => 'Name',
			'phone' => 'Phone',
			'address' => 'Address',
			'group' => 'Group',
			'lastlogin' => 'Lastlogin',
			'regtime' => 'Regtime',
			'lastip' => 'Lastip',
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

		$criteria->compare('uid',$this->uid,true);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('passwd',$this->passwd,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('group',$this->group);
		$criteria->compare('lastlogin',$this->lastlogin,true);
		$criteria->compare('regtime',$this->regtime,true);
		$criteria->compare('lastip',$this->lastip,true);
		$criteria->compare('note',$this->note,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Users the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


}
