<?php /* Smarty version 2.6.31, created on 2025-03-03 17:30:44
         compiled from main_frame_noside.tpl */ ?>
<?php 
add_filter('wp_title', 'set_page_wp_title', 1);
get_header();
 ?>

<div style="width:960px;float:left;padding:0 10px 20px;">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['include_template_file'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>

<?php get_footer(); ?>