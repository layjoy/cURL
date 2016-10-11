<?php
 echo date('D').'<br/>';

$value= 'something from somewhere';

/* 简单cookie设置*/
setcookie("TestCookie", $value);

/* 有效期1个小时*/
setcookie("TestCookie", $value, time()+3600);

/* 有效目录/~rasmus,有效域名example.com及其所有子域名*/
setcookie("TestCookie", $value, time()+3600, "/~rasmus/", ".example.com", 1);


/* 有效期1个小时*/
//setcookie("TestCookie", $value, time()+5);

print$_COOKIE['TestCookie'];

/*$value= 'something from somewhere';
header("Set-Cookie:name=$value");*/

//echo phpinfo();

/*header("Set-Cookie: name=$value[;path=$path[;domain=xxx.com[; ]]");*/


?> 
