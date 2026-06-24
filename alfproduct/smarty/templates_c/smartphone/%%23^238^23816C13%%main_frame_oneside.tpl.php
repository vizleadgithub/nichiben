<?php /* Smarty version 2.6.27, created on 2014-01-20 21:18:46
         compiled from main_frame_oneside.tpl */ ?>
<?php 
add_filter('wp_title', 'set_page_wp_title', 1);
get_header();
 ?>

<div style="width:220px;float:left;margin-right:13px;">
<?php get_sidebar(1); ?>
</div>

<div style="width:730px;float:left;padding-right:13px;">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['include_template_file'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>

<?php get_footer(); ?>