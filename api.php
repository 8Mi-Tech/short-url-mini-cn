<?php
include './includes/api.inc.php';
$longurl = (isset($_GET['url'])) ?$_GET['url']:$_POST['url'];
$format = (isset($_GET['format'])) ?$_GET['format']:$_POST['format'];

if(!$longurl){
	show_result(0,"the url cannot be empty",10001);
  	exit();
}
if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$longurl)) {
	show_result(0,"url is incorrect",10002);
  	exit();
}
//查域名是否报毒
if (CHECK_MODE == 1) {
	$param = http_build_query(array(
		'token'		=>		CHECK_TOKEN
		,'domain'	=>		$longurl
	));
	$result = get_curl('http://check.uomg.com/api/urlsec/'.CHECK_TYPE, $param);
	$arr = json_decode($result,true);
	if ($arr['code'] == 201) {
		show_result(0,"url is red",10004);
	}

}
$uid = shorturl($longurl);
$myrow = $DB->get_row("select * from wjoy_log where longurl='".base64_encode($longurl)."' limit 1");
if(!$myrow){
	//不存在
	$sql = $DB->query("insert into `wjoy_log` (`uid`,`longurl`) values ('".$uid."','".base64_encode($longurl)."')");
	if(!empty($sql)){
	    show_result($uid,"success",1);
	}else{
	    show_result(0,"failure",10003);
	}
	
}else{
	//存在
	show_result($uid,"existence",1);
}

$DB->close();

function show_result($code,$msg,$result){
	global $format;
	if ($format === 'txt') {
		if ($code === 0 ){
			exit($msg);
		}else{
			exit($code);
		}
	}else{
		$result=array("code"=>$code,"msg"=>$msg,"result"=>$result);
		exit(json_encode($result));
	}

}