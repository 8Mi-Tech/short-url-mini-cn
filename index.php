<?php
if(!@file_exists('../install.lock')&&!@file_exists('./install.lock')){
	echo '<h2>检测到无 install.lock 文件</h2><ul><li><font size="4">如果您尚未安装本程序，请<a href="../install/">前往安装</a></font></li><li><font size="4">如果您已经安装本程序，请手动放置一个空的 install.lock 文件到根目录下，<b>为了您站点安全，在您完成它之前我们不会工作。</b></font></li></ul><br/>';
	exit(0);
}
?>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no">
	<meta name="generator" content="Myurl" />
	<title>短网址生成</title>
    <meta name="keywords" content="缩短网址,网址压缩,网址缩短,短网址,短域名,短地址,短URL,免费缩短网址,短链接生成器,短网址生成,免费缩址,域名伪装,域名转向,网站推广,短链接,长网址变短网址">
	<meta name="description" content="免费专业的网址缩短服务,在线生成短网址,开放API接口,可批量生成短链接,不限制接口请求数,跳转快,稳定可靠,防屏蔽,防拦截!">
	<link rel="Shortcut Icon" href="https://www.52tt.xyz/wp-content/uploads/2020/07/favicon.png">
	<link rel="stylesheet" href="https://css.letvcdn.com/lc04_yinyue/201612/19/20/00/bootstrap.min.css">
	<style type="text/css"></style>
	<style type="text/css">
		*{margin:0;padding:0}
		body,form,html{height:100%;}
		.row{margin-top:120px;min-height:70%}
		.footer{text-align:center;}
		.footer a{color: #009688;}
		.page-header{margin-bottom:40px}
		.expand-transition{-webkit-transition:all .5s ease;transition:all .5s ease}
		.expand-enter,.expand-leave{opacity:0}

		@media (max-width:768px){.h3-xs{font-size:20px}
			.row-xs{margin-top:80px}
		}
		.modal{display:block}
		.alert.top{position:fixed;top:30px;margin:0 auto;left:0;right:0;width:50%;z-index:1050}
		@media (max-width:768px){.alert.top-xs{width:80%}
		}
		.en-markup-crop-options{top:18px!important;left:50%!important;margin-left:-100px!important;width:200px!important;border:2px rgba(255,255,255,.38) solid!important;border-radius:4px!important}
		.en-markup-crop-options div div:first-of-type{margin-left:0!important}
	</style>
</head>

<body>

	<div id="app" class="container">
		<div class="alert top top-xs alert-dismissible alert-danger expand-transition" style="display:none" id="error-tips">

		</div>
		<div class="row row-xs">
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-10 col-xs-offset-1 col-sm-offset-3 col-md-offset-3 col-lg-offset-3">
				<div class="page-header">
					<h3 class="text-center h3-xs">短网址生成工具</h3>
				</div>
				<div class="form-group " id="input-wrap"> 
					<label class="control-label" for="inputContent">请输入长网址:</label> 
					<input type="text" id="inputContent" class="form-control" placeholder="请输入地址..."> 
				</div>
				<div class="text-right">
					<div class="input_group_addon btn btn-primary" id="shortify" onclick="checkUrl(document.getElementById('inputContent').value)">缩短网址</div>
				</div>
			</div>
			<div class="modal expand-transition" id="result-wrap" style="display:none">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header"> <button type="button" class="close" data-dismiss="modal" onclick="closeWrapper()" aria-hidden="true">×</button>
							<h4 class="modal-title">生成成功！</h4>
						</div>
						<div class="modal-body">
							<div class="form-group"> <input type="text" class="form-control" id="gen_result_url" value=""> </div>
						</div>
						<div class="modal-footer">
							<a target="_blank" id="preViewBtn"> <button type="button" class="btn btn-success">点击预览</button> </a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="footer" style="color:#888;">

	        <p>
	        	<!--<script type="text/javascript">站长统计代码</script> 
	        	| <a href="链接">XXX</a>
	        	| <a href="链接">XXX</a>
	        </p>
	        声明：需要的自己写<br>
			© Copyright 2020 <a href="链接">XXX</a> - All Rights Reserved. -->

	</div>

	<script>
		function sendAJAX(hasHttp) {
			var xhr = new XMLHttpRequest();
			xhr.onreadystatechange = function () {
				if (xhr.readyState === 4) {

					if(xhr.status===200){

						if (xhr.responseText.indexOf("(1045)") > -1) {
							console.log(xhr.responseText.indexOf("(1045)"));
							msgAlert('您还没有配置数据库文件吧！');
						}
						var result = JSON.parse(xhr.responseText);
						if (result.result == 1) {
							var resultWrap = document.getElementById('result-wrap');
							resultWrap.style.display = 'block';
							var preViewBtn = document.getElementById('preViewBtn'),
								genResultUrl = document.getElementById('gen_result_url');
							preViewBtn.setAttribute('href', location.protocol+'//'+location.host+'/' + result.code);
							genResultUrl.value = location.protocol+'//'+location.host+'/' + result.code
						} else {
							msgAlert(result.msg);
						}
					}else{
							msgAlert('返回错误');
					}	
				}
			};
			var urlVal = document.getElementById('inputContent').value;
			var urlParam = hasHttp?urlVal:'http://'+urlVal;
			xhr.open('GET', location.protocol+'//'+location.host + '/api.php?url=' + encodeURIComponent(urlParam));
			xhr.send();
		}

		function msgAlert(txt,input) {
			var tips = document.getElementById('error-tips');
			tips.style.display = "block";
			tips.innerHTML = txt;
			input&&(document.getElementById('input-wrap').classList.add('has-error'))
			setTimeout(function () {
				tips.style.display = 'none';
			}, 3000)
		}
		function closeWrapper(){
			document.getElementById('result-wrap').style.display='none'
		}
		function checkUrl(text) {
			var hasHttp = /^([hH][tT]{2}[pP]:\/\/|[hH][tT]{2}[pP][sS]:\/\/)\w+([-.]\w+)*\.\w+([-.]\w+)*/.test(text),
				notHasHttp = /^\w+([-.]\w+)*\.\w+([-.]\w+)*/.test(text);
			if (!hasHttp&&!notHasHttp) {
				msgAlert('输入的url有误，请重新输入!',true);
			} else {
				document.getElementById('input-wrap').classList.remove('has-error')
				sendAJAX(hasHttp);
			}
		}
	</script>

</body>

</html>