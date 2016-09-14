<link href="http://kevinburke.bitbucket.org/markdowncss/markdown.css" rel="stylesheet"></link>
# cURL
## 1. cURL介绍
cURL(Client URL Library Functions) <font color = #FF6A6A>_'cURL is a command line tool and library for transferring data with URL syntax'_</font> cURL是一个使用URL语法传输数据的命令行工具,
支持多种协议,如FTP, FTPS, HTTP, HTTPS, GOPHER, TELNET,DICT, FILE 以及 LDAP等。  
	<image src = 'https://github.com/layjoy/cURL/blob/master/curl.png?raw=true' width=568  />  
PHP支持cURL库,这里介绍 cURL 的一些特性，以及在PHP中如何运用它. 

## 2. 基本结构
 先查看cURL是否开启  
`php -i | grep cURL` <font size = 2 color = #00CD00>//查看phpinfo,过滤出cURL的信息</font>  
默认情况下PHP是支持cURL的，如果未开启,需要在php.ini中开启该功能:  
`;extension=php_curl.dll`前面的分号去掉   
 
在PHP中建立cURL请求的基本步骤: 
#### 1. 初始化

	curl_init()

#### 2. 设置变量  
`curl_setopt()`最重要的部分，一切玄妙均在此。有一长串cURL参数可供设置，它们能指定URL请求的各个细节。要一次性全部看完并理解可能比较困难，所以今天我们只试一下那些更常用也更有用的选项。
#### 3. 执行并获取结果

	curl_exec()		
	
#### 4. 释放cURL句柄,关闭curl
	curl_close()
	
## 3. cURL实现Get和Post  
 
3.1 Get方式实现 

```  

$url = "http://www.baidu.com";
//1、初始化CURL
$ch = curl_init();

//2、设置传输选项
curl_setopt($ch,CURLOPT_URL,$url);

//3、执行CURL请求
curl_exec($ch);

//4、关闭CURL
curl_close($ch);

```
3.2 Post方式实现  

```

$url = "http://japi.juhe.cn/joke/content/text.from?";
$data = "page=&pagesize=&key=b10bbc3126e377305ed9ce63763be02d";
$ch = curl_init(); //初始化一个CURL对象
curl_setopt($ch, CURLOPT_URL,$url );
//设置你所需要抓取的URL
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
//设置curl参数，要求结果是否输出到屏幕上，为true(或1)的时候是不返回到网页中,$data需要echo一下。
curl_setopt($ch, CURLOPT_POST, 1);
//post提交
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt ($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36');
$data = curl_exec($ch);//运行curl,请求网页

curl_close($ch);

```
以上方式获取到的数据是json格式的，使用json_decode函数解释成数组。  

```
$output_array = json_decode($output,true);
```
如果使用json_decode($output)解析的话，将会得到object类型的数据。
## 4. 使用cURL来实现几个简单的功能
  
 <center><font size =5 color = #EE9572 >cURL使用场景</font></center>  
<font color = #32CD32 > 
            
  - 网页资源    
    - 编写网络爬虫  
  - WebService数据接口资源  
    - 动态获取接口数据,比如天气,号码归属地等
  - https资源  
    - CURL访问https资源  
  - 其他资源  
    - 所有网络上的资源都可以用从cURL访问和下载到    
    
   </font>  
4.1 使用PHP cURL做一个简单的网络爬虫:   

```

$curl=curl_init('http://www.baidu.com');
curl_exec($curl);
curl_close($curl);


```
查看抓取到的内容  
`php -f ~/Sites/cURL/crawler01.php`   <font size = 2 color = #00CD00>//或cd到文件目录执行php -f命令</font>  
将抓取到的内容 重定向到baidu.html文件中  
`php -f ~/Sites/cURL/crawler01.php > ~/Sites/cURL/baidu.html`  

在网络上下载一个百度首页并把内容中的“百度”替换为“奇步”之后输出

```

$curlobj = curl_init();			// 初始化
curl_setopt($curlobj, CURLOPT_URL, "http://www.baidu.com");// 设置访问网页的URL
curl_setopt($curlobj, CURLOPT_RETURNTRANSFER, true);// true或1 执行之后不直接打印出来,0或false直接打印出来
$output=curl_exec($curlobj);	// 执行
curl_close($curlobj);			// 关闭cURL
echo str_replace("百度","奇步",$output);

```
  
4.2 使用PHP cURL获取天气信息:   

```

$data = 'theCityName=北京';
$curlobj = curl_init();
$url = "http://www.webxml.com.cn/WebServices/WeatherWebService.asmx/getWeatherbyCityName";
curl_setopt($curlobj, CURLOPT_URL, $url);
curl_setopt($curlobj, CURLOPT_RETURNTRANSFER, 1);
// post数据
curl_setopt($curlobj, CURLOPT_POST, 1);
// post的变量
curl_setopt($curlobj, CURLOPT_POSTFIELDS, $data);
curl_setopt($curlobj, CURLOPT_HTTPHEADER, array("application/x-www-form-urlencoded; charset=utf-8",
    "Content-length: " . strlen($data),
));

/*在抓取网页这部分，有一个参数必须要考虑进去，那就是UserAgent
 *UserAgent简称（UA），是一个只读的字符串，它声明了浏览器用于 HTTP 请求的用户代理头的值。
 *即:声明用什么浏览器来打开目标网页
 */
//$UA = $_SERVER['HTTP_USER_AGENT'];

curl_setopt ($curlobj, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36');

$output = curl_exec($curlobj);

if (!curl_errno($curlobj)) {

//     $info = curl_getinfo($curlobj);
//     print_r($info);

    echo $output;

} else {
    echo 'Curl error: ' . curl_error($curlobj);
}
curl_close($curlobj);


/*
 *curl_getinfo()参数  
 *       名称	                              说明
 *CURLINFO_EFFECTIVE_URL          	最后一个有效的url地址
 *CURLINFO_HTTP_CODE	            最后一个收到的HTTP代码
 *CURLINFO_FILETIME                远程获取文档的时间，如果无法获取，则返回值为“-1”
 *CURLINFO_TOTAL_TIME	            最后一次传输所消耗的时间
 *CURLINFO_NAMELOOKUP_TIME	        名称解析所消耗的时间
 *CURLINFO_CONNECT_TIME	            建立连接所消耗的时间
 *CURLINFO_PRETRANSFER_TIME	        从建立连接到准备传输所使用的时间
 *CURLINFO_STARTTRANSFER_TIME	    从建立连接到传输开始所使用的时间
 *CURLINFO_REDIRECT_TIME	        在事务传输开始前重定向所使用的时间
 *CURLINFO_SIZE_UPLOAD	            上传数据量的总值
 *CURLINFO_SIZE_DOWNLOAD	        下载数据量的总值
 *CURLINFO_SPEED_DOWNLOAD	        平均下载速度
 *CURLINFO_SPEED_UPLOAD	            平均上传速度
 *CURLINFO_HEADER_SIZE	            header部分的大小
 *CURLINFO_HEADER_OUT	            发送请求的字符串
 *CURLINFO_REQUEST_SIZE	            在HTTP请求中有问题的请求的大小
 *CURLINFO_SSL_VERIFYRESULT	        Result of SSL certification verification requested by setting CURLOPT_SSL_VERIFYPEER
 *CURLINFO_CONTENT_LENGTH_DOWNLOAD	从Content-Length: field中读取的下载内容长度
 *CURLINFO_CONTENT_LENGTH_UPLOAD	上传内容大小的说明
 *CURLINFO_CONTENT_TYPE	           下载内容的“Content-type”值，NULL表示服务器没有发送有效的“Content-Type: header”
*/  

```

4.3 使用PHP cURL获取个人空间页面资源并下载:

```

$data='username=admin@126.com&password=123qwe&remember=1';
$curlobj = curl_init();	 // 初始化
curl_setopt($curlobj, CURLOPT_URL, "http://www.imooc.com/user/login"); // 设置访问网页的URL
curl_setopt($curlobj, CURLOPT_RETURNTRANSFER, true); // 执行之后不直接打印出来

// Cookie相关设置，这部分设置需要在所有会话开始之前设置(用来保存用户登录信息)
date_default_timezone_set('PRC'); // 使用Cookie时，必须先设置时区(因为cookie有过期时间)
curl_setopt($curlobj, CURLOPT_COOKIESESSION, TRUE);//设置支持cookiesession,客户端保存cookie,服务器保存session
curl_setopt($curlobj, CURLOPT_COOKIEFILE, 'cookiefiles');//包含cookie信息的文件名称，这个cookie文件可以是Netscape格式或者HTTP风格的header信息。
curl_setopt($curlobj, CURLOPT_COOKIEJAR, 'cookiefiles');//连接关闭以后，存放cookie信息的文件名称,用来读取本地保存的cookie
curl_setopt($curlobj, CURLOPT_COOKIE,session_name().'='.session_id());//设定HTTP请求中“Set-Cookie:”部分的内容。存储 session_name = session_id

curl_setopt($curlobj, CURLOPT_HEADER, 0);
curl_setopt($curlobj, CURLOPT_FOLLOWLOCATION, 1);//让cURL支持页面链接跳转

curl_setopt($curlobj, CURLOPT_POST, 1);
curl_setopt($curlobj, CURLOPT_POSTFIELDS, $data);
curl_setopt($curlobj, CURLOPT_HTTPHEADER, array("application/x-www-form-urlencoded; charset=utf-8",
    "Content-length: ".strlen($data)
));
curl_exec($curlobj);	// 执行
curl_setopt($curlobj, CURLOPT_URL, "http://www.imooc.com/space/index");//重新设置访问网页的URL
curl_setopt($curlobj, CURLOPT_POST, 0);//下载网页不需要post请求,关闭post
curl_setopt($curlobj, CURLOPT_HTTPHEADER, array("Content-type: text/xml"
)); //把CURLOPT_HTTPHEADER清除掉,设置成xml格式

$output=curl_exec($curlobj);	// 执行
curl_close($curlobj);			// 关闭cURL
echo $output;


/**
 * 注释掉curl_setopt($curlobj, CURLOPT_FOLLOWLOCATION, 1),
 * 因为这个设置必须关闭安全模式以及关闭open_basedir，对服务器安全不利
 * 自定义实现页面链接跳转抓取
 */
function curl_redir_exec($ch,$debug="") 
{ 
    static $curl_loops = 0; 
    static $curl_max_loops = 20; 

    if ($curl_loops++ >= $curl_max_loops) 
    { 
        $curl_loops = 0; 
        return FALSE; 
    } 
    curl_setopt($ch, CURLOPT_HEADER, true); // 开启header才能够抓取到重定向到的新URL
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    $data = curl_exec($ch); 
    // 分割返回的内容
    $h_len = curl_getinfo($ch, CURLINFO_HEADER_SIZE); 
    $header = substr($data,0,$h_len);
    $data = substr($data,$h_len - 1);

    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
    if ($http_code == 301 || $http_code == 302) { 
        $matches = array(); 
        preg_match('/Location:(.*?)\n/', $header, $matches); 
        $url = @parse_url(trim(array_pop($matches))); 
        // print_r($url); 
        if (!$url) 
        { 
            //couldn't process the url to redirect to 
            $curl_loops = 0; 
            return $data; 
        } 
        $last_url = parse_url(curl_getinfo($ch, CURLINFO_EFFECTIVE_URL)); 
        if (!isset($url['scheme'])) 
            $url['scheme'] = $last_url['scheme']; 
        if (!isset($url['host'])) 
            $url['host'] = $last_url['host']; 
        if (!isset($url['path'])) 
            $url['path'] = $last_url['path'];

        $new_url = $url['scheme'] . '://' . $url['host'] . $url['path'] . (isset($url['query'])?'?'.$url['query']:''); 
        curl_setopt($ch, CURLOPT_URL, $new_url); 

        return curl_redir_exec($ch); 
    } else { 
        $curl_loops=0; 
        return $data; 
    } 
} 
```  
4.4 使用PHP cURL访问https资源:  

```

$curlobj = curl_init();			// 初始化
curl_setopt($curlobj, CURLOPT_URL, "https://ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/jquery.validate.js");		// 设置访问网页的URL
curl_setopt($curlobj, CURLOPT_RETURNTRANSFER, true);			// 执行之后不直接打印出来

// 设置HTTPS支持
date_default_timezone_set('PRC'); // 使用Cookie时，必须先设置时区
curl_setopt($curlobj, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查从证书中检查SSL加密算法是否存在
curl_setopt($curlobj, CURLOPT_SSL_VERIFYHOST, 2); //

$output=curl_exec($curlobj);	// 执行
curl_close($curlobj);			// 关闭cURL
echo $output;
```  

  
4.5 封装cURL(微信开发中): 

```

	//https请求(GET和POST)
		public function https_request($url,$data=null)//$data默认给空
		{
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); //将页面以文件流的形式保存

			if(!empty($data))//$data不为空,post请求
			{
				curl_setopt($ch, CURLOPT_POST, 1);//模拟POST请求

				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);//POST提交内容
			}

			$outopt = curl_exec($ch);//请求结果 一般为json数据

			curl_close($ch);

			return json_decode($outopt,true);//true 返回数组,不加true返回对象
		}
		
```


<font size = 4 color = 	#FF0000>**可设置的参数:**</font>  　　

<font color = 	#FF0000>CURLOPT_HEADER  
默认为0,启用时设置这个选项为一个非零值,会将头文件的信息作为数据流输出。
  
CURLOPT_HTTPHEADER  
设置一个header中传输内容的数组。
  
CURLOPT_COOKIE  
设定HTTP请求中“Set-Cookie:”部分的内容。

CURLOPT_COOKIESESSION  
启用时curl会仅仅传递一个session cookie，忽略其他的cookie，默认状况下curl会将所有的cookie返回给服务端。session cookie是指那些用来判断服务器端的session是否有效而存在的cookie。 

CURLOPT_COOKIEFILE  
包含cookie信息的文件名称，这个cookie文件可以是Netscape格式或者HTTP风格的header信息。

CURLOPT_COOKIEJAR  
连接关闭以后，存放cookie信息的文件名称
 
CURLOPT_POSTFIELDS  
在HTTP中的“POST”操作。如果要传送一个文件，需要一个@开头的文件名
 
CURLOPT_POST  
启用时会发送一个常规的POST请求，类型为：application/x-www-form-urlencoded，就像表单提交的一样。  

CURLOPT_RETURNTRANSFER  
//设置curl参数，请求结果是否输出到屏幕上，为true(或1)的时候将curl_exec()获取的信息以文件流的形式返回，而不是直接输出。(可以echo打印出来) 

CURLOPT_UPLOAD  
启用时允许文件传输

CURLOPT_INFILESIZE  
设定上传文件的大小

CURLOPT_TIMEOUT  
设置curl允许执行的最长秒数

CURLOPT_URL  
需要获取的URL地址，也可以在PHP的curl_init()函数中设置。

CURLOPT_USERAGENT  
在HTTP请求中包含一个”user-agent”头的字符串。

CURLOPT_USERPWD  
传递一个连接中需要的用户名和密码，格式为：“[username]:[password]”。  
  
CURLOPT_FILE  
设置输出文件的位置，值是一个资源类型，默认为STDOUT (浏览器)。

CURLOPT_INFILE  
在上传文件的时候需要读取的文件地址，值是一个资源类型。  
  
</font>


CURLOPT_AUTOREFERER  
自动设置header中的referer信息  
  
CURLOPT_BINARYTRANSFER  
在启用CURLOPT_RETURNTRANSFER时候将获取数据返回  
  
  
CURLOPT_CRLF  
启用时将Unix的换行符转换成回车换行符。  

CURLOPT_DNS_USE_GLOBAL_CACHE  
启用时会启用一个全局的DNS缓存，此项为线程安全的，并且默认为true。  

CURLOPT_FAILONERROR  
显示HTTP状态码，默认行为是忽略编号小于等于400的HTTP信息  

CURLOPT_FILETIME  
启用时会尝试修改远程文档中的信息。结果信息会通过curl_getinfo()函数的CURLINFO_FILETIME选项返回。  

CURLOPT_FOLLOWLOCATION  
启用时会将服务器服务器返回的“Location:”放在header中递归的返回给服务器，使用CURLOPT_MAXREDIRS可以限定递归返回的数量。  

CURLOPT_FORBID_REUSE  
在完成交互以后强迫断开连接，不能重用。

CURLOPT_FRESH_CONNECT  
强制获取一个新的连接，替代缓存中的连接。  


CURLOPT_HTTPGET  
启用时会设置HTTP的method为GET，因为GET是默认的，所以只在被修改的情况下使用。

CURLOPT_HTTPPROXYTUNNEL  
启用时会通过HTTP代理来传输。

CURLOPT_MUTE  
讲curl函数中所有修改过的参数恢复默认值。

CURLOPT_NETRC  
在连接建立以后，访问~/.netrc文件获取用户名和密码信息连接远程站点。

CURLOPT_NOBODY  
启用时将不对HTML中的body部分进行输出。

CURLOPT_NOPROGRESS  
启用时关闭curl传输的进度条，此项的默认设置为true

CURLOPT_NOSIGNAL  
启用时忽略所有的curl传递给php进行的信号。在SAPI多线程传输时此项被默认打开。

CURLOPT_PUT  
启用时允许HTTP发送文件，必须同时设置CURLOPT_INFILE和CURLOPT_INFILESIZE

CURLOPT_UNRESTRICTED_AUTH  
在使用CURLOPT_FOLLOWLOCATION产生的header中的多个locations中持续追加用户名和密码信息，即使域名已发生改变。

CURLOPT_VERBOSE  
启用时会汇报所有的信息，存放在STDERR或指定的CURLOPT_STDERR中

CURLOPT_BUFFERSIZE
每次获取的数据中读入缓存的大小，这个值每次都会被填满。

CURLOPT_CLOSEPOLICY  
不是CURLCLOSEPOLICY_LEAST_RECENTLY_USED就是CURLCLOSEPOLICY_OLDEST，还存在另外三个，但是curl暂时还不支持。.

CURLOPT_CONNECTTIMEOUT  
在发起连接前等待的时间，如果设置为0，则不等待。

CURLOPT_DNS_CACHE_TIMEOUT  
设置在内存中保存DNS信息的时间，默认为120秒。

CURLOPT_FTPSSLAUTH  
The FTP authentication method (when is activated): CURLFTPAUTH_SSL (try SSL first), CURLFTPAUTH_TLS (try TLS first), or CURLFTPAUTH_DEFAULT (let cURL decide).

CURLOPT_HTTP_VERSION  
设置curl使用的HTTP协议，CURL_HTTP_VERSION_NONE（让curl自己判断），CURL_HTTP_VERSION_1_0（HTTP/1.0），CURL_HTTP_VERSION_1_1（HTTP/1.1）

CURLOPT_HTTPAUTH  
使用的HTTP验证方法，可选的值 有：CURLAUTH_BASIC，CURLAUTH_DIGEST，CURLAUTH_GSSNEGOTIATE，CURLAUTH_NTLM，CURLAUTH_ANY，CURLAUTH_ANYSAFE， 可以使用“|”操作符分隔多个值，curl让服务器选择一个支持最好的值，CURLAUTH_ANY等价于CURLAUTH_BASIC | CURLAUTH_DIGEST | CURLAUTH_GSSNEGOTIATE | CURLAUTH_NTLM，CURLAUTH_ANYSAFE等价于CURLAUTH_DIGEST | CURLAUTH_GSSNEGOTIATE | CURLAUTH_NTLM

CURLOPT_LOW_SPEED_LIMIT  
当传输速度小于CURLOPT_LOW_SPEED_LIMIT时，PHP会根据CURLOPT_LOW_SPEED_TIME来判断是否因太慢而取消传输。

CURLOPT_LOW_SPEED_TIME  
The number of seconds the transfer should be below CURLOPT_LOW_SPEED_LIMIT for PHP to consider the transfer too slow and abort.
当传输速度小于CURLOPT_LOW_SPEED_LIMIT时，PHP会根据CURLOPT_LOW_SPEED_TIME来判断是否因太慢而取消传输。

CURLOPT_MAXCONNECTS  
允许的最大连接数量，超过是会通过CURLOPT_CLOSEPOLICY决定应该停止哪些连接

CURLOPT_MAXREDIRS  
指定最多的HTTP重定向的数量，这个选项是和CURLOPT_FOLLOWLOCATION一起使用的。

CURLOPT_PORT  
一个可选的用来指定连接端口的量

CURLOPT_PROXYAUTH  
The HTTP authentication method(s) to use for the proxy connection. Use the same bitmasks as described in CURLOPT_HTTPAUTH. For proxy authentication, only CURLAUTH_BASIC and CURLAUTH_NTLM are currently supported.
CURLOPT_PROXYPORT
The port number of the proxy to connect to. This port number can also be set in CURLOPT_PROXY.
CURLOPT_PROXYTYPE
Either CURLPROXY_HTTP (default) or CURLPROXY_SOCKS5.

CURLOPT_RESUME_FROM  
在恢复传输时传递一个字节偏移量（用来断点续传）

CURLOPT_SSL_VERIFYHOST  
1 to check the existence of a common name in the SSL peer certificate.  
2 to check the existence of a common name and also verify that it matches the hostname provided.

CURLOPT_SSLVERSION  
The SSL version (2 or 3) to use. By default PHP will try to determine this itself, although in some cases this must be set manually.

CURLOPT_TIMECONDITION  
如果在CURLOPT_TIMEVALUE指定的某个时间以后被编辑过，则使用CURL_TIMECOND_IFMODSINCE返回页面，如果没有被修 改过，并且CURLOPT_HEADER为true，则返回一个"304 Not Modified"的header，CURLOPT_HEADER为false，则使用CURL_TIMECOND_ISUNMODSINCE，默认值为 CURL_TIMECOND_IFMODSINCE

CURLOPT_TIMEVALUE  
设置一个CURLOPT_TIMECONDITION使用的时间戳，在默认状态下使用的是CURL_TIMECOND_IFMODSINCE

CURLOPT_CAINFO  
The name of a file holding one or more certificates to verify the peer with. This only makes sense when used in combination with CURLOPT_SSL_VERIFYPEER.
CURLOPT_CAPATH
A directory that holds multiple CA certificates. Use this option alongside CURLOPT_SSL_VERIFYPEER.

CURLOPT_CUSTOMREQUEST  
A custom request method to use instead of "GET" or "HEAD" when doing a HTTP request. This is useful for doing "DELETE" or other, more obscure HTTP requests. Valid values are things like "GET", "POST", "CONNECT" and so on; i.e. Do not enter a whole HTTP request line here. For instance, entering "GET /index.html HTTP/1.0\r\n\r\n" would be incorrect.
Note: Don't do this without making sure the server supports the custom request method first.

CURLOPT_EGBSOCKET  
Like CURLOPT_RANDOM_FILE, except a filename to an Entropy Gathering Daemon socket.

CURLOPT_ENCODING  
header中“Accept-Encoding: ”部分的内容，支持的编码格式为："identity"，"deflate"，"gzip"。如果设置为空字符串，则表示支持所有的编码格式

CURLOPT_FTPPORT  
The value which will be used to get the IP address to use for the FTP "POST" instruction. The "POST" instruction tells the remote server to connect to our specified IP address. The string may be a plain IP address, a hostname, a network interface name (under Unix), or just a plain '-' to use the systems default IP address.

CURLOPT_INTERFACE  
在外部网络接口中使用的名称，可以是一个接口名，IP或者主机名。

CURLOPT_KRB4LEVEL  
KRB4(Kerberos 4)安全级别的设置，可以是一下几个值之一："clear"，"safe"，"confidential"，"private"。默认的值 为"private"，设置为null的时候表示禁用KRB4，现在KRB4安全仅能在FTP传输中使用。

CURLOPT_PROXY  
设置通过的HTTP代理服务器

CURLOPT_PROXYUSERPWD  
连接到代理服务器的，格式为“[username]:[password]”的用户名和密码。

CURLOPT_RANDOM_FILE  
设定存放SSL用到的随机数种子的文件名称

CURLOPT_RANGE  
设置HTTP传输范围，可以用“X-Y”的形式设置一个传输区间，如果有多个HTTP传输，则使用逗号分隔多个值，形如："X-Y,N-M"。

CURLOPT_REFERER  
设置header中"Referer: " 部分的值。

CURLOPT_SSL_CIPHER_LIST  
A list of ciphers to use for SSL. For example, RC4-SHA and TLSv1 are valid cipher lists.

CURLOPT_SSLCERT  
传递一个包含PEM格式证书的字符串。

CURLOPT_SSLCERTPASSWD  
传递一个包含使用CURLOPT_SSLCERT证书必需的密码。

CURLOPT_SSLCERTTYPE  
The format of the certificate. Supported formats are "PEM" (default), "DER", and "ENG".
CURLOPT_SSLENGINE
The identifier for the crypto engine of the private SSL key specified in CURLOPT_SSLKEY.
CURLOPT_SSLENGINE_DEFAULT
The identifier for the crypto engine used for asymmetric crypto operations.
CURLOPT_SSLKEY
The name of a file containing a private SSL key.
CURLOPT_SSLKEYPASSWD
The secret password needed to use the private SSL key specified in CURLOPT_SSLKEY.
Note: Since this option contains a sensitive password, remember to keep the PHP script it is contained within safe.
CURLOPT_SSLKEYTYPE
The key type of the private SSL key specified in CURLOPT_SSLKEY. Supported key types are "PEM" (default), "DER", and "ENG".

CURLOPT_HTTP200ALIASES  
设置不再以error的形式来处理HTTP 200的响应，格式为一个数组。

CURLOPT_POSTQUOTE  
An array of FTP commands to execute on the server after the FTP request has been performed.

CURLOPT_QUOTE  
An array of FTP commands to execute on the server prior to the FTP request.

CURLOPT_STDERR  
设置一个错误输出地址，值是一个资源类型，取代默认的STDERR。

CURLOPT_WRITEHEADER  
设置header部分内容的写入的文件地址，值是一个资源类型。

CURLOPT_HEADERFUNCTION  
设置一个回调函数，这个函数有两个参数，第一个是curl的资源句柄，第二个是输出的header数据。header数据的输出必须依赖这个函数，返回已写入的数据大小。

CURLOPT_PASSWDFUNCTION  
设置一个回调函数，有三个参数，第一个是curl的资源句柄，第二个是一个密码提示符，第三个参数是密码长度允许的最大值。返回密码的值。

CURLOPT_READFUNCTION  
设置一个回调函数，有两个参数，第一个是curl的资源句柄，第二个是读取到的数据。数据读取必须依赖这个函数。返回读取数据的大小，比如0或者EOF。

CURLOPT_WRITEFUNCTION  
设置一个回调函数，有两个参数，第一个是curl的资源句柄，第二个是写入的数据。数据写入必须依赖这个函数。返回精确的已写入数据的大小
