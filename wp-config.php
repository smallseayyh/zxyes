<?php
/**
 * WordPress基础配置文件。
 *
 * 这个文件被安装程序用于自动生成wp-config.php配置文件，
 * 您可以不使用网站，您需要手动复制这个文件，
 * 并重命名为“wp-config.php”，然后填入相关信息。
 *
 * 本文件包含以下配置选项：
 *
 * * MySQL设置
 * * 密钥
 * * 数据库表名前缀
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL 设置 - 具体信息来自您正在使用的主机 ** //
/** WordPress数据库的名称 */
define( 'DB_NAME', 'm.zxm666.top' );

/** MySQL数据库用户名 */
define( 'DB_USER', 'yanyh' );

/** MySQL数据库密码 */
define( 'DB_PASSWORD', 'yanyh1990' );

/** MySQL主机 */
define( 'DB_HOST', '120.24.71.180' );

/** 创建数据表时默认的文字编码 */
define( 'DB_CHARSET', 'utf8mb4' );

/** 数据库整理类型。如不确定请勿更改 */
define( 'DB_COLLATE', '' );

/**#@+
 * 身份认证密钥与盐。
 *
 * 修改为任意独一无二的字串！
 * 或者直接访问{@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org密钥生成服务}
 * 任何修改都会导致所有cookies失效，所有用户将必须重新登录。
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'P(QMi%kA59!/8fml#P{:q*_9x1I^I)Gg4L1KVsW:&Xe[UhO`]<wBNO5&2ly<CXdB' );
define( 'SECURE_AUTH_KEY',  'vw./b<l7+G>R<e[oeU._#>S;~)5O}@Ic]Pfj+?92Z/:dcD^^8L]A.T3Vh?JdZ7}j' );
define( 'LOGGED_IN_KEY',    '[+S81iJux#zjGzo7@dnI)=-jj%!j.6ayK4AM_3xxJ#XpYcP-eoj![[jrpKTS}MI<' );
define( 'NONCE_KEY',        ']x5d<,_=a6rnDR@:fz)&UfR )IVky.WkadZn<^0,Qn,u8d|9Cu1irh|4Cro(aMdx' );
define( 'AUTH_SALT',        '9sMTFl]zvf%RDw4FNa~[&+T8^i_dK[$0e .Dof2)`BhE<)xEa.b#-9j!|v[a&8qk' );
define( 'SECURE_AUTH_SALT', '7:E&&?L{.`#!>6;a3gkqp! $f|f,k!I{0YY<Ha !8;fo(VGr&w+iD.^/W]CD~S1n' );
define( 'LOGGED_IN_SALT',   'ZeuY6U]tr%583?Q g?06`BF?C0.|6X?zD+2.j@3gm6]Ph18+@`y?mRncZjl7IIFu' );
define( 'NONCE_SALT',       'qQgt/c|Yvu]GJ#3de!:P)<C.)^WZ)Gz(9*_~=fQ[bS56.f|6jPj@Fm$wXcE~ss&C' );

/**#@-*/

/**
 * WordPress数据表前缀。
 *
 * 如果您有在同一数据库内安装多个WordPress的需求，请为每个WordPress设置
 * 不同的数据表前缀。前缀名只能为数字、字母加下划线。
 */
$table_prefix = 'wp_';

/**
 * 开发者专用：WordPress调试模式。
 *
 * 将这个值改为true，WordPress将显示所有用于开发的提示。
 * 强烈建议插件开发者在开发环境中启用WP_DEBUG。
 *
 * 要获取其他能用于调试的信息，请访问文档。
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define('WP_DEBUG', false);

/* 好了！请不要再继续编辑。请保存本文件。使用愉快！ */

/** WordPress目录的绝对路径。 */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** 设置WordPress变量和包含文件。 */
require_once( ABSPATH . 'wp-settings.php' );
