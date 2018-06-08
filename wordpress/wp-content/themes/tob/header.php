<!DOCTYPE HTML>
<html>
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta http-equiv="cache-control" content="no-siteapp">
<title><?php echo _title() ?></title>
<?php wp_head(); ?>
<!--[if lt IE 9]><script src="<?php echo get_stylesheet_directory_uri() ?>/js/html5.js"></script><![endif]-->
</head>
<body <?php body_class(_bodyclass()) ?>>
<header class="header">
	<div class="container">
		<?php _the_logo(); ?>
		<div class="sitenav">
			<ul><?php _the_menu('nav'); ?></ul>
		</div>
		<span class="sitenav-on"><i class="fa">&#xe605;</i></span>
		<span class="sitenav-mask"></span>
		<?php if( _hui('ac_weixin') || _hui('ac_weibo') || _hui('ac_tqq') || _hui('ac_qzone') ){ ?>
			<div class="accounts">
				<?php if( _hui('ac_weixin') ): ?>
					<a class="account-weixin" href="javascript:;"><i class="fa">&#xe60e;</i>
						<div class="account-popover"><div class="account-popover-content"><img src="<?php echo _hui('ac_weixin') ?>"></div></div>
					</a>
				<?php endif; ?>
				<?php if( _hui('ac_weibo') ): ?><a class="account-weibo" target="_blank" href="<?php echo _hui('ac_weibo') ?>" tipsy title="关注微博"><i class="fa">&#xe608;</i></a><?php endif; ?>
				<?php if( _hui('ac_tqq') ): ?><a class="account-tqq" target="_blank" href="<?php echo _hui('ac_tqq') ?>" tipsy title="关注腾讯微博"><i class="fa">&#xe60c;</i></a><?php endif; ?>
				<?php if( _hui('ac_qzone') ): ?><a class="account-qzone" target="_blank" href="<?php echo _hui('ac_qzone') ?>" tipsy title="关注QQ空间"><i class="fa">&#xe607;</i></a><?php endif; ?>
			</div>
		<?php } ?>
		<?php if( _hui('nav_search') ){ ?>
			<span class="searchstart-on"><i class="fa">&#xe600;</i></span>
			<span class="searchstart-off"><i class="fa">&#xe606;</i></span>
			<form method="get" class="searchform" action="<?php echo esc_url( home_url( '/' ) ) ?>" >
				<button tabindex="3" class="sbtn" type="submit"><i class="fa">&#xe600;</i></button><input tabindex="2" class="sinput" name="s" type="text" placeholder="输入关键字" value="<?php echo htmlspecialchars($s) ?>">
			</form>
		<?php } ?>
	</div>
</header>