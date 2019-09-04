<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::namespace('Api')->group(function () {
    // 测试用
    Route::prefix('test/')->group(function () {
        // 测试
        Route::any('', 'TestController@test');
        // 清空缓存
        Route::any('flush-cache', 'TestController@flushCache');
    });

    // 图片验证码
    Route::prefix('captcha/')->group(function () {
        // 获取验证码
        Route::patch('', 'CaptchaController@create');
    });

    // UEditor
    Route::any('ueditor/main', 'UEditorController@main');

    // 系别
    Route::prefix('departments/')->group(function () {
        // 全部列表
        Route::get('all', 'DepartmentController@getAll');
    });

    // 年级
    Route::prefix('grades/')->group(function () {
        // 全部列表
        Route::get('all', 'GradeController@getAll');
    });

    // 商品类别
    Route::prefix('categories/')->group(function () {
        // 全部列表
        Route::get('all', 'CategoryController@getAll');
    });

    // Token
    Route::prefix('token/')->group(function () {
        // 登录 by 密码
        Route::post('by-pwd', 'TokenController@createByPwd');
    });

    // 用户
    Route::prefix('user/')->group(function () {
        // 注册
        Route::post('', 'UserController@post');
        // 重设密码
        Route::put('password/by-retrieve', 'UserController@putPasswordByRetrieve');
    });

    // 商品
    Route::prefix('goods/')->group(function () {
        // 详情
        Route::get('', 'GoodsController@get');
        // 搜索
        Route::get('many', 'GoodsController@getMany')->middleware(['pagination']);
    });

    // 需要登录
    Route::middleware(['token'])->group(function () {
        // 用户
        Route::prefix('user/')->group(function () {
            // 账号信息
            Route::get('current', 'UserController@getCurrent');
            // 修改账号信息
            Route::put('profile', 'UserController@putProfile');
        });
        // 文件
        Route::prefix('file/')->group(function () {
            // 上传
            Route::post('', 'FileController@post');
        });
        // 商品
        Route::prefix('goods/')->group(function () {
            // 发布商品
            Route::post('', 'GoodsController@post');
            // 修改商品
            Route::put('', 'GoodsController@put');
            // 我发布的商品列表
            Route::get('mine', 'GoodsController@getManyOfMine')->middleware(['pagination']);
            // 删除商品
            Route::delete('', 'GoodsController@delete');
            // 推荐商品
            Route::get('recommend', 'GoodsController@getRecommend');
        });
        // 订单
        Route::prefix('order/')->group(function () {
            // 创建订单
            Route::post('', 'OrderController@post');
            // 订单详情
            Route::get('', 'OrderController@get');
            // 取消订单
            Route::put('cancel', 'OrderController@putCancel');
            // 交易成功
            Route::put('done', 'OrderController@putDone');
            // 延长交易时间
            Route::put('extend-timeout', 'OrderController@putExtendTimeout');
        });
        // 订单
        Route::prefix('orders/')->group(function () {
            // 订单列表
            Route::get('', 'OrderController@getMany')->middleware(['pagination']);
            // 卖出订单列表
            Route::get('sell', 'OrderController@getManyOfSell')->middleware(['pagination']);
        });
        // 浏览记录
        Route::prefix('browse-logs/')->group(function () {
            // 列表
            Route::get('', 'BrowseLogController@getMany')->middleware(['pagination']);
        });
        // 浏览记录
        Route::prefix('browse-log/')->group(function () {
            // 创建记录
            Route::post('', 'BrowseLogController@post');
            // 更新记录
            Route::put('', 'BrowseLogController@put');
        });
    });
    // 平台管理
    Route::prefix('admin/')->namespace('Admin')->group(function () {
        // Token
        Route::prefix('token/')->group(function () {
            // 登录 by 密码
            Route::post('by-pwd', 'TokenController@createByPwd');
        });
        // 需要管理员登录
        Route::middleware(['token:' . User::ROLE_TYPE_ADMIN])->group(function () {
            // 用户
            Route::prefix('user/')->group(function () {
                // 管理员资料
                Route::patch('current', 'UserController@getCurrent');
                // 用户详情
                Route::patch('', 'UserController@get');
                // 创建/编辑用户
                Route::post('', 'UserController@post');
            });
            // 用户
            Route::prefix('users/')->group(function () {
                // 用户列表
                Route::patch('', 'UserController@getMany')->middleware(['pagination']);
            });
            // 订单
            Route::prefix('order/')->group(function () {
                // 删除
                Route::delete('', 'OrderController@delete');
            });
            // 订单
            Route::prefix('orders/')->group(function () {
                // 列表
                Route::patch('', 'OrderController@getMany')->middleware(['pagination']);
            });
            // 年级
            Route::prefix('grades/')->group(function () {
                // 列表
                Route::patch('', 'GradeController@getMany')->middleware(['pagination']);
            });
            // 年级
            Route::prefix('grade/')->group(function () {
                // 详情
                Route::patch('', 'GradeController@get');
                // 创建/编辑
                Route::post('', 'GradeController@post');
            });
            // 系别
            Route::prefix('departments/')->group(function () {
                // 列表
                Route::patch('', 'DepartmentController@getMany')->middleware(['pagination']);
            });
            // 系别
            Route::prefix('department/')->group(function () {
                // 详情
                Route::patch('', 'DepartmentController@get');
                // 创建/编辑
                Route::post('', 'DepartmentController@post');
            });
            // 商品类别
            Route::prefix('categories/')->group(function () {
                // 列表
                Route::patch('', 'CategoryController@getMany')->middleware(['pagination']);
            });
            // 商品类别
            Route::prefix('category/')->group(function () {
                // 详情
                Route::patch('', 'CategoryController@get');
                // 创建/编辑
                Route::post('', 'CategoryController@post');
            });
        });
    });
});