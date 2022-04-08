# Qiniu 云储存 Laravel 5/6/7/8/9 Storage版

## 说明

由于业务需要，基于 zgldh/qiniu-laravel-storage:0.10.3 进行了简单二次开发。
修复了在本地测试环境中读取filesystems.php中的七牛云配置异常的问题

业务场景：使用laravel-admin整合ueditor，并且配置七牛云的需要，该包仅是面向特定业务需求改写的包

环境：
+ php7.4 
+ laravel6
+ qiniu/php-sdk： "^7.4"
+ codingyu/ueditor: "^3.0"


---

基于 https://github.com/qiniu/php-sdk 开发

符合Laravel 5/6/7/8/9 的Storage用法。

## 注意

  从七牛获取到的`putTime`时间戳，是以 100纳秒 为单位的。 
  
  参考 https://developer.qiniu.com/kodo/api/1308/stat https://developer.qiniu.com/kodo/api/1284/list
  
  PHP 可以用 [Carbon](http://carbon.nesbot.com/docs/) `Carbon::createFromTimestampMs($putTime/10000)` 来保证最大精度
  
  
  JavaScript 可以用 [moment](http://momentjs.cn/docs/#/parsing/unix-offset/)  `moment(putTime/10000)` 来保证最大精度
   

## 安装

 - ```composer require zgldh/qiniu-laravel-storage:0.10.3```
 - ```config/app.php``` 里面的 ```providers``` 数组， 加上一行 ```zgldh\QiniuStorage\QiniuFilesystemServiceProvider::class```
 - ```config/filesystem.php``` 里面的 ```disks```数组加上：

```php

    'disks' => [
        ... ,
        'qiniu' => [
            'driver'  => 'qiniu',
            'domains' => [
                'default'   => 'xxxxx.com1.z0.glb.clouddn.com', //你的七牛域名
                'https'     => 'dn-yourdomain.qbox.me',         //你的HTTPS域名
                'custom'    => 'static.abc.com',                //Useless 没啥用，请直接使用上面的 default 项
             ],
            'access_key'=> '',  //AccessKey
            'secret_key'=> '',  //SecretKey
            'bucket'    => '',  //Bucket名字
            'notify_url'=> '',  //持久化处理回调地址
            'access'    => 'public',  //空间访问控制 public 或 private
            'hotlink_prevention_key' => 'afc89ff8bd2axxxxxxxxxxxxxxbb', // CDN 时间戳防盗链的 key。 设置为 null 则不启用本功能。
//            'hotlink_prevention_key' => 'cbab68a279xxxxxxxxxxab509a', // 同上，备用
        ],
    ],

```

 - 完成



## 官方SDK / 手册

 - https://github.com/qiniu/php-sdk
 - http://developer.qiniu.com/docs/v6/sdk/php-sdk.html


