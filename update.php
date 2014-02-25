<?php
/**
 * Created by PhpStorm.
 * User: gg
 * Date: 13-12-24
 * Time: 下午7:06
 */


$data = array("id"=>123121211,"content_ik"=>"Solr是一个高性能，采用Java5开发，基于Lucene");
$data_string = json_encode($data);
$data1 = array("doc"=>$data_string,"boost"=>"1.0","overwrite"=>true,"commitWithin"=>1000);
$data1_string = json_encode($data1);
$update = json_encode(array("add"=>$data1_string));
echo $update;
$ch = curl_init('http://210.42.151.79/solr/testColl/update?wt=json');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS,$update);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($data_string))
);
$result = curl_exec($ch);
echo $result;
