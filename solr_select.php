<?php
//服务端返回JSON数据
//用于解决ajax跨域问题
header("Content-type: application/json; charset=utf-8");
$address = "*";
$title = "*";
$from = "*";
$cat = "*";
if(isset($_GET['address'])) $address = "\"" . $_GET['address'] . "\"";
if(isset($_GET['title'])) $title = "\"" . $_GET['title'] . "\"";
if(isset($_GET['from'])) $from = "\"" . $_GET['from'] . "\"";
if(isset($_GET['cat'])) $cat = "\"" . $_GET['cat'] . "\"";
// if(isset($_GET['cat'])) {
// 	if($_GET['cat'] == 4) {
// 		$cat = "*";
// 	}
// 	else {
// 		$cat = "\"" . $_GET['cat'] . "\"";
// 	}
// }
$address = urlencode($address);
$title = urlencode($title);
$from = urlencode($from);
$cat = urlencode($cat);

// if()
// $url = "http://210.42.151.79/solr/testColl/select?wt=json&q=";
$url = "http://210.42.151.79/solr/testColl/select?wt=json&q=+content_ik%3A" . $address;
// $url = "http://210.42.151.79/solr/testColl/select?wt=json&q=+content_ik%3A" . $address . "+title_ik%3A" . $title ."+from_ik%3A" . $from;
// $url = "http://210.42.151.79/solr/testColl/select?wt=json&q=+content_ik%3A" . $address . "+title_ik%3A" . $title ."+from_ik%3A" . $from ."+type_ik%3A" . $cat;
$data = file_get_contents($url);
echo $data;
// echo "http://210.42.151.79/solr/testColl/select?wt=json&q=content_ik%3A" . $address . "+title_ik%3A" . $title ."+from_ik%3A" . $from ."+type_ik%3A" . $cat;
// $callback=$_GET['callbackparam'];
// echo $callback."($data)";

?>