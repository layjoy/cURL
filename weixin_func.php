<?php

	/*
		代码封装-封装成一个函数
	*/

	//1、接收微信服务器get请求过来的4个参数

	define("TOKEN","duduloveme");
	$echostr = $_GET['echostr'];//随机字符串

	valid();

	//验证消息
	function valid()
	{
		if(checkSignature())
		{
			echo $_GET['echostr'];
		}
		else
		{
			echo "Error";
		}
	}
	//检验微信加密签名Signature
	function checkSignature()
	{
		$signature = $_GET['signature'];//微信加密签名
		$timestamp = $_GET['timestamp'];//时间戳
		$nonce = $_GET['nonce'];//随机数
	

		//2、加密/校验
		// 1. 将token、timestamp、nonce三个参数进行字典序排序；
		$tmpArr = array(TOKEN,$timestamp,$nonce);
		sort($tmpArr,SORT_STRING);

		// 2. 将三个参数字符串拼接成一个字符串进行sha1加密；
		$tmpStr = implode($tmpArr);
		$tmpStr = sha1($tmpStr);

		// 3. 开发者获得加密后的字符串与signature对比。
		if($tmpStr == $signature)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

?>
