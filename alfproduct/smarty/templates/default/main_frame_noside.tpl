<!--{php}-->
add_filter('wp_title', 'set_page_wp_title', 1);
get_header();
<!--{/php}-->

<div style="width:960px;float:left;padding:0 10px 20px;">
<!--{include file=$include_template_file}-->
</div>

<!--{php}-->get_footer();<!--{/php}-->
