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

namespace think;

class Env
{
    /**
     * 获取环境变量值
     * @access public
     * @param  string $name    环境变量名（支持二级 . 号分割）
     * @param  string $default 默认值
     * @return mixed
     * 当初设置时使用putenv，这里当然就用getenv了，都是php原生函数
     * 不知为啥还弄个Env类干啥，有点多余了，既然有get方法，为啥没有set方法呢？是吧
     */
    public static function get($name, $default = null)
    {
        $result = getenv(ENV_PREFIX . strtoupper(str_replace('.', '_', $name)));

        //因为布尔值在环境变量的存储特殊性，转换为字符串了，所以这里再转换真正的布尔
        if (false !== $result) {
            if ('false' === $result) {
                $result = false;
            } elseif ('true' === $result) {
                $result = true;
            }

            return $result;
        }

        return $default;
    }
}
