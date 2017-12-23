<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2017 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

define('THINK_VERSION', '5.0.13');
define('THINK_START_TIME', microtime(true));
define('THINK_START_MEM', memory_get_usage());
define('EXT', '.php');
define('DS', DIRECTORY_SEPARATOR);
#定义框架的目录，框架目录属于项目目录下，与应用目录同级
defined('THINK_PATH') or define('THINK_PATH', __DIR__ . DS);
#框架的库目录，属于框架目录下的概念
define('LIB_PATH', THINK_PATH . 'library' . DS);
#库目录下还有核心类目录
define('CORE_PATH', LIB_PATH . 'think' . DS);
define('TRAIT_PATH', LIB_PATH . 'traits' . DS);
#应用目录，这个在入口文件index.php里就定义了
defined('APP_PATH') or define('APP_PATH', dirname($_SERVER['SCRIPT_FILENAME']) . DS);
#项目的路径，项目的概念比应用要大
defined('ROOT_PATH') or define('ROOT_PATH', dirname(realpath(APP_PATH)) . DS);
#扩展类文件，是基于项目目录的
defined('EXTEND_PATH') or define('EXTEND_PATH', ROOT_PATH . 'extend' . DS);
#第三方composer管理的目录，也是在项目目录下
defined('VENDOR_PATH') or define('VENDOR_PATH', ROOT_PATH . 'vendor' . DS);
#运行时目录，也是在项目目录下
defined('RUNTIME_PATH') or define('RUNTIME_PATH', ROOT_PATH . 'runtime' . DS);
#日志目录，在运行时目录下
defined('LOG_PATH') or define('LOG_PATH', RUNTIME_PATH . 'log' . DS);
#缓存目录，在运行时目录
defined('CACHE_PATH') or define('CACHE_PATH', RUNTIME_PATH . 'cache' . DS);
#临时文件目录，也在运行时目录下
defined('TEMP_PATH') or define('TEMP_PATH', RUNTIME_PATH . 'temp' . DS);
#配置目录，等同于应用目录，哦，是吗？
defined('CONF_PATH') or define('CONF_PATH', APP_PATH); // 配置文件目录
#配置文件后缀
defined('CONF_EXT') or define('CONF_EXT', EXT); // 配置文件后缀
#环境变量的配置前缀
defined('ENV_PREFIX') or define('ENV_PREFIX', 'PHP_'); // 环境变量的配置前缀

// 环境常量，是命令行还是web
define('IS_CLI', PHP_SAPI == 'cli' ? true : false);
#是否是windows，当然还有Linux,macos等
define('IS_WIN', strpos(PHP_OS, 'WIN') !== false);

// 载入Loader类，框架的开端，一定少不了自动加载模块，这个Loader.php就是完成psr4自动加载机制的实现类
require CORE_PATH . 'Loader.php';

// 如果有环境变量文件，环境变量文件里的内容格式是ini格式的，就用php的函数parse_ini_file来解析，直接返回数组
//第二个参数是true,故返回多维数组
if (is_file(ROOT_PATH . '.env')) {
    $env = parse_ini_file(ROOT_PATH . '.env', true);

    //环境变量？这是什么意思呢？刚查了一下互联网，最初听说是在安装java时
    //后来学习Linux又加深了。所以，环境变量就是web服务器所在的操作系统级别的变量，比如操作系统的登录用户是谁
    //当前所在的文件目录等，这些都是环境变量。
    //当然了，php岂能修改上述几个变量，为了安全问题，肯定是不能让php来修改某些环境变量的，这里无非就是把
    //一些变量设置到操作系统级别，这样在整个php全局都能访问。
    //生命周期，不是永远的，仅仅在本次请求里，请求结束，本次设置的也就失效了。
    foreach ($env as $key => $val) {
        $name = ENV_PREFIX . strtoupper($key);

        if (is_array($val)) {
            foreach ($val as $k => $v) {
                $item = $name . '_' . strtoupper($k);
                putenv("$item=$v");
            }
        } else {
            putenv("$name=$val");
        }
    }
}

// 刚刚把Loader类加载进来还不行，还得运行这个方法，完成一系列的逻辑，具体看方法源码
\think\Loader::register();

// 注册错误和异常处理机制，大家注意，这个\think\Error并没有主动加载，是因为上一行已经注册了自动加载机制
//此后框架的文件，自然都能自动加载进来了，对不对哈哈。
\think\Error::register();

// 使用核心类文件Config的set方法，把配置信息加载到代码（内存）中
\think\Config::set(include THINK_PATH . 'convention' . EXT);
