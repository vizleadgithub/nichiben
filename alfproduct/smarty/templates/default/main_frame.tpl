<!--{php}-->
add_filter('wp_title', 'set_page_wp_title', 1);
get_header();
<!--{/php}-->

<div style="width:220px;float:left;margin-left:13px;list-style-type:none;">
<!--{php}-->get_sidebar(1);<!--{/php}-->
</div>

<div style="width:730px;float:left;padding-right:13px;">
<!--{php}-->get_template_part('nav_search');<!--{/php}-->
<!--{include file=$include_template_file}-->
</div>

<!--<div style="width:230px;float:left;">-->
<!--{* php}-->get_sidebar(2);<!--{/php *}-->
<!--{* php}-->get_sidebar(3);<!--{/php *}-->
<!--{* php}-->get_sidebar(4);<!--{/php *}-->
<!--</div>-->

<!--{php}-->get_footer();<!--{/php}-->
