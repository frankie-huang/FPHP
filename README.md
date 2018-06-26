# FPHP
Frankie的MVC框架（重度阉割版MVC框架，仅写于自用。他人慎用，后果自负2333）



## 一. 部署

### 配置文件

将Core/Conf/config_template.php重命名为config.php，并将里面的配置信息自行填写。

### 隐藏URL里的入口文件index.php

#### Apache

1. httpd.conf配置文件中加载了mod_rewrite.so模块
2. AllowOverride None 将None改为 All
3. 把下面的内容保存为.htaccess文件放到应用入口文件的同级目录下

```
<IfModule mod_rewrite.c>
 RewriteEngine on
 RewriteCond %{REQUEST_FILENAME} !-d
 RewriteCond %{REQUEST_FILENAME} !-f
 RewriteRule ^(.*)$ index.php/$1 [QSA,PT,L]
</IfModule>
```

#### Nginx

在nginx的配置文件的location / {} 部分加入如下配置：

```shell
location / { // ...... 省略部分代码
	if (!-e $request_filename) {
		rewrite  ^(.*)$  /index.php?s=$1  last;
		break;
	}
}
```

如果你的FPHP安装在二级目录，Nginx的伪静态方法设置如下，其中youdomain是所在的目录名称。

```shell
location /youdomain/ {
    if (!-e $request_filename){
        rewrite  ^/youdomain/(.*)$  /youdomain/index.php?s=$1  last;
    }
}
```


## 二. 使用

支持C函数读取配置

支持I函数读取GET和POST数据

支持M函数初始化数据库连接

支持$this->view->display()加载前端页面