-- phpMyAdmin SQL Dump
-- version 4.4.15.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2017-05-27 16:57:16
-- 服务器版本： 5.5.48-log
-- PHP Version: 7.1.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_book`
--

-- --------------------------------------------------------

--
-- 表的结构 `think_admin`
--

CREATE TABLE IF NOT EXISTS `think_admin` (
  `id` int(11) NOT NULL COMMENT '主键,自增长',
  `gid` varchar(50) NOT NULL COMMENT '所属群组:-1为超级管理员',
  `username` varchar(30) NOT NULL COMMENT '用户名',
  `password` varchar(32) NOT NULL COMMENT '用户密码',
  `hash` varchar(50) NOT NULL COMMENT '密码校验',
  `status` tinyint(1) NOT NULL COMMENT '状态:0正常;1锁定',
  `date` int(11) NOT NULL COMMENT '添加日期',
  `last_date` int(10) NOT NULL COMMENT '最后登录时间',
  `last_ip` varchar(15) NOT NULL COMMENT '最后登录IP',
  `dates` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='管理员表';

--
-- 转存表中的数据 `think_admin`
--

INSERT INTO `think_admin` (`id`, `gid`, `username`, `password`, `hash`, `status`, `date`, `last_date`, `last_ip`, `dates`) VALUES
(1, '-1', '524314430@qq.com', 'ba59abbe56e057f', '', 0, 1486708840, 1495870832, '192.168.5.1', 0),
(3, '52,49', 'test@qq.com', 'ba59abbe56e057f', '', 0, 1495856916, 1495857467, '192.168.5.1', 0);

-- --------------------------------------------------------

--
-- 表的结构 `think_book`
--

CREATE TABLE IF NOT EXISTS `think_book` (
  `id` int(11) NOT NULL COMMENT '主键，自增长',
  `book_name` varchar(50) DEFAULT NULL COMMENT '名称',
  `book_cate` int(11) NOT NULL COMMENT '种类',
  `book_desc` varchar(500) DEFAULT NULL COMMENT '简介',
  `book_no` int(11) NOT NULL COMMENT '书籍编号',
  `book_author` varchar(50) DEFAULT NULL COMMENT '作者',
  `book_publish` varchar(50) DEFAULT NULL COMMENT '出版社',
  `book_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '书籍价格',
  `book_mark` varchar(50) DEFAULT NULL COMMENT '书架号',
  `book_totals` int(11) NOT NULL DEFAULT '0' COMMENT '库存',
  `book_given` int(11) NOT NULL DEFAULT '0' COMMENT '借出数',
  `book_left` int(11) NOT NULL DEFAULT '0' COMMENT '剩余数',
  `book_status` int(11) NOT NULL DEFAULT '0' COMMENT '状态0正常1禁用',
  `book_date` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `dates` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='图书表';

--
-- 转存表中的数据 `think_book`
--

INSERT INTO `think_book` (`id`, `book_name`, `book_cate`, `book_desc`, `book_no`, `book_author`, `book_publish`, `book_price`, `book_mark`, `book_totals`, `book_given`, `book_left`, `book_status`, `book_date`, `dates`) VALUES
(1, 'PHP基础教程（第4版）', 4, '由贾菡等编译的《PHP基础教程(第4版)》以通俗易懂的语言向初学者介绍了PHP语言的基本概念、使用方法和注意事项。全书通过丰富的示例，引领读者逐步掌握这门流行的Web开发语言，使读者能够上手编写适用于常用场景的PHP脚本。', 0, 'Larry Ullman 贾菡/刘彦博（译者）', '人民邮电出版社', '65.00', '1馆1楼54架', 10, 0, 10, 0, 1495778210, 1495874223),
(2, 'Node与Express开发', 4, '本书系统讲解了使用Express开发动态Web应用的流程和步骤。作者不仅讲授了开发公共站点及REST API的基础知识，同时还讲解了构建单页、多页及混合Web应用的规划方式及实践。具体而言，第1~5章介绍Node 和Express，搭建一个示例网站的骨架，讨论测试和QA。第6~12章介绍Node中更重要的结构，讲解模板，介绍cookies、会话和表单处理器，探讨中间件以及从服务器发送电子邮件。第13~15章讨论持久化、URL路由、API的编写、流行的MVC范式。第18~22章讨论安全、社交媒体集成以及网站的调试、启用和维护。', 0, '布朗 (Ethan Brown) (作者), 吴海星 (译者), 苏文 (译者)', '人民邮电出版社', '54.00', '1馆1楼56架', 10, 0, 10, 0, 1495782258, 0);

-- --------------------------------------------------------

--
-- 表的结构 `think_category`
--

CREATE TABLE IF NOT EXISTS `think_category` (
  `id` int(11) NOT NULL COMMENT '主键自增',
  `cate_name` varchar(50) DEFAULT NULL COMMENT '种类名称',
  `cate_status` int(11) NOT NULL DEFAULT '0' COMMENT '是否可用：0可用，1不可用',
  `cate_date` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `dates` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='图书种类表';

--
-- 转存表中的数据 `think_category`
--

INSERT INTO `think_category` (`id`, `cate_name`, `cate_status`, `cate_date`, `dates`) VALUES
(2, '期刊', 0, 1495764834, 0),
(3, '杂志', 0, 1495764910, 0),
(4, '计算机', 0, 1495764920, 0),
(5, '艺术类', 0, 1495764933, 0),
(6, '工程类', 0, 1495764943, 0),
(7, '文学类', 0, 1495764959, 0);

-- --------------------------------------------------------

--
-- 表的结构 `think_config`
--

CREATE TABLE IF NOT EXISTS `think_config` (
  `id` int(11) NOT NULL COMMENT '主键，自增长',
  `title` varchar(150) NOT NULL COMMENT '网站名',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `date` int(10) NOT NULL COMMENT '修改日期',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `dates` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='网站配置';

--
-- 转存表中的数据 `think_config`
--

INSERT INTO `think_config` (`id`, `title`, `price`, `date`, `status`, `dates`) VALUES
(1, '图书管理系统', '0.02', 1476674789, 0, 1495862700);

-- --------------------------------------------------------

--
-- 表的结构 `think_model`
--

CREATE TABLE IF NOT EXISTS `think_model` (
  `id` int(11) NOT NULL COMMENT '主键，自增长',
  `fid` int(11) NOT NULL DEFAULT '0' COMMENT '父节点：0,为顶级',
  `title` varchar(30) DEFAULT NULL COMMENT '英文标识',
  `name` varchar(50) DEFAULT NULL COMMENT '中文名',
  `info` varchar(250) DEFAULT NULL COMMENT '简介',
  `ico` varchar(50) DEFAULT NULL COMMENT '图标',
  `pic` varchar(500) DEFAULT NULL COMMENT '控制器图片',
  `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序,越小越靠前',
  `show` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否显示导航,0显示,1不显示',
  `status` int(10) NOT NULL DEFAULT '0' COMMENT '状态:0正常,1锁定',
  `date` int(10) DEFAULT '0' COMMENT '添加日期',
  `dates` int(10) NOT NULL DEFAULT '0' COMMENT '修改日期'
) ENGINE=MyISAM AUTO_INCREMENT=59 DEFAULT CHARSET=utf8 COMMENT='控制器';

--
-- 转存表中的数据 `think_model`
--

INSERT INTO `think_model` (`id`, `fid`, `title`, `name`, `info`, `ico`, `pic`, `sort`, `show`, `status`, `date`, `dates`) VALUES
(1, 0, 'control', '控制器管理', '控制器管理', ' fa-list-alt', '', 100, 0, 0, 1433401695, 1486176270),
(2, 1, 'index', '查看控制器', '查看控制器', 'fa-list-alt', '', 2, 0, 0, 1396104162, 1486176254),
(58, 52, 'exceed', '已经逾期', '已经逾期', 'fa-list-alt', NULL, 100, 0, 0, 1495785285, 0),
(57, 52, 'expire', '将要逾期', '将要逾期', ' fa-calendar', NULL, 100, 0, 0, 1495785115, 0),
(56, 52, 'index', '借阅图书', '借阅图书', 'glyphicon glyphicon-th', NULL, 100, 0, 0, 1495784931, 1495784950),
(54, 53, 'punish', '逾期惩罚设置', '逾期惩罚设置', 'fa-gavel', NULL, 100, 0, 0, 1495783821, 0),
(55, 41, 'administrator', '系统管理员', '系统管理员', 'glyphicon glyphicon-user', NULL, 100, 0, 0, 1495784713, 1495785603),
(53, 0, 'setting', '系统设置', '系统设置', 'fa-cog', NULL, 100, 0, 0, 1495783692, 0),
(41, 0, 'reader', '用户管理', '用户管理', 'fa-user', NULL, 100, 0, 0, 1494315773, 1495694607),
(42, 41, 'index', '读者管理', '用户管理', 'fa-user-md', NULL, 100, 0, 0, 1494315820, 1495785591),
(51, 49, 'books', '书籍管理', '图书管理', 'fa-bookmark', NULL, 100, 0, 0, 1495767225, 0),
(50, 49, 'index', '分类管理', '图书管理', 'glyphicon glyphicon-signal', NULL, 100, 0, 0, 1495761236, 1495782433),
(52, 0, 'borrow', '借书管理', '借书管理', 'glyphicon glyphicon-list-alt', NULL, 100, 0, 0, 1495782553, 1495784818),
(49, 0, 'book', '图书管理', '图书管理', 'fa-book', NULL, 100, 0, 0, 1495761204, 1495761268);

-- --------------------------------------------------------

--
-- 表的结构 `think_reader`
--

CREATE TABLE IF NOT EXISTS `think_reader` (
  `id` int(11) NOT NULL COMMENT '主键，自增长',
  `read_no` varchar(20) DEFAULT NULL COMMENT '借书号',
  `read_name` varchar(50) DEFAULT NULL COMMENT '读者姓名',
  `read_class` varchar(50) NOT NULL COMMENT '班级',
  `read_contac` varchar(50) DEFAULT NULL COMMENT '联系方式',
  `read_status` int(11) NOT NULL DEFAULT '0' COMMENT '读者状态：0正常，1禁用，2挂失',
  `read_time` int(11) NOT NULL DEFAULT '0' COMMENT '操作时间',
  `dates` int(11) NOT NULL COMMENT '时间戳'
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='读者表';

--
-- 转存表中的数据 `think_reader`
--

INSERT INTO `think_reader` (`id`, `read_no`, `read_name`, `read_class`, `read_contac`, `read_status`, `read_time`, `dates`) VALUES
(3, '755OQJCNVA8', '刘海', '信息管理15级移动C', '13584866592', 0, 1495759190, 1495761113),
(4, '755OQJBE6F5', '吴桐', '经管14级财务C', '15698546592', 0, 1495759469, 0),
(6, '755OQJBQ0F3', '柳岩', '经管系14级财务A', '13584866592', 0, 1495759669, 1495759891);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `think_admin`
--
ALTER TABLE `think_admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `think_book`
--
ALTER TABLE `think_book`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `think_category`
--
ALTER TABLE `think_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `think_config`
--
ALTER TABLE `think_config`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `think_model`
--
ALTER TABLE `think_model`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sort` (`sort`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `think_reader`
--
ALTER TABLE `think_reader`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `think_admin`
--
ALTER TABLE `think_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键,自增长',AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `think_book`
--
ALTER TABLE `think_book`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键，自增长',AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `think_category`
--
ALTER TABLE `think_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键自增',AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `think_config`
--
ALTER TABLE `think_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键，自增长',AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `think_model`
--
ALTER TABLE `think_model`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键，自增长',AUTO_INCREMENT=59;
--
-- AUTO_INCREMENT for table `think_reader`
--
ALTER TABLE `think_reader`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键，自增长',AUTO_INCREMENT=8;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
