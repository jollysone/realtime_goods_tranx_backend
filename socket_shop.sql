-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- 主机： localhost
-- 生成日期： 2019-04-15 07:34:38
-- 服务器版本： 5.7.25
-- PHP 版本： 7.2.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `socket_shop`
--

-- --------------------------------------------------------

--
-- 表的结构 `browse_logs`
--

CREATE TABLE `browse_logs` (
  `ai` int(10) UNSIGNED NOT NULL,
  `id` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户',
  `goods_id` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '商品',
  `stay_time` int(11) NOT NULL DEFAULT '0' COMMENT '停留时长',
  `source` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '访问来源',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='浏览记录';

-- --------------------------------------------------------

--
-- 表的结构 `categories`
--

CREATE TABLE `categories` (
  `ai` int(10) UNSIGNED NOT NULL,
  `id` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '上级',
  `level` int(11) NOT NULL DEFAULT '0' COMMENT '层级',
  `name` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '名称',
  `icon_class` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '图标',
  `amount` int(11) NOT NULL DEFAULT '0' COMMENT '商品数量',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='类别';

--
-- 转存表中的数据 `categories`
--

INSERT INTO `categories` (`ai`, `id`, `parent_id`, `level`, `name`, `icon_class`, `amount`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'd7deb0b29af69f9b23c3c7ddaaf46f67', '', 1, '手机数码', '', 0, '2019-04-10 08:00:21', '2019-04-10 08:18:47', NULL),
(2, '86c7f5e08ae9fa85cfb4b8686ae35443', 'd7deb0b29af69f9b23c3c7ddaaf46f67', 2, '手机', '', 0, '2019-04-10 08:00:21', '2019-04-10 08:18:47', NULL),
(3, 'b82566a6c4ef7c4db18cf8700a895f5f', 'd7deb0b29af69f9b23c3c7ddaaf46f67', 2, '相机', '', 0, '2019-04-10 08:00:21', '2019-04-10 08:00:21', NULL),
(4, '4673e0a32d0d014f41be77192dfa5781', 'd7deb0b29af69f9b23c3c7ddaaf46f67', 2, '耳机音响', '', 0, '2019-04-10 08:00:21', '2019-04-10 08:00:21', NULL),
(5, '334c9067a40ba6d7b0d634ab4a227ce1', 'd7deb0b29af69f9b23c3c7ddaaf46f67', 2, '存储卡', '', 0, '2019-04-10 08:00:21', '2019-04-10 08:00:21', NULL),
(6, '5db96c7ee5a9d292c9296f189c2afd27', '', 1, '电脑办公', '', 1, '2019-04-10 08:00:21', '2019-04-10 08:28:55', NULL),
(7, 'cbcc5b1c42c32d5d01251b48ae50ba97', '5db96c7ee5a9d292c9296f189c2afd27', 2, '笔记本', '', 0, '2019-04-10 08:00:21', '2019-04-10 08:00:21', NULL),
(8, '1ab441d9db158284cf631d936d4880a5', '5db96c7ee5a9d292c9296f189c2afd27', 2, '键鼠', '', 1, '2019-04-10 08:00:21', '2019-04-10 08:28:55', NULL),
(9, 'c60b1cd4407cc9959c4844d075e19ec7', '5db96c7ee5a9d292c9296f189c2afd27', 2, '显示器', '', 0, '2019-04-10 08:00:21', '2019-04-10 08:00:21', NULL),
(10, 'bf44ba2032e5a3e2c01ffebabd342fe5', '5db96c7ee5a9d292c9296f189c2afd27', 2, '办公耗材', '', 0, '2019-04-10 08:00:21', '2019-04-10 08:00:21', NULL),
(11, 'd076eb6c7610c0d120e9b744278d47f0', '', 1, '家具厨具', '', 0, '2019-04-10 08:00:21', '2019-04-10 08:00:21', NULL),
(12, '185f440fe9ffe96be55ca3b15a44a999', 'd076eb6c7610c0d120e9b744278d47f0', 2, '厨房配件', '', 0, '2019-04-10 08:00:21', '2019-04-10 08:00:21', NULL),
(13, '402681012e893ab403b61999d5310491', 'd076eb6c7610c0d120e9b744278d47f0', 2, '收纳用品', '', 0, '2019-04-10 08:00:21', '2019-04-10 08:00:21', NULL),
(14, '7810db2fd2f5e3f1cb703eeae3aefb32', 'd076eb6c7610c0d120e9b744278d47f0', 2, '灯具', '', 0, '2019-04-10 08:00:21', '2019-04-10 08:00:21', NULL),
(15, 'ca3c4a493211e7cee9e98ed6a68fc634', 'd076eb6c7610c0d120e9b744278d47f0', 2, '桌椅', '', 0, '2019-04-10 08:00:21', '2019-04-10 08:00:21', NULL),
(16, '1e69a77279ee0ac92bc3188d5f6f5cf7', '', 1, '服装鞋帽', '', 0, '2019-04-10 08:00:21', '2019-04-10 08:00:21', NULL),
(17, '6a36ba7512f76f7301ff27904abf2393', '1e69a77279ee0ac92bc3188d5f6f5cf7', 2, '男装', '', 0, '2019-04-10 08:00:21', '2019-04-10 08:00:21', NULL),
(18, '257bbed2a0dd687dd6cfef999ee1dcd4', '1e69a77279ee0ac92bc3188d5f6f5cf7', 2, '女装', '', 0, '2019-04-10 08:00:21', '2019-04-10 08:00:21', NULL),
(19, 'f7eb75119bddac99f3b49e6cd572948d', '1e69a77279ee0ac92bc3188d5f6f5cf7', 2, '男鞋', '', 0, '2019-04-10 08:00:21', '2019-04-10 08:00:21', NULL),
(20, '208997b26f2eade15771b4ac36c3965f', '1e69a77279ee0ac92bc3188d5f6f5cf7', 2, '女鞋', '', 0, '2019-04-10 08:00:21', '2019-04-10 08:00:21', NULL),
(21, 'b6f162baed7db8232c8cf966d065db1d', '', 1, '箱包首饰', '', 0, '2019-04-10 08:00:21', '2019-04-10 08:00:21', NULL),
(22, 'a2d06b43239cfc2795a704f55d865b4a', 'b6f162baed7db8232c8cf966d065db1d', 2, '拉杆箱', '', 0, '2019-04-10 08:00:21', '2019-04-10 08:00:21', NULL),
(23, 'c157107b3c6439b27035e780e7bf4550', 'b6f162baed7db8232c8cf966d065db1d', 2, '书包', '', 0, '2019-04-10 08:00:21', '2019-04-10 08:00:21', NULL),
(24, '6715295f44c7a048c6ba465c41a3a6da', 'b6f162baed7db8232c8cf966d065db1d', 2, '手包', '', 0, '2019-04-10 08:00:21', '2019-04-10 08:00:21', NULL),
(25, 'b0d356f5f853f8dca6daa0bdbeaadfc1', 'b6f162baed7db8232c8cf966d065db1d', 2, '首饰饰品', '', 0, '2019-04-10 08:00:21', '2019-04-10 08:00:21', NULL),
(26, '5d077c72a9379466ea54a154c2e3298c', '', 1, '运动户外', '', 0, '2019-04-10 08:00:21', '2019-04-10 08:00:21', NULL),
(27, '400aa5c05dd12136b3dad39b98302b70', '5d077c72a9379466ea54a154c2e3298c', 2, '单车', '', 0, '2019-04-10 08:00:21', '2019-04-10 08:00:21', NULL),
(28, '6210682286a39066426ef9b1fbba09d9', '5d077c72a9379466ea54a154c2e3298c', 2, '运动护具', '', 0, '2019-04-10 08:00:21', '2019-04-10 08:00:21', NULL),
(29, 'e3142c7cb43a2204455cee431e4f2760', '5d077c72a9379466ea54a154c2e3298c', 2, '户外用品', '', 0, '2019-04-10 08:00:21', '2019-04-10 08:00:21', NULL),
(30, 'bfadb507b90a1b6606038aba7f196df4', '5d077c72a9379466ea54a154c2e3298c', 2, '体育用品', '', 0, '2019-04-10 08:00:21', '2019-04-10 08:00:21', NULL),
(31, '0ff37603a2f2dbb75e958314e75fe666', '', 1, '食品特产', '', 1, '2019-04-10 08:00:21', '2019-04-10 08:18:47', NULL),
(32, '38aa7e33f4cd1fd53914e7099f75bd94', '0ff37603a2f2dbb75e958314e75fe666', 2, '新鲜水果', '', 0, '2019-04-10 08:00:21', '2019-04-10 08:00:21', NULL),
(33, '4f6a0036c15ee99e357d5c13eb5dc31a', '0ff37603a2f2dbb75e958314e75fe666', 2, '进口食品', '', 0, '2019-04-10 08:00:21', '2019-04-10 08:00:21', NULL),
(34, '0166b05666d1fcc5690b539dd897f4ec', '0ff37603a2f2dbb75e958314e75fe666', 2, '地方特产', '', 1, '2019-04-10 08:00:21', '2019-04-10 08:18:47', NULL),
(35, '59f9f42c6d14b97df3b7147a2a72fb8f', '0ff37603a2f2dbb75e958314e75fe666', 2, '饮料茗茶', '', 0, '2019-04-10 08:00:21', '2019-04-10 08:00:21', NULL),
(36, '08b9f7c68b2277219718e70b4d24e166', '', 1, '图书文娱', '', 0, '2019-04-10 08:00:21', '2019-04-10 08:00:21', NULL),
(37, '89b237d7f013522f429fe7986fa89d28', '08b9f7c68b2277219718e70b4d24e166', 2, '教材教辅', '', 0, '2019-04-10 08:00:21', '2019-04-10 08:00:21', NULL),
(38, 'c839181ea45d66136bfa0a8e1c01790f', '08b9f7c68b2277219718e70b4d24e166', 2, '科学技术', '', 0, '2019-04-10 08:00:21', '2019-04-10 08:00:21', NULL),
(39, '11d5d3044430fd861a96a8e3058dcc66', '08b9f7c68b2277219718e70b4d24e166', 2, '文娱音像', '', 0, '2019-04-10 08:00:21', '2019-04-10 08:00:21', NULL),
(40, 'f0b98913644a75364c9e829aac86057c', '08b9f7c68b2277219718e70b4d24e166', 2, '电子书', '', 0, '2019-04-10 08:00:21', '2019-04-10 08:00:21', NULL);

-- --------------------------------------------------------

--
-- 表的结构 `configs`
--

CREATE TABLE `configs` (
  `id` int(10) UNSIGNED NOT NULL,
  `key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '参数名',
  `value` text COLLATE utf8mb4_unicode_ci COMMENT '参数值',
  `remark` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='系统参数';

--
-- 转存表中的数据 `configs`
--

INSERT INTO `configs` (`id`, `key`, `value`, `remark`, `created_at`, `updated_at`) VALUES
(1, 'order_sn', '20190414121915001', '最新订单号', '2019-04-10 08:30:13', '2019-04-14 04:19:15');

-- --------------------------------------------------------

--
-- 表的结构 `credits`
--

CREATE TABLE `credits` (
  `ai` int(10) UNSIGNED NOT NULL,
  `id` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户',
  `base_score` int(11) NOT NULL DEFAULT '0' COMMENT '基础信用分',
  `buy_score` int(11) NOT NULL DEFAULT '0' COMMENT '购买信用分',
  `sell_score` int(11) NOT NULL DEFAULT '0' COMMENT '卖出信用分',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='信用分';

-- --------------------------------------------------------

--
-- 表的结构 `credit_logs`
--

CREATE TABLE `credit_logs` (
  `ai` int(10) UNSIGNED NOT NULL,
  `id` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户',
  `old_score` int(11) NOT NULL DEFAULT '0' COMMENT '原信用分',
  `change_score` int(11) NOT NULL DEFAULT '0' COMMENT '变更信用分',
  `remark` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '原因',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='信用分记录';

-- --------------------------------------------------------

--
-- 表的结构 `departments`
--

CREATE TABLE `departments` (
  `ai` int(10) UNSIGNED NOT NULL,
  `id` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '名称',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='系别';

-- --------------------------------------------------------

--
-- 表的结构 `files`
--

CREATE TABLE `files` (
  `ai` int(10) UNSIGNED NOT NULL,
  `id` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '路径',
  `thumb_path` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '缩略图',
  `extension` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '扩展名',
  `size` int(11) NOT NULL DEFAULT '0' COMMENT '大小',
  `thumb_size` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '缩略图大小',
  `remark` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注',
  `ip` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '上传 IP',
  `uploaded_by_id` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '上传用户',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='文件';

--
-- 转存表中的数据 `files`
--

INSERT INTO `files` (`ai`, `id`, `path`, `thumb_path`, `extension`, `size`, `thumb_size`, `remark`, `ip`, `uploaded_by_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '8fdb63ac8d9e525ff0eec1acdf194b73', 'goods/b2020cde014c5e3b4d37c2985ce99e14/goods_8a610451098fdcb1.jpg', 'goods/b2020cde014c5e3b4d37c2985ce99e14/goods_8a610451098fdcb1.jpg', 'jpg', 19952, 19952, '', '116.3.195.153', 'b2020cde014c5e3b4d37c2985ce99e14', '2019-04-10 08:16:37', '2019-04-10 08:16:37', NULL),
(2, '4b5b4d06137fa22b17fb1be967c077c4', 'goods/8e7dacd5e81e383b3d46145dbc9c0533/goods_ef48e0eff0ff958e.png', 'goods/8e7dacd5e81e383b3d46145dbc9c0533/goods_ef48e0eff0ff958e.png', 'png', 3285, 3285, '', '123.185.223.224', '8e7dacd5e81e383b3d46145dbc9c0533', '2019-04-10 08:28:41', '2019-04-10 08:28:41', NULL);

-- --------------------------------------------------------

--
-- 表的结构 `goods`
--

CREATE TABLE `goods` (
  `ai` int(10) UNSIGNED NOT NULL,
  `id` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户',
  `number` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '编号',
  `title` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '标题',
  `price` decimal(6,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  `category_id` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '类型',
  `hot_degree` int(11) NOT NULL DEFAULT '0' COMMENT '热度',
  `pic_id` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '图片',
  `description` text COLLATE utf8mb4_unicode_ci COMMENT '描述',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='商品';

--
-- 转存表中的数据 `goods`
--

INSERT INTO `goods` (`ai`, `id`, `user_id`, `number`, `title`, `price`, `status`, `category_id`, `hot_degree`, `pic_id`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'a19206dd760c4cd81a8506097a2b8977', 'b2020cde014c5e3b4d37c2985ce99e14', '', '测试商品001', '100.00', 1, '0166b05666d1fcc5690b539dd897f4ec', 0, '8fdb63ac8d9e525ff0eec1acdf194b73', '<p>图文描述<br/></p><p><img src=\"http://files.socket-shop.demo.qizhit.com/ueditor/image/20190410/1554884249130835.jpg\" title=\"1554884249130835.jpg\" alt=\"8ff35184699191a8.jpg\"/></p>', '2019-04-10 08:17:34', '2019-04-10 08:18:47', NULL),
(2, '2aefd9a6eaa547e9df29465f2f8862ec', '8e7dacd5e81e383b3d46145dbc9c0533', '', 'asdasd', '123.00', 1, '1ab441d9db158284cf631d936d4880a5', 0, '4b5b4d06137fa22b17fb1be967c077c4', '<p>茶几上的卡号发客户打款首付款海口市大会丰厚的撒谎疯狂的撒谎分开了大师傅进口量的撒回复卡的说法快结婚的撒可富很快的撒谎分开了大师傅开大声发卡都是废话卡的说法看</p>', '2019-04-10 08:28:55', '2019-04-10 08:28:55', NULL);

-- --------------------------------------------------------

--
-- 表的结构 `grades`
--

CREATE TABLE `grades` (
  `ai` int(10) UNSIGNED NOT NULL,
  `id` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '名称',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='年级';

-- --------------------------------------------------------

--
-- 表的结构 `illegal_logs`
--

CREATE TABLE `illegal_logs` (
  `ai` int(10) UNSIGNED NOT NULL,
  `id` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户',
  `goods_id` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '商品',
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '类型',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='违规记录';

-- --------------------------------------------------------

--
-- 表的结构 `labels`
--

CREATE TABLE `labels` (
  `ai` int(10) UNSIGNED NOT NULL,
  `id` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '名称',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='标签';

-- --------------------------------------------------------

--
-- 表的结构 `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 转存表中的数据 `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2018_09_07_025207_create_configs_table', 1),
(2, '2019_04_01_025154_create_tokens_table', 1),
(3, '2019_04_01_102301_create_files_table', 1),
(4, '2019_04_01_102301_create_users_table', 1),
(5, '2019_04_01_102302_create_departments_table', 1),
(6, '2019_04_01_102302_create_grades_table', 1),
(7, '2019_04_01_102303_create_labels_table', 1),
(8, '2019_04_01_102304_create_browse_logs_table', 1),
(9, '2019_04_01_102305_create_goods_table', 1),
(10, '2019_04_01_102306_create_categories_table', 1),
(11, '2019_04_01_102307_create_illegal_logs_table', 1),
(12, '2019_04_01_102307_create_orders_table', 1),
(13, '2019_04_01_102308_create_credits_table', 1),
(14, '2019_04_01_102309_create_credit_logs_table', 1),
(15, '2019_04_09_164240_add_pic_id_and_description_column_to_goods_table', 1),
(16, '2019_04_09_164707_add_parent_id_and_level_column_to_categories_table', 1),
(17, '2019_04_09_165329_add_icon_class_column_to_categories_table', 1),
(18, '2019_04_09_171807_add_amount_column_to_categories_table', 1),
(19, '2019_04_09_175531_change_uploaded_by_column_to_uploaded_by_id_column_from_files_table', 1),
(20, '2019_04_10_012917_add_user_id_column_to_goods_table', 1);

-- --------------------------------------------------------

--
-- 表的结构 `orders`
--

CREATE TABLE `orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `sn` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `buyer_id` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '买家',
  `seller_id` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '卖家',
  `goods_id` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '商品',
  `amount` int(11) NOT NULL DEFAULT '0' COMMENT '数量',
  `price` decimal(6,2) NOT NULL DEFAULT '0.00' COMMENT '金额',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='订单';

--
-- 转存表中的数据 `orders`
--

INSERT INTO `orders` (`id`, `sn`, `buyer_id`, `seller_id`, `goods_id`, `amount`, `price`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '20190410163013001', '8e7dacd5e81e383b3d46145dbc9c0533', 'b2020cde014c5e3b4d37c2985ce99e14', 'a19206dd760c4cd81a8506097a2b8977', 1, '100.00', 1, '2019-04-10 08:30:13', '2019-04-10 08:30:13', NULL),
(2, '20190410213122001', '8e7dacd5e81e383b3d46145dbc9c0533', 'b2020cde014c5e3b4d37c2985ce99e14', 'a19206dd760c4cd81a8506097a2b8977', 1, '100.00', 1, '2019-04-10 13:31:22', '2019-04-10 13:31:22', NULL),
(3, '20190414121459001', 'ccb6b9aa20c51e17a51e531f2eac5382', 'b2020cde014c5e3b4d37c2985ce99e14', 'a19206dd760c4cd81a8506097a2b8977', 1, '100.00', 1, '2019-04-14 04:14:59', '2019-04-14 04:14:59', NULL),
(4, '20190414121915001', 'ccb6b9aa20c51e17a51e531f2eac5382', 'b2020cde014c5e3b4d37c2985ce99e14', 'a19206dd760c4cd81a8506097a2b8977', 1, '100.00', 1, '2019-04-14 04:19:15', '2019-04-14 04:19:15', NULL);

-- --------------------------------------------------------

--
-- 表的结构 `tokens`
--

CREATE TABLE `tokens` (
  `id` int(10) UNSIGNED NOT NULL,
  `app_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '应用类型',
  `user_id` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户',
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '标识内容',
  `ip` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '登录 IP',
  `expire_at` timestamp NULL DEFAULT NULL COMMENT '过期时间',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户登录标识';

--
-- 转存表中的数据 `tokens`
--

INSERT INTO `tokens` (`id`, `app_type`, `user_id`, `token`, `ip`, `expire_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'b2020cde014c5e3b4d37c2985ce99e14', '77152c2416ec6c82cc691b91f007ea4de9b72f48e50627efb689e234bd918e72', '116.3.195.153', '2019-04-17 08:15:47', '2019-04-10 08:15:47', '2019-04-10 08:25:04', '2019-04-10 08:25:04'),
(2, 1, 'b2020cde014c5e3b4d37c2985ce99e14', '20decadd08fde0d4b046172dd8d72494b1d771850f688ce2e0c08a1ecd4e02c8', '116.3.195.153', '2019-04-17 08:25:04', '2019-04-10 08:25:04', '2019-04-10 08:25:04', NULL),
(3, 1, '8e7dacd5e81e383b3d46145dbc9c0533', 'dce0d5a46c6ec1477ce9ae72df64e9c2b663abad4635966269a868ed5d251931', '123.185.223.224', '2019-04-17 08:27:21', '2019-04-10 08:27:21', '2019-04-10 08:27:45', '2019-04-10 08:27:45'),
(4, 1, '8e7dacd5e81e383b3d46145dbc9c0533', 'b9be4a0d9ef133b8b7dc542070e5ab309123a0b86be61ec1c066b91005977575', '123.185.223.27', '2019-04-17 08:27:45', '2019-04-10 08:27:45', '2019-04-10 08:27:45', NULL),
(5, 1, 'ccb6b9aa20c51e17a51e531f2eac5382', '7813148ba0b95f73d11e4e04d40eff92be1e6e575f9d28ca3f98a89baa0eb583', '123.151.77.123', '2019-04-21 03:41:13', '2019-04-14 03:41:13', '2019-04-14 04:09:44', '2019-04-14 04:09:44'),
(6, 1, 'ccb6b9aa20c51e17a51e531f2eac5382', '948b41bb204fec9585d00bdb00ad056246cd837198dcef54995356f247e33461', '124.93.196.21', '2019-04-21 04:09:44', '2019-04-14 04:09:44', '2019-04-14 04:09:44', NULL);

-- --------------------------------------------------------

--
-- 表的结构 `users`
--

CREATE TABLE `users` (
  `ai` int(10) UNSIGNED NOT NULL,
  `id` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `number` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '编号',
  `phone` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '手机',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '密码',
  `true_name` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '真实姓名',
  `gender` tinyint(4) NOT NULL DEFAULT '0' COMMENT '性别',
  `avatar_id` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '头像',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '邮箱',
  `department_id` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '系别',
  `grade_id` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '年级',
  `nick` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '昵称',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  `role_type` int(11) NOT NULL DEFAULT '0' COMMENT '角色类型',
  `label_id` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '标签',
  `login_at` timestamp NULL DEFAULT NULL COMMENT '最后登录',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户';

--
-- 转存表中的数据 `users`
--

INSERT INTO `users` (`ai`, `id`, `number`, `phone`, `password`, `true_name`, `gender`, `avatar_id`, `email`, `department_id`, `grade_id`, `nick`, `status`, `role_type`, `label_id`, `login_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'b2020cde014c5e3b4d37c2985ce99e14', 't0001', '18842606505', '$2y$10$oNjj3OWpIaMKhYfHic56.eI/5GA6dCLRpgi/GEm7zRc7jbohaNuHS', '', 0, '', '', '', '', 'Ethan', 1, 1, '', '2019-04-10 08:25:04', '2019-04-10 08:15:31', '2019-04-10 08:25:04', NULL),
(2, '8e7dacd5e81e383b3d46145dbc9c0533', '515', '13011176338', '$2y$10$ei6jH7c4thVift7nQgcEPe./8tCFAerSOJpbO5F6naGELRKoz/euC', '', 0, '', '', '', '', 'JollySone', 1, 1, '', '2019-04-10 08:27:45', '2019-04-10 08:26:28', '2019-04-10 08:27:45', NULL),
(5, 'ccb6b9aa20c51e17a51e531f2eac5382', '5150000', '18018990339', '$2y$10$zt8YnPk.tG0TEsiOcC1neOPg3FNP6fkxWMNbtjZsJQfmA0DX3WWZC', '', 0, '', '', '', '', 'jollysone', 1, 1, '', '2019-04-14 04:09:44', '2019-04-14 03:41:01', '2019-04-14 04:09:44', NULL);

--
-- 转储表的索引
--

--
-- 表的索引 `browse_logs`
--
ALTER TABLE `browse_logs`
  ADD PRIMARY KEY (`ai`),
  ADD UNIQUE KEY `browse_logs_id_unique` (`id`);

--
-- 表的索引 `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`ai`),
  ADD UNIQUE KEY `categories_id_unique` (`id`);

--
-- 表的索引 `configs`
--
ALTER TABLE `configs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `configs_key_unique` (`key`);

--
-- 表的索引 `credits`
--
ALTER TABLE `credits`
  ADD PRIMARY KEY (`ai`),
  ADD UNIQUE KEY `credits_id_unique` (`id`);

--
-- 表的索引 `credit_logs`
--
ALTER TABLE `credit_logs`
  ADD PRIMARY KEY (`ai`),
  ADD UNIQUE KEY `credit_logs_id_unique` (`id`);

--
-- 表的索引 `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`ai`),
  ADD UNIQUE KEY `departments_id_unique` (`id`);

--
-- 表的索引 `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`ai`),
  ADD UNIQUE KEY `files_id_unique` (`id`);

--
-- 表的索引 `goods`
--
ALTER TABLE `goods`
  ADD PRIMARY KEY (`ai`),
  ADD UNIQUE KEY `goods_id_unique` (`id`);

--
-- 表的索引 `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`ai`),
  ADD UNIQUE KEY `grades_id_unique` (`id`);

--
-- 表的索引 `illegal_logs`
--
ALTER TABLE `illegal_logs`
  ADD PRIMARY KEY (`ai`),
  ADD UNIQUE KEY `illegal_logs_id_unique` (`id`);

--
-- 表的索引 `labels`
--
ALTER TABLE `labels`
  ADD PRIMARY KEY (`ai`),
  ADD UNIQUE KEY `labels_id_unique` (`id`);

--
-- 表的索引 `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_sn_unique` (`sn`);

--
-- 表的索引 `tokens`
--
ALTER TABLE `tokens`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ai`),
  ADD UNIQUE KEY `users_id_unique` (`id`),
  ADD UNIQUE KEY `users_number_unique` (`number`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `browse_logs`
--
ALTER TABLE `browse_logs`
  MODIFY `ai` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `categories`
--
ALTER TABLE `categories`
  MODIFY `ai` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- 使用表AUTO_INCREMENT `configs`
--
ALTER TABLE `configs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `credits`
--
ALTER TABLE `credits`
  MODIFY `ai` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `credit_logs`
--
ALTER TABLE `credit_logs`
  MODIFY `ai` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `departments`
--
ALTER TABLE `departments`
  MODIFY `ai` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `files`
--
ALTER TABLE `files`
  MODIFY `ai` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `goods`
--
ALTER TABLE `goods`
  MODIFY `ai` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `grades`
--
ALTER TABLE `grades`
  MODIFY `ai` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `illegal_logs`
--
ALTER TABLE `illegal_logs`
  MODIFY `ai` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `labels`
--
ALTER TABLE `labels`
  MODIFY `ai` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- 使用表AUTO_INCREMENT `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- 使用表AUTO_INCREMENT `tokens`
--
ALTER TABLE `tokens`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- 使用表AUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `ai` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
