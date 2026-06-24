<?php /* Smarty version 2.6.31, created on 2025-03-03 15:45:42
         compiled from main_frame.tpl */ ?>
<?php 
add_filter('wp_title', 'set_page_wp_title', 1);
get_header();
 ?>

<div style="width:220px;float:left;margin-left:13px;list-style-type:none;">
<?php get_sidebar(1); ?>
</div>

<div style="width:730px;float:left;padding-right:13px;">
<?php get_template_part('nav_search'); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['include_template_file'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>

<!--<div style="width:230px;float:left;">-->
<!--</div>-->

<?php get_footer(); ?>