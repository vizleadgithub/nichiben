<!--{php}-->
add_filter('wp_title', 'set_page_wp_title', 1);
get_header();
<!--{/php}-->

<div style="width:220px;float:left;margin-right:13px;">
<!--{php}-->get_sidebar(1);<!--{/php}-->
</div>

<div style="width:730px;float:left;padding-right:13px;">
<!--{include file=$include_template_file}-->
</div>

<!--{php}-->get_footer();<!--{/php}-->
