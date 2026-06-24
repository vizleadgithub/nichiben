<?php
/**
 * WordPress core upgrade functionality.
 *
 * @package WordPress
 * @subpackage Administration
 * @since 2.7.0
 */

/**
 * Stores files to be deleted.
 *
 * Bundled theme files should not be included in this list.
 *
 * @since 2.7.0
 *
 * @global array $_old_files
 * @var array
 * @name $_old_files
 */
global $_old_files;

$_old_files = array(
	// 2.0
	'cp-files/import-b2.php',
	'cp-files/import-blogger.php',
	'cp-files/import-greymatter.php',
	'cp-files/import-livejournal.php',
	'cp-files/import-mt.php',
	'cp-files/import-rss.php',
	'cp-files/import-textpattern.php',
	'cp-files/quicktags.js',
	'wp-images/fade-butt.png',
	'wp-images/get-firefox.png',
	'wp-images/header-shadow.png',
	'wp-images/smilies',
	'wp-images/wp-small.png',
	'wp-images/wpminilogo.png',
	'wp.php',
	// 2.0.8
	'cp-includes/js/tinymce/plugins/inlinepopups/readme.txt',
	// 2.1
	'cp-files/edit-form-ajax-cat.php',
	'cp-files/execute-pings.php',
	'cp-files/inline-uploading.php',
	'cp-files/link-categories.php',
	'cp-files/list-manipulation.js',
	'cp-files/list-manipulation.php',
	'cp-includes/comment-functions.php',
	'cp-includes/feed-functions.php',
	'cp-includes/functions-compat.php',
	'cp-includes/functions-formatting.php',
	'cp-includes/functions-post.php',
	'cp-includes/js/dbx-key.js',
	'cp-includes/js/tinymce/plugins/autosave/langs/cs.js',
	'cp-includes/js/tinymce/plugins/autosave/langs/sv.js',
	'cp-includes/links.php',
	'cp-includes/pluggable-functions.php',
	'cp-includes/template-functions-author.php',
	'cp-includes/template-functions-category.php',
	'cp-includes/template-functions-general.php',
	'cp-includes/template-functions-links.php',
	'cp-includes/template-functions-post.php',
	'cp-includes/wp-l10n.php',
	// 2.2
	'cp-files/cat-js.php',
	'cp-files/import/b2.php',
	'cp-includes/js/autosave-js.php',
	'cp-includes/js/list-manipulation-js.php',
	'cp-includes/js/wp-ajax-js.php',
	// 2.3
	'cp-files/admin-db.php',
	'cp-files/cat.js',
	'cp-files/categories.js',
	'cp-files/custom-fields.js',
	'cp-files/dbx-admin-key.js',
	'cp-files/edit-comments.js',
	'cp-files/install-rtl.css',
	'cp-files/install.css',
	'cp-files/upgrade-schema.php',
	'cp-files/upload-functions.php',
	'cp-files/upload-rtl.css',
	'cp-files/upload.css',
	'cp-files/upload.js',
	'cp-files/users.js',
	'cp-files/widgets-rtl.css',
	'cp-files/widgets.css',
	'cp-files/xfn.js',
	'cp-includes/js/tinymce/license.html',
	// 2.5
	'cp-files/css/upload.css',
	'cp-files/images/box-bg-left.gif',
	'cp-files/images/box-bg-right.gif',
	'cp-files/images/box-bg.gif',
	'cp-files/images/box-butt-left.gif',
	'cp-files/images/box-butt-right.gif',
	'cp-files/images/box-butt.gif',
	'cp-files/images/box-head-left.gif',
	'cp-files/images/box-head-right.gif',
	'cp-files/images/box-head.gif',
	'cp-files/images/heading-bg.gif',
	'cp-files/images/login-bkg-bottom.gif',
	'cp-files/images/login-bkg-tile.gif',
	'cp-files/images/notice.gif',
	'cp-files/images/toggle.gif',
	'cp-files/includes/upload.php',
	'cp-files/js/dbx-admin-key.js',
	'cp-files/js/link-cat.js',
	'cp-files/profile-update.php',
	'cp-files/templates.php',
	'cp-includes/images/wlw/WpComments.png',
	'cp-includes/images/wlw/WpIcon.png',
	'cp-includes/images/wlw/WpWatermark.png',
	'cp-includes/js/dbx.js',
	'cp-includes/js/fat.js',
	'cp-includes/js/list-manipulation.js',
	'cp-includes/js/tinymce/langs/en.js',
	'cp-includes/js/tinymce/plugins/autosave/editor_plugin_src.js',
	'cp-includes/js/tinymce/plugins/autosave/langs',
	'cp-includes/js/tinymce/plugins/directionality/images',
	'cp-includes/js/tinymce/plugins/directionality/langs',
	'cp-includes/js/tinymce/plugins/inlinepopups/css',
	'cp-includes/js/tinymce/plugins/inlinepopups/images',
	'cp-includes/js/tinymce/plugins/inlinepopups/jscripts',
	'cp-includes/js/tinymce/plugins/paste/images',
	'cp-includes/js/tinymce/plugins/paste/jscripts',
	'cp-includes/js/tinymce/plugins/paste/langs',
	'cp-includes/js/tinymce/plugins/spellchecker/classes/HttpClient.class.php',
	'cp-includes/js/tinymce/plugins/spellchecker/classes/TinyGoogleSpell.class.php',
	'cp-includes/js/tinymce/plugins/spellchecker/classes/TinyPspell.class.php',
	'cp-includes/js/tinymce/plugins/spellchecker/classes/TinyPspellShell.class.php',
	'cp-includes/js/tinymce/plugins/spellchecker/css/spellchecker.css',
	'cp-includes/js/tinymce/plugins/spellchecker/images',
	'cp-includes/js/tinymce/plugins/spellchecker/langs',
	'cp-includes/js/tinymce/plugins/spellchecker/tinyspell.php',
	'cp-includes/js/tinymce/plugins/wordpress/images',
	'cp-includes/js/tinymce/plugins/wordpress/langs',
	'cp-includes/js/tinymce/plugins/wordpress/wordpress.css',
	'cp-includes/js/tinymce/plugins/wphelp',
	'cp-includes/js/tinymce/themes/advanced/css',
	'cp-includes/js/tinymce/themes/advanced/images',
	'cp-includes/js/tinymce/themes/advanced/jscripts',
	'cp-includes/js/tinymce/themes/advanced/langs',
	// 2.5.1
	'cp-includes/js/tinymce/tiny_mce_gzip.php',
	// 2.6
	'cp-files/bookmarklet.php',
	'cp-includes/js/jquery/jquery.dimensions.min.js',
	'cp-includes/js/tinymce/plugins/wordpress/popups.css',
	'cp-includes/js/wp-ajax.js',
	// 2.7
	'cp-files/css/press-this-ie-rtl.css',
	'cp-files/css/press-this-ie.css',
	'cp-files/css/upload-rtl.css',
	'cp-files/edit-form.php',
	'cp-files/images/comment-pill.gif',
	'cp-files/images/comment-stalk-classic.gif',
	'cp-files/images/comment-stalk-fresh.gif',
	'cp-files/images/comment-stalk-rtl.gif',
	'cp-files/images/del.png',
	'cp-files/images/gear.png',
	'cp-files/images/media-button-gallery.gif',
	'cp-files/images/media-buttons.gif',
	'cp-files/images/postbox-bg.gif',
	'cp-files/images/tab.png',
	'cp-files/images/tail.gif',
	'cp-files/js/forms.js',
	'cp-files/js/upload.js',
	'cp-files/link-import.php',
	'cp-includes/images/audio.png',
	'cp-includes/images/css.png',
	'cp-includes/images/default.png',
	'cp-includes/images/doc.png',
	'cp-includes/images/exe.png',
	'cp-includes/images/html.png',
	'cp-includes/images/js.png',
	'cp-includes/images/pdf.png',
	'cp-includes/images/swf.png',
	'cp-includes/images/tar.png',
	'cp-includes/images/text.png',
	'cp-includes/images/video.png',
	'cp-includes/images/zip.png',
	'cp-includes/js/tinymce/tiny_mce_config.php',
	'cp-includes/js/tinymce/tiny_mce_ext.js',
	// 2.8
	'cp-files/js/users.js',
	'cp-includes/js/swfupload/plugins/swfupload.documentready.js',
	'cp-includes/js/swfupload/plugins/swfupload.graceful_degradation.js',
	'cp-includes/js/swfupload/swfupload_f9.swf',
	'cp-includes/js/tinymce/plugins/autosave',
	'cp-includes/js/tinymce/plugins/paste/css',
	'cp-includes/js/tinymce/utils/mclayer.js',
	'cp-includes/js/tinymce/wordpress.css',
	// 2.8.5
	'cp-files/import/btt.php',
	'cp-files/import/jkw.php',
	// 2.9
	'cp-files/js/page.dev.js',
	'cp-files/js/page.js',
	'cp-files/js/set-post-thumbnail-handler.dev.js',
	'cp-files/js/set-post-thumbnail-handler.js',
	'cp-files/js/slug.dev.js',
	'cp-files/js/slug.js',
	'cp-includes/gettext.php',
	'cp-includes/js/tinymce/plugins/wordpress/js',
	'cp-includes/streams.php',
	// MU
	'README.txt',
	'htaccess.dist',
	'index-install.php',
	'cp-files/css/mu-rtl.css',
	'cp-files/css/mu.css',
	'cp-files/images/site-admin.png',
	'cp-files/includes/mu.php',
	'cp-files/wpmu-admin.php',
	'cp-files/wpmu-blogs.php',
	'cp-files/wpmu-edit.php',
	'cp-files/wpmu-options.php',
	'cp-files/wpmu-themes.php',
	'cp-files/wpmu-upgrade-site.php',
	'cp-files/wpmu-users.php',
	'cp-includes/images/wordpress-mu.png',
	'cp-includes/wpmu-default-filters.php',
	'cp-includes/wpmu-functions.php',
	'wpmu-settings.php',
	// 3.0
	'cp-files/categories.php',
	'cp-files/edit-category-form.php',
	'cp-files/edit-page-form.php',
	'cp-files/edit-pages.php',
	'cp-files/images/admin-header-footer.png',
	'cp-files/images/browse-happy.gif',
	'cp-files/images/ico-add.png',
	'cp-files/images/ico-close.png',
	'cp-files/images/ico-edit.png',
	'cp-files/images/ico-viewpage.png',
	'cp-files/images/fav-top.png',
	'cp-files/images/screen-options-left.gif',
	'cp-files/images/wp-logo-vs.gif',
	'cp-files/images/wp-logo.gif',
	'cp-files/import',
	'cp-files/js/wp-gears.dev.js',
	'cp-files/js/wp-gears.js',
	'cp-files/options-misc.php',
	'cp-files/page-new.php',
	'cp-files/page.php',
	'cp-files/rtl.css',
	'cp-files/rtl.dev.css',
	'cp-files/update-links.php',
	'cp-files/wp-admin.css',
	'cp-files/wp-admin.dev.css',
	'cp-includes/js/codepress',
	'cp-includes/js/codepress/engines/khtml.js',
	'cp-includes/js/codepress/engines/older.js',
	'cp-includes/js/jquery/autocomplete.dev.js',
	'cp-includes/js/jquery/autocomplete.js',
	'cp-includes/js/jquery/interface.js',
	'cp-includes/js/scriptaculous/prototype.js',
	// Following file added back in 5.1, see #45645.
	//'cp-includes/js/tinymce/wp-tinymce.js',
	// 3.1
	'cp-files/edit-attachment-rows.php',
	'cp-files/edit-link-categories.php',
	'cp-files/edit-link-category-form.php',
	'cp-files/edit-post-rows.php',
	'cp-files/images/button-grad-active-vs.png',
	'cp-files/images/button-grad-vs.png',
	'cp-files/images/fav-arrow-vs-rtl.gif',
	'cp-files/images/fav-arrow-vs.gif',
	'cp-files/images/fav-top-vs.gif',
	'cp-files/images/list-vs.png',
	'cp-files/images/screen-options-right-up.gif',
	'cp-files/images/screen-options-right.gif',
	'cp-files/images/visit-site-button-grad-vs.gif',
	'cp-files/images/visit-site-button-grad.gif',
	'cp-files/link-category.php',
	'cp-files/sidebar.php',
	'cp-includes/classes.php',
	'cp-includes/js/tinymce/blank.htm',
	'cp-includes/js/tinymce/plugins/media/css/content.css',
	'cp-includes/js/tinymce/plugins/media/img',
	'cp-includes/js/tinymce/plugins/safari',
	// 3.2
	'cp-files/images/logo-login.gif',
	'cp-files/images/star.gif',
	'cp-files/js/list-table.dev.js',
	'cp-files/js/list-table.js',
	'cp-includes/default-embeds.php',
	'cp-includes/js/tinymce/plugins/wordpress/img/help.gif',
	'cp-includes/js/tinymce/plugins/wordpress/img/more.gif',
	'cp-includes/js/tinymce/plugins/wordpress/img/toolbars.gif',
	'cp-includes/js/tinymce/themes/advanced/img/fm.gif',
	'cp-includes/js/tinymce/themes/advanced/img/sflogo.png',
	// 3.3
	'cp-files/css/colors-classic-rtl.css',
	'cp-files/css/colors-classic-rtl.dev.css',
	'cp-files/css/colors-fresh-rtl.css',
	'cp-files/css/colors-fresh-rtl.dev.css',
	'cp-files/css/dashboard-rtl.dev.css',
	'cp-files/css/dashboard.dev.css',
	'cp-files/css/global-rtl.css',
	'cp-files/css/global-rtl.dev.css',
	'cp-files/css/global.css',
	'cp-files/css/global.dev.css',
	'cp-files/css/install-rtl.dev.css',
	'cp-files/css/login-rtl.dev.css',
	'cp-files/css/login.dev.css',
	'cp-files/css/ms.css',
	'cp-files/css/ms.dev.css',
	'cp-files/css/nav-menu-rtl.css',
	'cp-files/css/nav-menu-rtl.dev.css',
	'cp-files/css/nav-menu.css',
	'cp-files/css/nav-menu.dev.css',
	'cp-files/css/plugin-install-rtl.css',
	'cp-files/css/plugin-install-rtl.dev.css',
	'cp-files/css/plugin-install.css',
	'cp-files/css/plugin-install.dev.css',
	'cp-files/css/press-this-rtl.dev.css',
	'cp-files/css/press-this.dev.css',
	'cp-files/css/theme-editor-rtl.css',
	'cp-files/css/theme-editor-rtl.dev.css',
	'cp-files/css/theme-editor.css',
	'cp-files/css/theme-editor.dev.css',
	'cp-files/css/theme-install-rtl.css',
	'cp-files/css/theme-install-rtl.dev.css',
	'cp-files/css/theme-install.css',
	'cp-files/css/theme-install.dev.css',
	'cp-files/css/widgets-rtl.dev.css',
	'cp-files/css/widgets.dev.css',
	'cp-files/includes/internal-linking.php',
	'cp-includes/images/admin-bar-sprite-rtl.png',
	'cp-includes/js/jquery/ui.button.js',
	'cp-includes/js/jquery/ui.core.js',
	'cp-includes/js/jquery/ui.dialog.js',
	'cp-includes/js/jquery/ui.draggable.js',
	'cp-includes/js/jquery/ui.droppable.js',
	'cp-includes/js/jquery/ui.mouse.js',
	'cp-includes/js/jquery/ui.position.js',
	'cp-includes/js/jquery/ui.resizable.js',
	'cp-includes/js/jquery/ui.selectable.js',
	'cp-includes/js/jquery/ui.sortable.js',
	'cp-includes/js/jquery/ui.tabs.js',
	'cp-includes/js/jquery/ui.widget.js',
	'cp-includes/js/l10n.dev.js',
	'cp-includes/js/l10n.js',
	'cp-includes/js/tinymce/plugins/wplink/css',
	'cp-includes/js/tinymce/plugins/wplink/img',
	'cp-includes/js/tinymce/plugins/wplink/js',
	'cp-includes/js/tinymce/themes/advanced/img/wpicons.png',
	'cp-includes/js/tinymce/themes/advanced/skins/wp_theme/img/butt2.png',
	'cp-includes/js/tinymce/themes/advanced/skins/wp_theme/img/button_bg.png',
	'cp-includes/js/tinymce/themes/advanced/skins/wp_theme/img/down_arrow.gif',
	'cp-includes/js/tinymce/themes/advanced/skins/wp_theme/img/fade-butt.png',
	'cp-includes/js/tinymce/themes/advanced/skins/wp_theme/img/separator.gif',
	// Don't delete, yet: 'wp-rss.php',
	// Don't delete, yet: 'wp-rdf.php',
	// Don't delete, yet: 'wp-rss2.php',
	// Don't delete, yet: 'wp-commentsrss2.php',
	// Don't delete, yet: 'wp-atom.php',
	// Don't delete, yet: 'wp-feed.php',
	// 3.4
	'cp-files/images/gray-star.png',
	'cp-files/images/logo-login.png',
	'cp-files/images/star.png',
	'cp-files/index-extra.php',
	'cp-files/network/index-extra.php',
	'cp-files/user/index-extra.php',
	'cp-files/images/screenshots/admin-flyouts.png',
	'cp-files/images/screenshots/coediting.png',
	'cp-files/images/screenshots/drag-and-drop.png',
	'cp-files/images/screenshots/help-screen.png',
	'cp-files/images/screenshots/media-icon.png',
	'cp-files/images/screenshots/new-feature-pointer.png',
	'cp-files/images/screenshots/welcome-screen.png',
	'cp-includes/css/editor-buttons.css',
	'cp-includes/css/editor-buttons.dev.css',
	'cp-includes/js/tinymce/plugins/paste/blank.htm',
	'cp-includes/js/tinymce/plugins/wordpress/css',
	'cp-includes/js/tinymce/plugins/wordpress/editor_plugin.dev.js',
	'cp-includes/js/tinymce/plugins/wordpress/img/embedded.png',
	'cp-includes/js/tinymce/plugins/wordpress/img/more_bug.gif',
	'cp-includes/js/tinymce/plugins/wordpress/img/page_bug.gif',
	'cp-includes/js/tinymce/plugins/wpdialogs/editor_plugin.dev.js',
	'cp-includes/js/tinymce/plugins/wpeditimage/css/editimage-rtl.css',
	'cp-includes/js/tinymce/plugins/wpeditimage/editor_plugin.dev.js',
	'cp-includes/js/tinymce/plugins/wpfullscreen/editor_plugin.dev.js',
	'cp-includes/js/tinymce/plugins/wpgallery/editor_plugin.dev.js',
	'cp-includes/js/tinymce/plugins/wpgallery/img/gallery.png',
	'cp-includes/js/tinymce/plugins/wplink/editor_plugin.dev.js',
	// Don't delete, yet: 'wp-pass.php',
	// Don't delete, yet: 'wp-register.php',
	// 3.5
	'cp-files/gears-manifest.php',
	'cp-files/includes/manifest.php',
	'cp-files/images/archive-link.png',
	'cp-files/images/blue-grad.png',
	'cp-files/images/button-grad-active.png',
	'cp-files/images/button-grad.png',
	'cp-files/images/ed-bg-vs.gif',
	'cp-files/images/ed-bg.gif',
	'cp-files/images/fade-butt.png',
	'cp-files/images/fav-arrow-rtl.gif',
	'cp-files/images/fav-arrow.gif',
	'cp-files/images/fav-vs.png',
	'cp-files/images/fav.png',
	'cp-files/images/gray-grad.png',
	'cp-files/images/loading-publish.gif',
	'cp-files/images/logo-ghost.png',
	'cp-files/images/logo.gif',
	'cp-files/images/menu-arrow-frame-rtl.png',
	'cp-files/images/menu-arrow-frame.png',
	'cp-files/images/menu-arrows.gif',
	'cp-files/images/menu-bits-rtl-vs.gif',
	'cp-files/images/menu-bits-rtl.gif',
	'cp-files/images/menu-bits-vs.gif',
	'cp-files/images/menu-bits.gif',
	'cp-files/images/menu-dark-rtl-vs.gif',
	'cp-files/images/menu-dark-rtl.gif',
	'cp-files/images/menu-dark-vs.gif',
	'cp-files/images/menu-dark.gif',
	'cp-files/images/required.gif',
	'cp-files/images/screen-options-toggle-vs.gif',
	'cp-files/images/screen-options-toggle.gif',
	'cp-files/images/toggle-arrow-rtl.gif',
	'cp-files/images/toggle-arrow.gif',
	'cp-files/images/upload-classic.png',
	'cp-files/images/upload-fresh.png',
	'cp-files/images/white-grad-active.png',
	'cp-files/images/white-grad.png',
	'cp-files/images/widgets-arrow-vs.gif',
	'cp-files/images/widgets-arrow.gif',
	'cp-files/images/wpspin_dark.gif',
	'cp-includes/images/upload.png',
	'cp-includes/js/prototype.js',
	'cp-includes/js/scriptaculous',
	'cp-files/css/wp-admin-rtl.dev.css',
	'cp-files/css/wp-admin.dev.css',
	'cp-files/css/media-rtl.dev.css',
	'cp-files/css/media.dev.css',
	'cp-files/css/colors-classic.dev.css',
	'cp-files/css/customize-controls-rtl.dev.css',
	'cp-files/css/customize-controls.dev.css',
	'cp-files/css/ie-rtl.dev.css',
	'cp-files/css/ie.dev.css',
	'cp-files/css/install.dev.css',
	'cp-files/css/colors-fresh.dev.css',
	'cp-includes/js/customize-base.dev.js',
	'cp-includes/js/json2.dev.js',
	'cp-includes/js/comment-reply.dev.js',
	'cp-includes/js/customize-preview.dev.js',
	'cp-includes/js/wplink.dev.js',
	'cp-includes/js/tw-sack.dev.js',
	'cp-includes/js/wp-list-revisions.dev.js',
	'cp-includes/js/autosave.dev.js',
	'cp-includes/js/admin-bar.dev.js',
	'cp-includes/js/quicktags.dev.js',
	'cp-includes/js/wp-ajax-response.dev.js',
	'cp-includes/js/wp-pointer.dev.js',
	'cp-includes/js/hoverIntent.dev.js',
	'cp-includes/js/colorpicker.dev.js',
	'cp-includes/js/wp-lists.dev.js',
	'cp-includes/js/customize-loader.dev.js',
	'cp-includes/js/jquery/jquery.table-hotkeys.dev.js',
	'cp-includes/js/jquery/jquery.color.dev.js',
	'cp-includes/js/jquery/jquery.color.js',
	'cp-includes/js/jquery/jquery.hotkeys.dev.js',
	'cp-includes/js/jquery/jquery.form.dev.js',
	'cp-includes/js/jquery/suggest.dev.js',
	'cp-files/js/xfn.dev.js',
	'cp-files/js/set-post-thumbnail.dev.js',
	'cp-files/js/comment.dev.js',
	'cp-files/js/theme.dev.js',
	'cp-files/js/cat.dev.js',
	'cp-files/js/password-strength-meter.dev.js',
	'cp-files/js/user-profile.dev.js',
	'cp-files/js/theme-preview.dev.js',
	'cp-files/js/post.dev.js',
	'cp-files/js/media-upload.dev.js',
	'cp-files/js/word-count.dev.js',
	'cp-files/js/plugin-install.dev.js',
	'cp-files/js/edit-comments.dev.js',
	'cp-files/js/media-gallery.dev.js',
	'cp-files/js/custom-fields.dev.js',
	'cp-files/js/custom-background.dev.js',
	'cp-files/js/common.dev.js',
	'cp-files/js/inline-edit-tax.dev.js',
	'cp-files/js/gallery.dev.js',
	'cp-files/js/utils.dev.js',
	'cp-files/js/widgets.dev.js',
	'cp-files/js/wp-fullscreen.dev.js',
	'cp-files/js/nav-menu.dev.js',
	'cp-files/js/dashboard.dev.js',
	'cp-files/js/link.dev.js',
	'cp-files/js/user-suggest.dev.js',
	'cp-files/js/postbox.dev.js',
	'cp-files/js/tags.dev.js',
	'cp-files/js/image-edit.dev.js',
	'cp-files/js/media.dev.js',
	'cp-files/js/customize-controls.dev.js',
	'cp-files/js/inline-edit-post.dev.js',
	'cp-files/js/categories.dev.js',
	'cp-files/js/editor.dev.js',
	'cp-includes/js/tinymce/plugins/wpeditimage/js/editimage.dev.js',
	'cp-includes/js/tinymce/plugins/wpdialogs/js/popup.dev.js',
	'cp-includes/js/tinymce/plugins/wpdialogs/js/wpdialog.dev.js',
	'cp-includes/js/plupload/handlers.dev.js',
	'cp-includes/js/plupload/wp-plupload.dev.js',
	'cp-includes/js/swfupload/handlers.dev.js',
	'cp-includes/js/jcrop/jquery.Jcrop.dev.js',
	'cp-includes/js/jcrop/jquery.Jcrop.js',
	'cp-includes/js/jcrop/jquery.Jcrop.css',
	'cp-includes/js/imgareaselect/jquery.imgareaselect.dev.js',
	'cp-includes/css/wp-pointer.dev.css',
	'cp-includes/css/editor.dev.css',
	'cp-includes/css/jquery-ui-dialog.dev.css',
	'cp-includes/css/admin-bar-rtl.dev.css',
	'cp-includes/css/admin-bar.dev.css',
	'cp-includes/js/jquery/ui/jquery.effects.clip.min.js',
	'cp-includes/js/jquery/ui/jquery.effects.scale.min.js',
	'cp-includes/js/jquery/ui/jquery.effects.blind.min.js',
	'cp-includes/js/jquery/ui/jquery.effects.core.min.js',
	'cp-includes/js/jquery/ui/jquery.effects.shake.min.js',
	'cp-includes/js/jquery/ui/jquery.effects.fade.min.js',
	'cp-includes/js/jquery/ui/jquery.effects.explode.min.js',
	'cp-includes/js/jquery/ui/jquery.effects.slide.min.js',
	'cp-includes/js/jquery/ui/jquery.effects.drop.min.js',
	'cp-includes/js/jquery/ui/jquery.effects.highlight.min.js',
	'cp-includes/js/jquery/ui/jquery.effects.bounce.min.js',
	'cp-includes/js/jquery/ui/jquery.effects.pulsate.min.js',
	'cp-includes/js/jquery/ui/jquery.effects.transfer.min.js',
	'cp-includes/js/jquery/ui/jquery.effects.fold.min.js',
	'cp-files/images/screenshots/captions-1.png',
	'cp-files/images/screenshots/captions-2.png',
	'cp-files/images/screenshots/flex-header-1.png',
	'cp-files/images/screenshots/flex-header-2.png',
	'cp-files/images/screenshots/flex-header-3.png',
	'cp-files/images/screenshots/flex-header-media-library.png',
	'cp-files/images/screenshots/theme-customizer.png',
	'cp-files/images/screenshots/twitter-embed-1.png',
	'cp-files/images/screenshots/twitter-embed-2.png',
	'cp-files/js/utils.js',
	// Added back in 5.3 [45448], see #43895.
	// 'cp-files/options-privacy.php',
	'wp-app.php',
	'cp-includes/class-wp-atom-server.php',
	'cp-includes/js/tinymce/themes/advanced/skins/wp_theme/ui.css',
	// 3.5.2
	'cp-includes/js/swfupload/swfupload-all.js',
	// 3.6
	'cp-files/js/revisions-js.php',
	'cp-files/images/screenshots',
	'cp-files/js/categories.js',
	'cp-files/js/categories.min.js',
	'cp-files/js/custom-fields.js',
	'cp-files/js/custom-fields.min.js',
	// 3.7
	'cp-files/js/cat.js',
	'cp-files/js/cat.min.js',
	'cp-includes/js/tinymce/plugins/wpeditimage/js/editimage.min.js',
	// 3.8
	'cp-includes/js/tinymce/themes/advanced/skins/wp_theme/img/page_bug.gif',
	'cp-includes/js/tinymce/themes/advanced/skins/wp_theme/img/more_bug.gif',
	'cp-includes/js/thickbox/tb-close-2x.png',
	'cp-includes/js/thickbox/tb-close.png',
	'cp-includes/images/wpmini-blue-2x.png',
	'cp-includes/images/wpmini-blue.png',
	'cp-files/css/colors-fresh.css',
	'cp-files/css/colors-classic.css',
	'cp-files/css/colors-fresh.min.css',
	'cp-files/css/colors-classic.min.css',
	'cp-files/js/about.min.js',
	'cp-files/js/about.js',
	'cp-files/images/arrows-dark-vs-2x.png',
	'cp-files/images/wp-logo-vs.png',
	'cp-files/images/arrows-dark-vs.png',
	'cp-files/images/wp-logo.png',
	'cp-files/images/arrows-pr.png',
	'cp-files/images/arrows-dark.png',
	'cp-files/images/press-this.png',
	'cp-files/images/press-this-2x.png',
	'cp-files/images/arrows-vs-2x.png',
	'cp-files/images/welcome-icons.png',
	'cp-files/images/wp-logo-2x.png',
	'cp-files/images/stars-rtl-2x.png',
	'cp-files/images/arrows-dark-2x.png',
	'cp-files/images/arrows-pr-2x.png',
	'cp-files/images/menu-shadow-rtl.png',
	'cp-files/images/arrows-vs.png',
	'cp-files/images/about-search-2x.png',
	'cp-files/images/bubble_bg-rtl-2x.gif',
	'cp-files/images/wp-badge-2x.png',
	'cp-files/images/wordpress-logo-2x.png',
	'cp-files/images/bubble_bg-rtl.gif',
	'cp-files/images/wp-badge.png',
	'cp-files/images/menu-shadow.png',
	'cp-files/images/about-globe-2x.png',
	'cp-files/images/welcome-icons-2x.png',
	'cp-files/images/stars-rtl.png',
	'cp-files/images/wp-logo-vs-2x.png',
	'cp-files/images/about-updates-2x.png',
	// 3.9
	'cp-files/css/colors.css',
	'cp-files/css/colors.min.css',
	'cp-files/css/colors-rtl.css',
	'cp-files/css/colors-rtl.min.css',
	// Following files added back in 4.5, see #36083.
	// 'cp-files/css/media-rtl.min.css',
	// 'cp-files/css/media.min.css',
	// 'cp-files/css/farbtastic-rtl.min.css',
	'cp-files/images/lock-2x.png',
	'cp-files/images/lock.png',
	'cp-files/js/theme-preview.js',
	'cp-files/js/theme-install.min.js',
	'cp-files/js/theme-install.js',
	'cp-files/js/theme-preview.min.js',
	'cp-includes/js/plupload/plupload.html4.js',
	'cp-includes/js/plupload/plupload.html5.js',
	'cp-includes/js/plupload/changelog.txt',
	'cp-includes/js/plupload/plupload.silverlight.js',
	'cp-includes/js/plupload/plupload.flash.js',
	// Added back in 4.9 [41328], see #41755.
	// 'cp-includes/js/plupload/plupload.js',
	'cp-includes/js/tinymce/plugins/spellchecker',
	'cp-includes/js/tinymce/plugins/inlinepopups',
	'cp-includes/js/tinymce/plugins/media/js',
	'cp-includes/js/tinymce/plugins/media/css',
	'cp-includes/js/tinymce/plugins/wordpress/img',
	'cp-includes/js/tinymce/plugins/wpdialogs/js',
	'cp-includes/js/tinymce/plugins/wpeditimage/img',
	'cp-includes/js/tinymce/plugins/wpeditimage/js',
	'cp-includes/js/tinymce/plugins/wpeditimage/css',
	'cp-includes/js/tinymce/plugins/wpgallery/img',
	'cp-includes/js/tinymce/plugins/wpfullscreen/css',
	'cp-includes/js/tinymce/plugins/paste/js',
	'cp-includes/js/tinymce/themes/advanced',
	'cp-includes/js/tinymce/tiny_mce.js',
	'cp-includes/js/tinymce/mark_loaded_src.js',
	'cp-includes/js/tinymce/wp-tinymce-schema.js',
	'cp-includes/js/tinymce/plugins/media/editor_plugin.js',
	'cp-includes/js/tinymce/plugins/media/editor_plugin_src.js',
	'cp-includes/js/tinymce/plugins/media/media.htm',
	'cp-includes/js/tinymce/plugins/wpview/editor_plugin_src.js',
	'cp-includes/js/tinymce/plugins/wpview/editor_plugin.js',
	'cp-includes/js/tinymce/plugins/directionality/editor_plugin.js',
	'cp-includes/js/tinymce/plugins/directionality/editor_plugin_src.js',
	'cp-includes/js/tinymce/plugins/wordpress/editor_plugin.js',
	'cp-includes/js/tinymce/plugins/wordpress/editor_plugin_src.js',
	'cp-includes/js/tinymce/plugins/wpdialogs/editor_plugin_src.js',
	'cp-includes/js/tinymce/plugins/wpdialogs/editor_plugin.js',
	'cp-includes/js/tinymce/plugins/wpeditimage/editimage.html',
	'cp-includes/js/tinymce/plugins/wpeditimage/editor_plugin.js',
	'cp-includes/js/tinymce/plugins/wpeditimage/editor_plugin_src.js',
	'cp-includes/js/tinymce/plugins/fullscreen/editor_plugin_src.js',
	'cp-includes/js/tinymce/plugins/fullscreen/fullscreen.htm',
	'cp-includes/js/tinymce/plugins/fullscreen/editor_plugin.js',
	'cp-includes/js/tinymce/plugins/wplink/editor_plugin_src.js',
	'cp-includes/js/tinymce/plugins/wplink/editor_plugin.js',
	'cp-includes/js/tinymce/plugins/wpgallery/editor_plugin_src.js',
	'cp-includes/js/tinymce/plugins/wpgallery/editor_plugin.js',
	'cp-includes/js/tinymce/plugins/tabfocus/editor_plugin.js',
	'cp-includes/js/tinymce/plugins/tabfocus/editor_plugin_src.js',
	'cp-includes/js/tinymce/plugins/wpfullscreen/editor_plugin.js',
	'cp-includes/js/tinymce/plugins/wpfullscreen/editor_plugin_src.js',
	'cp-includes/js/tinymce/plugins/paste/editor_plugin.js',
	'cp-includes/js/tinymce/plugins/paste/pasteword.htm',
	'cp-includes/js/tinymce/plugins/paste/editor_plugin_src.js',
	'cp-includes/js/tinymce/plugins/paste/pastetext.htm',
	'cp-includes/js/tinymce/langs/wp-langs.php',
	// 4.1
	'cp-includes/js/jquery/ui/jquery.ui.accordion.min.js',
	'cp-includes/js/jquery/ui/jquery.ui.autocomplete.min.js',
	'cp-includes/js/jquery/ui/jquery.ui.button.min.js',
	'cp-includes/js/jquery/ui/jquery.ui.core.min.js',
	'cp-includes/js/jquery/ui/jquery.ui.datepicker.min.js',
	'cp-includes/js/jquery/ui/jquery.ui.dialog.min.js',
	'cp-includes/js/jquery/ui/jquery.ui.draggable.min.js',
	'cp-includes/js/jquery/ui/jquery.ui.droppable.min.js',
	'cp-includes/js/jquery/ui/jquery.ui.effect-blind.min.js',
	'cp-includes/js/jquery/ui/jquery.ui.effect-bounce.min.js',
	'cp-includes/js/jquery/ui/jquery.ui.effect-clip.min.js',
	'cp-includes/js/jquery/ui/jquery.ui.effect-drop.min.js',
	'cp-includes/js/jquery/ui/jquery.ui.effect-explode.min.js',
	'cp-includes/js/jquery/ui/jquery.ui.effect-fade.min.js',
	'cp-includes/js/jquery/ui/jquery.ui.effect-fold.min.js',
	'cp-includes/js/jquery/ui/jquery.ui.effect-highlight.min.js',
	'cp-includes/js/jquery/ui/jquery.ui.effect-pulsate.min.js',
	'cp-includes/js/jquery/ui/jquery.ui.effect-scale.min.js',
	'cp-includes/js/jquery/ui/jquery.ui.effect-shake.min.js',
	'cp-includes/js/jquery/ui/jquery.ui.effect-slide.min.js',
	'cp-includes/js/jquery/ui/jquery.ui.effect-transfer.min.js',
	'cp-includes/js/jquery/ui/jquery.ui.effect.min.js',
	'cp-includes/js/jquery/ui/jquery.ui.menu.min.js',
	'cp-includes/js/jquery/ui/jquery.ui.mouse.min.js',
	'cp-includes/js/jquery/ui/jquery.ui.position.min.js',
	'cp-includes/js/jquery/ui/jquery.ui.progressbar.min.js',
	'cp-includes/js/jquery/ui/jquery.ui.resizable.min.js',
	'cp-includes/js/jquery/ui/jquery.ui.selectable.min.js',
	'cp-includes/js/jquery/ui/jquery.ui.slider.min.js',
	'cp-includes/js/jquery/ui/jquery.ui.sortable.min.js',
	'cp-includes/js/jquery/ui/jquery.ui.spinner.min.js',
	'cp-includes/js/jquery/ui/jquery.ui.tabs.min.js',
	'cp-includes/js/jquery/ui/jquery.ui.tooltip.min.js',
	'cp-includes/js/jquery/ui/jquery.ui.widget.min.js',
	'cp-includes/js/tinymce/skins/wordpress/images/dashicon-no-alt.png',
	// 4.3
	'cp-files/js/wp-fullscreen.js',
	'cp-files/js/wp-fullscreen.min.js',
	'cp-includes/js/tinymce/wp-mce-help.php',
	'cp-includes/js/tinymce/plugins/wpfullscreen',
	// 4.5
	'cp-includes/theme-compat/comments-popup.php',
	// 4.6
	'cp-files/includes/class-wp-automatic-upgrader.php', // Wrong file name, see #37628.
	// 4.8
	'cp-includes/js/tinymce/plugins/wpembed',
	'cp-includes/js/tinymce/plugins/media/moxieplayer.swf',
	'cp-includes/js/tinymce/skins/lightgray/fonts/readme.md',
	'cp-includes/js/tinymce/skins/lightgray/fonts/tinymce-small.json',
	'cp-includes/js/tinymce/skins/lightgray/fonts/tinymce.json',
	'cp-includes/js/tinymce/skins/lightgray/skin.ie7.min.css',
	// 4.9
	'cp-files/css/press-this-editor-rtl.css',
	'cp-files/css/press-this-editor-rtl.min.css',
	'cp-files/css/press-this-editor.css',
	'cp-files/css/press-this-editor.min.css',
	'cp-files/css/press-this-rtl.css',
	'cp-files/css/press-this-rtl.min.css',
	'cp-files/css/press-this.css',
	'cp-files/css/press-this.min.css',
	'cp-files/includes/class-wp-press-this.php',
	'cp-files/js/bookmarklet.js',
	'cp-files/js/bookmarklet.min.js',
	'cp-files/js/press-this.js',
	'cp-files/js/press-this.min.js',
	'cp-includes/js/mediaelement/background.png',
	'cp-includes/js/mediaelement/bigplay.png',
	'cp-includes/js/mediaelement/bigplay.svg',
	'cp-includes/js/mediaelement/controls.png',
	'cp-includes/js/mediaelement/controls.svg',
	'cp-includes/js/mediaelement/flashmediaelement.swf',
	'cp-includes/js/mediaelement/froogaloop.min.js',
	'cp-includes/js/mediaelement/jumpforward.png',
	'cp-includes/js/mediaelement/loading.gif',
	'cp-includes/js/mediaelement/silverlightmediaelement.xap',
	'cp-includes/js/mediaelement/skipback.png',
	'cp-includes/js/plupload/plupload.flash.swf',
	'cp-includes/js/plupload/plupload.full.min.js',
	'cp-includes/js/plupload/plupload.silverlight.xap',
	'cp-includes/js/swfupload/plugins',
	'cp-includes/js/swfupload/swfupload.swf',
	// 4.9.2
	'cp-includes/js/mediaelement/lang',
	'cp-includes/js/mediaelement/lang/ca.js',
	'cp-includes/js/mediaelement/lang/cs.js',
	'cp-includes/js/mediaelement/lang/de.js',
	'cp-includes/js/mediaelement/lang/es.js',
	'cp-includes/js/mediaelement/lang/fa.js',
	'cp-includes/js/mediaelement/lang/fr.js',
	'cp-includes/js/mediaelement/lang/hr.js',
	'cp-includes/js/mediaelement/lang/hu.js',
	'cp-includes/js/mediaelement/lang/it.js',
	'cp-includes/js/mediaelement/lang/ja.js',
	'cp-includes/js/mediaelement/lang/ko.js',
	'cp-includes/js/mediaelement/lang/nl.js',
	'cp-includes/js/mediaelement/lang/pl.js',
	'cp-includes/js/mediaelement/lang/pt.js',
	'cp-includes/js/mediaelement/lang/ro.js',
	'cp-includes/js/mediaelement/lang/ru.js',
	'cp-includes/js/mediaelement/lang/sk.js',
	'cp-includes/js/mediaelement/lang/sv.js',
	'cp-includes/js/mediaelement/lang/uk.js',
	'cp-includes/js/mediaelement/lang/zh-cn.js',
	'cp-includes/js/mediaelement/lang/zh.js',
	'cp-includes/js/mediaelement/mediaelement-flash-audio-ogg.swf',
	'cp-includes/js/mediaelement/mediaelement-flash-audio.swf',
	'cp-includes/js/mediaelement/mediaelement-flash-video-hls.swf',
	'cp-includes/js/mediaelement/mediaelement-flash-video-mdash.swf',
	'cp-includes/js/mediaelement/mediaelement-flash-video.swf',
	'cp-includes/js/mediaelement/renderers/dailymotion.js',
	'cp-includes/js/mediaelement/renderers/dailymotion.min.js',
	'cp-includes/js/mediaelement/renderers/facebook.js',
	'cp-includes/js/mediaelement/renderers/facebook.min.js',
	'cp-includes/js/mediaelement/renderers/soundcloud.js',
	'cp-includes/js/mediaelement/renderers/soundcloud.min.js',
	'cp-includes/js/mediaelement/renderers/twitch.js',
	'cp-includes/js/mediaelement/renderers/twitch.min.js',
	// 5.0
	'cp-includes/js/codemirror/jshint.js',
	// 5.1
	'cp-includes/random_compat/random_bytes_openssl.php',
	'cp-includes/js/tinymce/wp-tinymce.js.gz',
	// 5.3
	'cp-includes/js/wp-a11y.js',     // Moved to: cp-includes/js/dist/a11y.js
	'cp-includes/js/wp-a11y.min.js', // Moved to: cp-includes/js/dist/a11y.min.js
	// 5.4
	'cp-files/js/wp-fullscreen-stub.js',
	'cp-files/js/wp-fullscreen-stub.min.js',
	// 5.5
	'cp-files/css/ie.css',
	'cp-files/css/ie.min.css',
	'cp-files/css/ie-rtl.css',
	'cp-files/css/ie-rtl.min.css',
	// 5.6
	'cp-includes/js/jquery/ui/position.min.js',
	'cp-includes/js/jquery/ui/widget.min.js',
	// 5.7
	'cp-includes/blocks/classic/block.json',
	// 5.8
	'cp-files/images/freedoms.png',
	'cp-files/images/privacy.png',
	'cp-files/images/about-badge.svg',
	'cp-files/images/about-color-palette.svg',
	'cp-files/images/about-color-palette-vert.svg',
	'cp-files/images/about-header-brushes.svg',
	'cp-includes/block-patterns/large-header.php',
	'cp-includes/block-patterns/heading-paragraph.php',
	'cp-includes/block-patterns/quote.php',
	'cp-includes/block-patterns/text-three-columns-buttons.php',
	'cp-includes/block-patterns/two-buttons.php',
	'cp-includes/block-patterns/two-images.php',
	'cp-includes/block-patterns/three-buttons.php',
	'cp-includes/block-patterns/text-two-columns-with-images.php',
	'cp-includes/block-patterns/text-two-columns.php',
	'cp-includes/block-patterns/large-header-button.php',
	'cp-includes/blocks/subhead/block.json',
	'cp-includes/blocks/subhead',
	'cp-includes/css/dist/editor/editor-styles.css',
	'cp-includes/css/dist/editor/editor-styles.min.css',
	'cp-includes/css/dist/editor/editor-styles-rtl.css',
	'cp-includes/css/dist/editor/editor-styles-rtl.min.css',
	// 5.9
	'cp-includes/blocks/heading/editor.css',
	'cp-includes/blocks/heading/editor.min.css',
	'cp-includes/blocks/heading/editor-rtl.css',
	'cp-includes/blocks/heading/editor-rtl.min.css',
	'cp-includes/blocks/post-content/editor.css',
	'cp-includes/blocks/post-content/editor.min.css',
	'cp-includes/blocks/post-content/editor-rtl.css',
	'cp-includes/blocks/post-content/editor-rtl.min.css',
	'cp-includes/blocks/query-title/editor.css',
	'cp-includes/blocks/query-title/editor.min.css',
	'cp-includes/blocks/query-title/editor-rtl.css',
	'cp-includes/blocks/query-title/editor-rtl.min.css',
	'cp-includes/blocks/tag-cloud/editor.css',
	'cp-includes/blocks/tag-cloud/editor.min.css',
	'cp-includes/blocks/tag-cloud/editor-rtl.css',
	'cp-includes/blocks/tag-cloud/editor-rtl.min.css',
	// 6.1
	'cp-includes/blocks/post-comments.php',
	'cp-includes/blocks/post-comments/block.json',
	'cp-includes/blocks/post-comments/editor.css',
	'cp-includes/blocks/post-comments/editor.min.css',
	'cp-includes/blocks/post-comments/editor-rtl.css',
	'cp-includes/blocks/post-comments/editor-rtl.min.css',
	'cp-includes/blocks/post-comments/style.css',
	'cp-includes/blocks/post-comments/style.min.css',
	'cp-includes/blocks/post-comments/style-rtl.css',
	'cp-includes/blocks/post-comments/style-rtl.min.css',
	'cp-includes/blocks/post-comments',
	'cp-includes/blocks/comments-query-loop/block.json',
	'cp-includes/blocks/comments-query-loop/editor.css',
	'cp-includes/blocks/comments-query-loop/editor.min.css',
	'cp-includes/blocks/comments-query-loop/editor-rtl.css',
	'cp-includes/blocks/comments-query-loop/editor-rtl.min.css',
	'cp-includes/blocks/comments-query-loop',
	// 6.3
	'cp-includes/images/wlw',
	'cp-includes/wlwmanifest.xml',
	'cp-includes/random_compat',
);

/**
 * Stores Requests files to be preloaded and deleted.
 *
 * For classes/interfaces, use the class/interface name
 * as the array key.
 *
 * All other files/directories should not have a key.
 *
 * @since 6.2.0
 *
 * @global array $_old_requests_files
 * @var array
 * @name $_old_requests_files
 */
global $_old_requests_files;

$_old_requests_files = array(
	// Interfaces.
	'Requests_Auth'                              => 'cp-includes/Requests/Auth.php',
	'Requests_Hooker'                            => 'cp-includes/Requests/Hooker.php',
	'Requests_Proxy'                             => 'cp-includes/Requests/Proxy.php',
	'Requests_Transport'                         => 'cp-includes/Requests/Transport.php',

	// Classes.
	'Requests_Auth_Basic'                        => 'cp-includes/Requests/Auth/Basic.php',
	'Requests_Cookie_Jar'                        => 'cp-includes/Requests/Cookie/Jar.php',
	'Requests_Exception_HTTP'                    => 'cp-includes/Requests/Exception/HTTP.php',
	'Requests_Exception_Transport'               => 'cp-includes/Requests/Exception/Transport.php',
	'Requests_Exception_HTTP_304'                => 'cp-includes/Requests/Exception/HTTP/304.php',
	'Requests_Exception_HTTP_305'                => 'cp-includes/Requests/Exception/HTTP/305.php',
	'Requests_Exception_HTTP_306'                => 'cp-includes/Requests/Exception/HTTP/306.php',
	'Requests_Exception_HTTP_400'                => 'cp-includes/Requests/Exception/HTTP/400.php',
	'Requests_Exception_HTTP_401'                => 'cp-includes/Requests/Exception/HTTP/401.php',
	'Requests_Exception_HTTP_402'                => 'cp-includes/Requests/Exception/HTTP/402.php',
	'Requests_Exception_HTTP_403'                => 'cp-includes/Requests/Exception/HTTP/403.php',
	'Requests_Exception_HTTP_404'                => 'cp-includes/Requests/Exception/HTTP/404.php',
	'Requests_Exception_HTTP_405'                => 'cp-includes/Requests/Exception/HTTP/405.php',
	'Requests_Exception_HTTP_406'                => 'cp-includes/Requests/Exception/HTTP/406.php',
	'Requests_Exception_HTTP_407'                => 'cp-includes/Requests/Exception/HTTP/407.php',
	'Requests_Exception_HTTP_408'                => 'cp-includes/Requests/Exception/HTTP/408.php',
	'Requests_Exception_HTTP_409'                => 'cp-includes/Requests/Exception/HTTP/409.php',
	'Requests_Exception_HTTP_410'                => 'cp-includes/Requests/Exception/HTTP/410.php',
	'Requests_Exception_HTTP_411'                => 'cp-includes/Requests/Exception/HTTP/411.php',
	'Requests_Exception_HTTP_412'                => 'cp-includes/Requests/Exception/HTTP/412.php',
	'Requests_Exception_HTTP_413'                => 'cp-includes/Requests/Exception/HTTP/413.php',
	'Requests_Exception_HTTP_414'                => 'cp-includes/Requests/Exception/HTTP/414.php',
	'Requests_Exception_HTTP_415'                => 'cp-includes/Requests/Exception/HTTP/415.php',
	'Requests_Exception_HTTP_416'                => 'cp-includes/Requests/Exception/HTTP/416.php',
	'Requests_Exception_HTTP_417'                => 'cp-includes/Requests/Exception/HTTP/417.php',
	'Requests_Exception_HTTP_418'                => 'cp-includes/Requests/Exception/HTTP/418.php',
	'Requests_Exception_HTTP_428'                => 'cp-includes/Requests/Exception/HTTP/428.php',
	'Requests_Exception_HTTP_429'                => 'cp-includes/Requests/Exception/HTTP/429.php',
	'Requests_Exception_HTTP_431'                => 'cp-includes/Requests/Exception/HTTP/431.php',
	'Requests_Exception_HTTP_500'                => 'cp-includes/Requests/Exception/HTTP/500.php',
	'Requests_Exception_HTTP_501'                => 'cp-includes/Requests/Exception/HTTP/501.php',
	'Requests_Exception_HTTP_502'                => 'cp-includes/Requests/Exception/HTTP/502.php',
	'Requests_Exception_HTTP_503'                => 'cp-includes/Requests/Exception/HTTP/503.php',
	'Requests_Exception_HTTP_504'                => 'cp-includes/Requests/Exception/HTTP/504.php',
	'Requests_Exception_HTTP_505'                => 'cp-includes/Requests/Exception/HTTP/505.php',
	'Requests_Exception_HTTP_511'                => 'cp-includes/Requests/Exception/HTTP/511.php',
	'Requests_Exception_HTTP_Unknown'            => 'cp-includes/Requests/Exception/HTTP/Unknown.php',
	'Requests_Exception_Transport_cURL'          => 'cp-includes/Requests/Exception/Transport/cURL.php',
	'Requests_Proxy_HTTP'                        => 'cp-includes/Requests/Proxy/HTTP.php',
	'Requests_Response_Headers'                  => 'cp-includes/Requests/Response/Headers.php',
	'Requests_Transport_cURL'                    => 'cp-includes/Requests/Transport/cURL.php',
	'Requests_Transport_fsockopen'               => 'cp-includes/Requests/Transport/fsockopen.php',
	'Requests_Utility_CaseInsensitiveDictionary' => 'cp-includes/Requests/Utility/CaseInsensitiveDictionary.php',
	'Requests_Utility_FilteredIterator'          => 'cp-includes/Requests/Utility/FilteredIterator.php',
	'Requests_Cookie'                            => 'cp-includes/Requests/Cookie.php',
	'Requests_Exception'                         => 'cp-includes/Requests/Exception.php',
	'Requests_Hooks'                             => 'cp-includes/Requests/Hooks.php',
	'Requests_IDNAEncoder'                       => 'cp-includes/Requests/IDNAEncoder.php',
	'Requests_IPv6'                              => 'cp-includes/Requests/IPv6.php',
	'Requests_IRI'                               => 'cp-includes/Requests/IRI.php',
	'Requests_Response'                          => 'cp-includes/Requests/Response.php',
	'Requests_SSL'                               => 'cp-includes/Requests/SSL.php',
	'Requests_Session'                           => 'cp-includes/Requests/Session.php',

	// Directories.
	'cp-includes/Requests/Auth/',
	'cp-includes/Requests/Cookie/',
	'cp-includes/Requests/Exception/HTTP/',
	'cp-includes/Requests/Exception/Transport/',
	'cp-includes/Requests/Exception/',
	'cp-includes/Requests/Proxy/',
	'cp-includes/Requests/Response/',
	'cp-includes/Requests/Transport/',
	'cp-includes/Requests/Utility/',
);

/**
 * Stores new files in wp-content to copy
 *
 * The contents of this array indicate any new bundled plugins/themes which
 * should be installed with the WordPress Upgrade. These items will not be
 * re-installed in future upgrades, this behavior is controlled by the
 * introduced version present here being older than the current installed version.
 *
 * The content of this array should follow the following format:
 * Filename (relative to wp-content) => Introduced version
 * Directories should be noted by suffixing it with a trailing slash (/)
 *
 * @since 3.2.0
 * @since 4.7.0 New themes were not automatically installed for 4.4-4.6 on
 *              upgrade. New themes are now installed again. To disable new
 *              themes from being installed on upgrade, explicitly define
 *              CORE_UPGRADE_SKIP_NEW_BUNDLED as true.
 * @global array $_new_bundled_files
 * @var array
 * @name $_new_bundled_files
 */
global $_new_bundled_files;

$_new_bundled_files = array(
	'plugins/akismet/'          => '2.0',
	'themes/twentyten/'         => '3.0',
	'themes/twentyeleven/'      => '3.2',
	'themes/twentytwelve/'      => '3.5',
	'themes/twentythirteen/'    => '3.6',
	'themes/twentyfourteen/'    => '3.8',
	'themes/twentyfifteen/'     => '4.1',
	'themes/twentysixteen/'     => '4.4',
	'themes/twentyseventeen/'   => '4.7',
	'themes/twentynineteen/'    => '5.0',
	'themes/twentytwenty/'      => '5.3',
	'themes/twentytwentyone/'   => '5.6',
	'themes/twentytwentytwo/'   => '5.9',
	'themes/twentytwentythree/' => '6.1',
);

/**
 * Upgrades the core of WordPress.
 *
 * This will create a .maintenance file at the base of the WordPress directory
 * to ensure that people can not access the web site, when the files are being
 * copied to their locations.
 *
 * The files in the `$_old_files` list will be removed and the new files
 * copied from the zip file after the database is upgraded.
 *
 * The files in the `$_new_bundled_files` list will be added to the installation
 * if the version is greater than or equal to the old version being upgraded.
 *
 * The steps for the upgrader for after the new release is downloaded and
 * unzipped is:
 *   1. Test unzipped location for select files to ensure that unzipped worked.
 *   2. Create the .maintenance file in current WordPress base.
 *   3. Copy new WordPress directory over old WordPress files.
 *   4. Upgrade WordPress to new version.
 *     4.1. Copy all files/folders other than wp-content
 *     4.2. Copy any language files to WP_LANG_DIR (which may differ from WP_CONTENT_DIR
 *     4.3. Copy any new bundled themes/plugins to their respective locations
 *   5. Delete new WordPress directory path.
 *   6. Delete .maintenance file.
 *   7. Remove old files.
 *   8. Delete 'update_core' option.
 *
 * There are several areas of failure. For instance if PHP times out before step
 * 6, then you will not be able to access any portion of your site. Also, since
 * the upgrade will not continue where it left off, you will not be able to
 * automatically remove old files and remove the 'update_core' option. This
 * isn't that bad.
 *
 * If the copy of the new WordPress over the old fails, then the worse is that
 * the new WordPress directory will remain.
 *
 * If it is assumed that every file will be copied over, including plugins and
 * themes, then if you edit the default theme, you should rename it, so that
 * your changes remain.
 *
 * @since 2.7.0
 *
 * @global WP_Filesystem_Base $wp_filesystem          WordPress filesystem subclass.
 * @global array              $_old_files
 * @global array              $_old_requests_files
 * @global array              $_new_bundled_files
 * @global wpdb               $wpdb                   WordPress database abstraction object.
 * @global string             $wp_version
 * @global string             $required_php_version
 * @global string             $required_mysql_version
 *
 * @param string $from New release unzipped path.
 * @param string $to   Path to old WordPress installation.
 * @return string|WP_Error New WordPress version on success, WP_Error on failure.
 */
function update_core( $from, $to ) {
	global $wp_filesystem, $_old_files, $_old_requests_files, $_new_bundled_files, $wpdb;

	if ( function_exists( 'set_time_limit' ) ) {
		set_time_limit( 300 );
	}

	/*
	 * Merge the old Requests files and directories into the `$_old_files`.
	 * Then preload these Requests files first, before the files are deleted
	 * and replaced to ensure the code is in memory if needed.
	 */
	$_old_files = array_merge( $_old_files, array_values( $_old_requests_files ) );
	_preload_old_requests_classes_and_interfaces( $to );

	/**
	 * Filters feedback messages displayed during the core update process.
	 *
	 * The filter is first evaluated after the zip file for the latest version
	 * has been downloaded and unzipped. It is evaluated five more times during
	 * the process:
	 *
	 * 1. Before WordPress begins the core upgrade process.
	 * 2. Before Maintenance Mode is enabled.
	 * 3. Before WordPress begins copying over the necessary files.
	 * 4. Before Maintenance Mode is disabled.
	 * 5. Before the database is upgraded.
	 *
	 * @since 2.5.0
	 *
	 * @param string $feedback The core update feedback messages.
	 */
	apply_filters( 'update_feedback', __( 'Verifying the unpacked files&#8230;' ) );

	// Sanity check the unzipped distribution.
	$distro = '';
	$roots  = array( '/custom_pages/', '/custom_pages/' );

	foreach ( $roots as $root ) {
		if ( $wp_filesystem->exists( $from . $root . 'readme.html' )
			&& $wp_filesystem->exists( $from . $root . 'cp-includes/version.php' )
		) {
			$distro = $root;
			break;
		}
	}

	if ( ! $distro ) {
		$wp_filesystem->delete( $from, true );

		return new WP_Error( 'insane_distro', __( 'The update could not be unpacked' ) );
	}

	/*
	 * Import $wp_version, $required_php_version, and $required_mysql_version from the new version.
	 * DO NOT globalize any variables imported from `version-current.php` in this function.
	 *
	 * BC Note: $wp_filesystem->wp_content_dir() returned unslashed pre-2.8.
	 */
	$versions_file = trailingslashit( $wp_filesystem->wp_content_dir() ) . 'upgrade/version-current.php';

	if ( ! $wp_filesystem->copy( $from . $distro . 'cp-includes/version.php', $versions_file ) ) {
		$wp_filesystem->delete( $from, true );

		return new WP_Error(
			'copy_failed_for_version_file',
			__( 'The update cannot be installed because some files could not be copied. This is usually due to inconsistent file permissions.' ),
			'cp-includes/version.php'
		);
	}

	$wp_filesystem->chmod( $versions_file, FS_CHMOD_FILE );

	/*
	 * `wp_opcache_invalidate()` only exists in WordPress 5.5 or later,
	 * so don't run it when upgrading from older versions.
	 */
	if ( function_exists( 'wp_opcache_invalidate' ) ) {
		wp_opcache_invalidate( $versions_file );
	}

	require WP_CONTENT_DIR . '/upgrade/version-current.php';
	$wp_filesystem->delete( $versions_file );

	$php_version    = PHP_VERSION;
	$mysql_version  = $wpdb->db_version();
	$old_wp_version = $GLOBALS['wp_version']; // The version of WordPress we're updating from.
	/*
	 * Note: str_contains() is not used here, as this file is included
	 * when updating from older WordPress versions, in which case
	 * the polyfills from cp-includes/compat.php may not be available.
	 */
	$development_build = ( false !== strpos( $old_wp_version . $wp_version, '-' ) ); // A dash in the version indicates a development release.
	$php_compat        = version_compare( $php_version, $required_php_version, '>=' );

	if ( file_exists( WP_CONTENT_DIR . '/db.php' ) && empty( $wpdb->is_mysql ) ) {
		$mysql_compat = true;
	} else {
		$mysql_compat = version_compare( $mysql_version, $required_mysql_version, '>=' );
	}

	if ( ! $mysql_compat || ! $php_compat ) {
		$wp_filesystem->delete( $from, true );
	}

	$php_update_message = '';

	if ( function_exists( 'wp_get_update_php_url' ) ) {
		$php_update_message = '</p><p>' . sprintf(
			/* translators: %s: URL to Update PHP page. */
			__( '<a href="%s">Learn more about updating PHP</a>.' ),
			esc_url( wp_get_update_php_url() )
		);

		if ( function_exists( 'wp_get_update_php_annotation' ) ) {
			$annotation = wp_get_update_php_annotation();

			if ( $annotation ) {
				$php_update_message .= '</p><p><em>' . $annotation . '</em>';
			}
		}
	}

	if ( ! $mysql_compat && ! $php_compat ) {
		return new WP_Error(
			'php_mysql_not_compatible',
			sprintf(
				/* translators: 1: WordPress version number, 2: Minimum required PHP version number, 3: Minimum required MySQL version number, 4: Current PHP version number, 5: Current MySQL version number. */
				__( 'The update cannot be installed because WordPress %1$s requires PHP version %2$s or higher and MySQL version %3$s or higher. You are running PHP version %4$s and MySQL version %5$s.' ),
				$wp_version,
				$required_php_version,
				$required_mysql_version,
				$php_version,
				$mysql_version
			) . $php_update_message
		);
	} elseif ( ! $php_compat ) {
		return new WP_Error(
			'php_not_compatible',
			sprintf(
				/* translators: 1: WordPress version number, 2: Minimum required PHP version number, 3: Current PHP version number. */
				__( 'The update cannot be installed because WordPress %1$s requires PHP version %2$s or higher. You are running version %3$s.' ),
				$wp_version,
				$required_php_version,
				$php_version
			) . $php_update_message
		);
	} elseif ( ! $mysql_compat ) {
		return new WP_Error(
			'mysql_not_compatible',
			sprintf(
				/* translators: 1: WordPress version number, 2: Minimum required MySQL version number, 3: Current MySQL version number. */
				__( 'The update cannot be installed because WordPress %1$s requires MySQL version %2$s or higher. You are running version %3$s.' ),
				$wp_version,
				$required_mysql_version,
				$mysql_version
			)
		);
	}

	// Add a warning when the JSON PHP extension is missing.
	if ( ! extension_loaded( 'json' ) ) {
		return new WP_Error(
			'php_not_compatible_json',
			sprintf(
				/* translators: 1: WordPress version number, 2: The PHP extension name needed. */
				__( 'The update cannot be installed because WordPress %1$s requires the %2$s PHP extension.' ),
				$wp_version,
				'JSON'
			)
		);
	}

	/** This filter is documented in cp-files/includes/update-core.php */
	apply_filters( 'update_feedback', __( 'Preparing to install the latest version&#8230;' ) );

	/*
	 * Don't copy wp-content, we'll deal with that below.
	 * We also copy version.php last so failed updates report their old version.
	 */
	$skip              = array( 'wp-content', 'cp-includes/version.php' );
	$check_is_writable = array();

	// Check to see which files don't really need updating - only available for 3.7 and higher.
	if ( function_exists( 'get_core_checksums' ) ) {
		// Find the local version of the working directory.
		$working_dir_local = WP_CONTENT_DIR . '/upgrade/' . basename( $from ) . $distro;

		$checksums = get_core_checksums( $wp_version, isset( $wp_local_package ) ? $wp_local_package : 'en_US' );

		if ( is_array( $checksums ) && isset( $checksums[ $wp_version ] ) ) {
			$checksums = $checksums[ $wp_version ]; // Compat code for 3.7-beta2.
		}

		if ( is_array( $checksums ) ) {
			foreach ( $checksums as $file => $checksum ) {
				/*
				 * Note: str_starts_with() is not used here, as this file is included
				 * when updating from older WordPress versions, in which case
				 * the polyfills from cp-includes/compat.php may not be available.
				 */
				if ( 'wp-content' === substr( $file, 0, 10 ) ) {
					continue;
				}

				if ( ! file_exists( ABSPATH . $file ) ) {
					continue;
				}

				if ( ! file_exists( $working_dir_local . $file ) ) {
					continue;
				}

				if ( '.' === dirname( $file )
					&& in_array( pathinfo( $file, PATHINFO_EXTENSION ), array( 'html', 'txt' ), true )
				) {
					continue;
				}

				if ( md5_file( ABSPATH . $file ) === $checksum ) {
					$skip[] = $file;
				} else {
					$check_is_writable[ $file ] = ABSPATH . $file;
				}
			}
		}
	}

	// If we're using the direct method, we can predict write failures that are due to permissions.
	if ( $check_is_writable && 'direct' === $wp_filesystem->method ) {
		$files_writable = array_filter( $check_is_writable, array( $wp_filesystem, 'is_writable' ) );

		if ( $files_writable !== $check_is_writable ) {
			$files_not_writable = array_diff_key( $check_is_writable, $files_writable );

			foreach ( $files_not_writable as $relative_file_not_writable => $file_not_writable ) {
				// If the writable check failed, chmod file to 0644 and try again, same as copy_dir().
				$wp_filesystem->chmod( $file_not_writable, FS_CHMOD_FILE );

				if ( $wp_filesystem->is_writable( $file_not_writable ) ) {
					unset( $files_not_writable[ $relative_file_not_writable ] );
				}
			}

			// Store package-relative paths (the key) of non-writable files in the WP_Error object.
			$error_data = version_compare( $old_wp_version, '3.7-beta2', '>' ) ? array_keys( $files_not_writable ) : '';

			if ( $files_not_writable ) {
				return new WP_Error(
					'files_not_writable',
					__( 'The update cannot be installed because your site is unable to copy some files. This is usually due to inconsistent file permissions.' ),
					implode( ', ', $error_data )
				);
			}
		}
	}

	/** This filter is documented in cp-files/includes/update-core.php */
	apply_filters( 'update_feedback', __( 'Enabling Maintenance mode&#8230;' ) );

	// Create maintenance file to signal that we are upgrading.
	$maintenance_string = '<?php $upgrading = ' . time() . '; ?>';
	$maintenance_file   = $to . '.maintenance';
	$wp_filesystem->delete( $maintenance_file );
	$wp_filesystem->put_contents( $maintenance_file, $maintenance_string, FS_CHMOD_FILE );

	/** This filter is documented in cp-files/includes/update-core.php */
	apply_filters( 'update_feedback', __( 'Copying the required files&#8230;' ) );

	// Copy new versions of WP files into place.
	$result = copy_dir( $from . $distro, $to, $skip );

	if ( is_wp_error( $result ) ) {
		$result = new WP_Error(
			$result->get_error_code(),
			$result->get_error_message(),
			substr( $result->get_error_data(), strlen( $to ) )
		);
	}

	// Since we know the core files have copied over, we can now copy the version file.
	if ( ! is_wp_error( $result ) ) {
		if ( ! $wp_filesystem->copy( $from . $distro . 'cp-includes/version.php', $to . 'cp-includes/version.php', true /* overwrite */ ) ) {
			$wp_filesystem->delete( $from, true );
			$result = new WP_Error(
				'copy_failed_for_version_file',
				__( 'The update cannot be installed because your site is unable to copy some files. This is usually due to inconsistent file permissions.' ),
				'cp-includes/version.php'
			);
		}

		$wp_filesystem->chmod( $to . 'cp-includes/version.php', FS_CHMOD_FILE );

		/*
		 * `wp_opcache_invalidate()` only exists in WordPress 5.5 or later,
		 * so don't run it when upgrading from older versions.
		 */
		if ( function_exists( 'wp_opcache_invalidate' ) ) {
			wp_opcache_invalidate( $to . 'cp-includes/version.php' );
		}
	}

	// Check to make sure everything copied correctly, ignoring the contents of wp-content.
	$skip   = array( 'wp-content' );
	$failed = array();

	if ( isset( $checksums ) && is_array( $checksums ) ) {
		foreach ( $checksums as $file => $checksum ) {
			/*
			 * Note: str_starts_with() is not used here, as this file is included
			 * when updating from older WordPress versions, in which case
			 * the polyfills from cp-includes/compat.php may not be available.
			 */
			if ( 'wp-content' === substr( $file, 0, 10 ) ) {
				continue;
			}

			if ( ! file_exists( $working_dir_local . $file ) ) {
				continue;
			}

			if ( '.' === dirname( $file )
				&& in_array( pathinfo( $file, PATHINFO_EXTENSION ), array( 'html', 'txt' ), true )
			) {
				$skip[] = $file;
				continue;
			}

			if ( file_exists( ABSPATH . $file ) && md5_file( ABSPATH . $file ) === $checksum ) {
				$skip[] = $file;
			} else {
				$failed[] = $file;
			}
		}
	}

	// Some files didn't copy properly.
	if ( ! empty( $failed ) ) {
		$total_size = 0;

		foreach ( $failed as $file ) {
			if ( file_exists( $working_dir_local . $file ) ) {
				$total_size += filesize( $working_dir_local . $file );
			}
		}

		/*
		 * If we don't have enough free space, it isn't worth trying again.
		 * Unlikely to be hit due to the check in unzip_file().
		 */
		$available_space = function_exists( 'disk_free_space' ) ? @disk_free_space( ABSPATH ) : false;

		if ( $available_space && $total_size >= $available_space ) {
			$result = new WP_Error( 'disk_full', __( 'There is not enough free disk space to complete the update.' ) );
		} else {
			$result = copy_dir( $from . $distro, $to, $skip );

			if ( is_wp_error( $result ) ) {
				$result = new WP_Error(
					$result->get_error_code() . '_retry',
					$result->get_error_message(),
					substr( $result->get_error_data(), strlen( $to ) )
				);
			}
		}
	}

	/*
	 * Custom content directory needs updating now.
	 * Copy languages.
	 */
	if ( ! is_wp_error( $result ) && $wp_filesystem->is_dir( $from . $distro . 'cp-content/languages' ) ) {
		if ( WP_LANG_DIR !== ABSPATH . WPINC . '/languages' || @is_dir( WP_LANG_DIR ) ) {
			$lang_dir = WP_LANG_DIR;
		} else {
			$lang_dir = WP_CONTENT_DIR . '/languages';
		}
		/*
		 * Note: str_starts_with() is not used here, as this file is included
		 * when updating from older WordPress versions, in which case
		 * the polyfills from cp-includes/compat.php may not be available.
		 */
		// Check if the language directory exists first.
		if ( ! @is_dir( $lang_dir ) && 0 === strpos( $lang_dir, ABSPATH ) ) {
			// If it's within the ABSPATH we can handle it here, otherwise they're out of luck.
			$wp_filesystem->mkdir( $to . str_replace( ABSPATH, '', $lang_dir ), FS_CHMOD_DIR );
			clearstatcache(); // For FTP, need to clear the stat cache.
		}

		if ( @is_dir( $lang_dir ) ) {
			$wp_lang_dir = $wp_filesystem->find_folder( $lang_dir );

			if ( $wp_lang_dir ) {
				$result = copy_dir( $from . $distro . 'cp-content/languages/', $wp_lang_dir );

				if ( is_wp_error( $result ) ) {
					$result = new WP_Error(
						$result->get_error_code() . '_languages',
						$result->get_error_message(),
						substr( $result->get_error_data(), strlen( $wp_lang_dir ) )
					);
				}
			}
		}
	}

	/** This filter is documented in cp-files/includes/update-core.php */
	apply_filters( 'update_feedback', __( 'Disabling Maintenance mode&#8230;' ) );

	// Remove maintenance file, we're done with potential site-breaking changes.
	$wp_filesystem->delete( $maintenance_file );

	/*
	 * 3.5 -> 3.5+ - an empty twentytwelve directory was created upon upgrade to 3.5 for some users,
	 * preventing installation of Twenty Twelve.
	 */
	if ( '3.5' === $old_wp_version ) {
		if ( is_dir( WP_CONTENT_DIR . '/themes/twentytwelve' )
			&& ! file_exists( WP_CONTENT_DIR . '/themes/twentytwelve/style.css' )
		) {
			$wp_filesystem->delete( $wp_filesystem->wp_themes_dir() . 'twentytwelve/' );
		}
	}

	/*
	 * Copy new bundled plugins & themes.
	 * This gives us the ability to install new plugins & themes bundled with
	 * future versions of WordPress whilst avoiding the re-install upon upgrade issue.
	 * $development_build controls us overwriting bundled themes and plugins when a non-stable release is being updated.
	 */
	if ( ! is_wp_error( $result )
		&& ( ! defined( 'CORE_UPGRADE_SKIP_NEW_BUNDLED' ) || ! CORE_UPGRADE_SKIP_NEW_BUNDLED )
	) {
		foreach ( (array) $_new_bundled_files as $file => $introduced_version ) {
			// If a $development_build or if $introduced version is greater than what the site was previously running.
			if ( $development_build || version_compare( $introduced_version, $old_wp_version, '>' ) ) {
				$directory = ( '/' === $file[ strlen( $file ) - 1 ] );

				list( $type, $filename ) = explode( '/', $file, 2 );

				// Check to see if the bundled items exist before attempting to copy them.
				if ( ! $wp_filesystem->exists( $from . $distro . 'cp-content/' . $file ) ) {
					continue;
				}

				if ( 'plugins' === $type ) {
					$dest = $wp_filesystem->wp_plugins_dir();
				} elseif ( 'themes' === $type ) {
					// Back-compat, ::wp_themes_dir() did not return trailingslash'd pre-3.2.
					$dest = trailingslashit( $wp_filesystem->wp_themes_dir() );
				} else {
					continue;
				}

				if ( ! $directory ) {
					if ( ! $development_build && $wp_filesystem->exists( $dest . $filename ) ) {
						continue;
					}

					if ( ! $wp_filesystem->copy( $from . $distro . 'cp-content/' . $file, $dest . $filename, FS_CHMOD_FILE ) ) {
						$result = new WP_Error( "copy_failed_for_new_bundled_$type", __( 'Could not copy file.' ), $dest . $filename );
					}
				} else {
					if ( ! $development_build && $wp_filesystem->is_dir( $dest . $filename ) ) {
						continue;
					}

					$wp_filesystem->mkdir( $dest . $filename, FS_CHMOD_DIR );
					$_result = copy_dir( $from . $distro . 'cp-content/' . $file, $dest . $filename );

					/*
					 * If an error occurs partway through this final step,
					 * keep the error flowing through, but keep the process going.
					 */
					if ( is_wp_error( $_result ) ) {
						if ( ! is_wp_error( $result ) ) {
							$result = new WP_Error();
						}

						$result->add(
							$_result->get_error_code() . "_$type",
							$_result->get_error_message(),
							substr( $_result->get_error_data(), strlen( $dest ) )
						);
					}
				}
			}
		} // End foreach.
	}

	// Handle $result error from the above blocks.
	if ( is_wp_error( $result ) ) {
		$wp_filesystem->delete( $from, true );

		return $result;
	}

	// Remove old files.
	foreach ( $_old_files as $old_file ) {
		$old_file = $to . $old_file;

		if ( ! $wp_filesystem->exists( $old_file ) ) {
			continue;
		}

		// If the file isn't deleted, try writing an empty string to the file instead.
		if ( ! $wp_filesystem->delete( $old_file, true ) && $wp_filesystem->is_file( $old_file ) ) {
			$wp_filesystem->put_contents( $old_file, '' );
		}
	}

	// Remove any Genericons example.html's from the filesystem.
	_upgrade_422_remove_genericons();

	// Deactivate the REST API plugin if its version is 2.0 Beta 4 or lower.
	_upgrade_440_force_deactivate_incompatible_plugins();

	// Deactivate incompatible plugins.
	_upgrade_core_deactivate_incompatible_plugins();

	// Upgrade DB with separate request.
	/** This filter is documented in cp-files/includes/update-core.php */
	apply_filters( 'update_feedback', __( 'Upgrading database&#8230;' ) );

	$db_upgrade_url = admin_url( 'upgrade.php?step=upgrade_db' );
	wp_remote_post( $db_upgrade_url, array( 'timeout' => 60 ) );

	// Clear the cache to prevent an update_option() from saving a stale db_version to the cache.
	wp_cache_flush();
	// Not all cache back ends listen to 'flush'.
	wp_cache_delete( 'alloptions', 'options' );

	// Remove working directory.
	$wp_filesystem->delete( $from, true );

	// Force refresh of update information.
	if ( function_exists( 'delete_site_transient' ) ) {
		delete_site_transient( 'update_core' );
	} else {
		delete_option( 'update_core' );
	}

	/**
	 * Fires after WordPress core has been successfully updated.
	 *
	 * @since 3.3.0
	 *
	 * @param string $wp_version The current WordPress version.
	 */
	do_action( '_core_updated_successfully', $wp_version );

	// Clear the option that blocks auto-updates after failures, now that we've been successful.
	if ( function_exists( 'delete_site_option' ) ) {
		delete_site_option( 'auto_core_update_failed' );
	}

	return $wp_version;
}

/**
 * Preloads old Requests classes and interfaces.
 *
 * This function preloads the old Requests code into memory before the
 * upgrade process deletes the files. Why? Requests code is loaded into
 * memory via an autoloader, meaning when a class or interface is needed
 * If a request is in process, Requests could attempt to access code. If
 * the file is not there, a fatal error could occur. If the file was
 * replaced, the new code is not compatible with the old, resulting in
 * a fatal error. Preloading ensures the code is in memory before the
 * code is updated.
 *
 * @since 6.2.0
 *
 * @global array              $_old_requests_files Requests files to be preloaded.
 * @global WP_Filesystem_Base $wp_filesystem       WordPress filesystem subclass.
 * @global string             $wp_version          The WordPress version string.
 *
 * @param string $to Path to old WordPress installation.
 */
function _preload_old_requests_classes_and_interfaces( $to ) {
	global $_old_requests_files, $wp_filesystem, $wp_version;

	/*
	 * Requests was introduced in WordPress 4.6.
	 *
	 * Skip preloading if the website was previously using
	 * an earlier version of WordPress.
	 */
	if ( version_compare( $wp_version, '4.6', '<' ) ) {
		return;
	}

	if ( ! defined( 'REQUESTS_SILENCE_PSR0_DEPRECATIONS' ) ) {
		define( 'REQUESTS_SILENCE_PSR0_DEPRECATIONS', true );
	}

	foreach ( $_old_requests_files as $name => $file ) {
		// Skip files that aren't interfaces or classes.
		if ( is_int( $name ) ) {
			continue;
		}

		// Skip if it's already loaded.
		if ( class_exists( $name ) || interface_exists( $name ) ) {
			continue;
		}

		// Skip if the file is missing.
		if ( ! $wp_filesystem->is_file( $to . $file ) ) {
			continue;
		}

		require_once $to . $file;
	}
}

/**
 * Redirect to the About WordPress page after a successful upgrade.
 *
 * This function is only needed when the existing installation is older than 3.4.0.
 *
 * @since 3.3.0
 *
 * @global string $wp_version The WordPress version string.
 * @global string $pagenow    The filename of the current screen.
 * @global string $action
 *
 * @param string $new_version
 */
function _redirect_to_about_wordpress( $new_version ) {
	global $wp_version, $pagenow, $action;

	if ( version_compare( $wp_version, '3.4-RC1', '>=' ) ) {
		return;
	}

	// Ensure we only run this on the update-core.php page. The Core_Upgrader may be used in other contexts.
	if ( 'update-core.php' !== $pagenow ) {
		return;
	}

	if ( 'do-core-upgrade' !== $action && 'do-core-reinstall' !== $action ) {
		return;
	}

	// Load the updated default text localization domain for new strings.
	load_default_textdomain();

	// See do_core_upgrade().
	show_message( __( 'WordPress updated successfully.' ) );

	// self_admin_url() won't exist when upgrading from <= 3.0, so relative URLs are intentional.
	show_message(
		'<span class="hide-if-no-js">' . sprintf(
			/* translators: 1: WordPress version, 2: URL to About screen. */
			__( 'Welcome to WordPress %1$s. You will be redirected to the About WordPress screen. If not, click <a href="%2$s">here</a>.' ),
			$new_version,
			'about.php?updated'
		) . '</span>'
	);
	show_message(
		'<span class="hide-if-js">' . sprintf(
			/* translators: 1: WordPress version, 2: URL to About screen. */
			__( 'Welcome to WordPress %1$s. <a href="%2$s">Learn more</a>.' ),
			$new_version,
			'about.php?updated'
		) . '</span>'
	);
	echo '</div>';
	?>
<script type="text/javascript">
window.location = 'about.php?updated';
</script>
	<?php

	// Include admin-footer.php and exit.
	require_once ABSPATH . 'cp-files/admin-footer.php';
	exit;
}

/**
 * Cleans up Genericons example files.
 *
 * @since 4.2.2
 *
 * @global array              $wp_theme_directories
 * @global WP_Filesystem_Base $wp_filesystem
 */
function _upgrade_422_remove_genericons() {
	global $wp_theme_directories, $wp_filesystem;

	// A list of the affected files using the filesystem absolute paths.
	$affected_files = array();

	// Themes.
	foreach ( $wp_theme_directories as $directory ) {
		$affected_theme_files = _upgrade_422_find_genericons_files_in_folder( $directory );
		$affected_files       = array_merge( $affected_files, $affected_theme_files );
	}

	// Plugins.
	$affected_plugin_files = _upgrade_422_find_genericons_files_in_folder( WP_PLUGIN_DIR );
	$affected_files        = array_merge( $affected_files, $affected_plugin_files );

	foreach ( $affected_files as $file ) {
		$gen_dir = $wp_filesystem->find_folder( trailingslashit( dirname( $file ) ) );

		if ( empty( $gen_dir ) ) {
			continue;
		}

		// The path when the file is accessed via WP_Filesystem may differ in the case of FTP.
		$remote_file = $gen_dir . basename( $file );

		if ( ! $wp_filesystem->exists( $remote_file ) ) {
			continue;
		}

		if ( ! $wp_filesystem->delete( $remote_file, false, 'f' ) ) {
			$wp_filesystem->put_contents( $remote_file, '' );
		}
	}
}

/**
 * Recursively find Genericons example files in a given folder.
 *
 * @ignore
 * @since 4.2.2
 *
 * @param string $directory Directory path. Expects trailingslashed.
 * @return array
 */
function _upgrade_422_find_genericons_files_in_folder( $directory ) {
	$directory = trailingslashit( $directory );
	$files     = array();

	if ( file_exists( "{$directory}example.html" )
		/*
		 * Note: str_contains() is not used here, as this file is included
		 * when updating from older WordPress versions, in which case
		 * the polyfills from cp-includes/compat.php may not be available.
		 */
		&& false !== strpos( file_get_contents( "{$directory}example.html" ), '<title>Genericons</title>' )
	) {
		$files[] = "{$directory}example.html";
	}

	$dirs = glob( $directory . '*', GLOB_ONLYDIR );
	$dirs = array_filter(
		$dirs,
		static function( $dir ) {
			/*
			 * Skip any node_modules directories.
			 *
			 * Note: str_contains() is not used here, as this file is included
			 * when updating from older WordPress versions, in which case
			 * the polyfills from cp-includes/compat.php may not be available.
			 */
			return false === strpos( $dir, 'node_modules' );
		}
	);

	if ( $dirs ) {
		foreach ( $dirs as $dir ) {
			$files = array_merge( $files, _upgrade_422_find_genericons_files_in_folder( $dir ) );
		}
	}

	return $files;
}

/**
 * @ignore
 * @since 4.4.0
 */
function _upgrade_440_force_deactivate_incompatible_plugins() {
	if ( defined( 'REST_API_VERSION' ) && version_compare( REST_API_VERSION, '2.0-beta4', '<=' ) ) {
		deactivate_plugins( array( 'rest-api/plugin.php' ), true );
	}
}

/**
 * @access private
 * @ignore
 * @since 5.8.0
 * @since 5.9.0 The minimum compatible version of Gutenberg is 11.9.
 * @since 6.1.1 The minimum compatible version of Gutenberg is 14.1.
 */
function _upgrade_core_deactivate_incompatible_plugins() {
	if ( defined( 'GUTENBERG_VERSION' ) && version_compare( GUTENBERG_VERSION, '14.1', '<' ) ) {
		$deactivated_gutenberg['gutenberg'] = array(
			'plugin_name'         => 'Gutenberg',
			'version_deactivated' => GUTENBERG_VERSION,
			'version_compatible'  => '14.1',
		);
		if ( is_plugin_active_for_network( 'gutenberg/gutenberg.php' ) ) {
			$deactivated_plugins = get_site_option( 'wp_force_deactivated_plugins', array() );
			$deactivated_plugins = array_merge( $deactivated_plugins, $deactivated_gutenberg );
			update_site_option( 'wp_force_deactivated_plugins', $deactivated_plugins );
		} else {
			$deactivated_plugins = get_option( 'wp_force_deactivated_plugins', array() );
			$deactivated_plugins = array_merge( $deactivated_plugins, $deactivated_gutenberg );
			update_option( 'wp_force_deactivated_plugins', $deactivated_plugins );
		}
		deactivate_plugins( array( 'gutenberg/gutenberg.php' ), true );
	}
}
