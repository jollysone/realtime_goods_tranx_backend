<?php

namespace App\Helpers;


class ErrorCode
{
    const OK                     = [0, '一切正常'];
    const UNKNOWN_ERROR          = [1000, '未知错误'];
    const SERVER_ERROR           = [1001, '服务器异常'];
    const ROUTER_NOT_FOUND       = [1002, '路由不存在'];
    const OPERATION_INVALID      = [1003, '非法操作'];
    const TOKEN_MISSING          = [1004, '缺少 Token'];
    const TOKEN_INVALID          = [1005, '登录过期'];
    const OPERATION_TIMEOUT      = [1006, '操作超时'];
    const PERMISSION_DENIED      = [1007, '无操作权限'];
    const THIRD_PART_API_ERROR   = [1008, '第三方接口异常'];
    const FORM_VALIDATE_FAILED   = [2001, '表单验证失败'];
    const RESOURCE_NOT_EXIST     = [2002, '资源不存在'];
    const RESOURCE_AMOUNT_LIMIT  = [2003, '资源数量过多'];
    const FILE_SIZE_INVALID      = [2004, '文件大小错误'];
    const FILE_FORMAT_INVALID    = [2005, '文件格式错误'];
    const USER_NOT_EXIST         = [2010, '用户不存在'];
    const ACCOUNT_OR_PWD_INVALID = [2011, '账号或密码错误'];
    const ACCOUNT_DISABLED       = [2012, '账号已禁用'];
    const MOBILE_EXISTED         = [2013, '手机号已被使用'];
    const USER_NUMBER_EXISTED    = [2014, '学号已被使用'];
    const CAPTCHA_ERROR          = [2020, '图片验证码错误'];

}