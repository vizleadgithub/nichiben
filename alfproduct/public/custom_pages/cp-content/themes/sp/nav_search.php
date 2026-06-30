<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
?>
<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
<div class="nav_search">
	<div class="nav_search_area" style="">
		<div class="nav_search_area_input" style="">
			<form name="nav_search_area_input_form" method="post" action="/search/index.php?search=new#main">
				<div class="nav_search_area_input_form_keyword" style="width: 420px;">
					<img style="width: 28px;height: 28px;float: left;border: solid 0px #ffffff;padding: 0;cursor: pointer;" src="/img/btn/btn-search.png" class="nav_search_area_input_form_keyword_icon" onclick="document.nav_search_area_input_form.submit();">
					<?php
					$search_keyword = "";
					if( $_GET["search"]=="new" ){
						$search_keyword = array_filter(explode(  " ",  mb_str_replace( "　", " ", trim($_POST["search_keyword"]) )  ), 'strlen');
					} else {
						$search_keyword = array_filter(explode(  " ",  mb_str_replace( "　", " ", trim($_GET["search_keyword"]) )  ), 'strlen');
					}
					?>
					<input type="text" name="search_keyword" value="<?php print( htmlspecialchars(implode(' ', $search_keyword)) ); ?>" placeholder="フリーワード検索" class="nav_search_area_input_form_keyword_input" style="width: 380px;height: 28px;font-size: 20px;line-height: 28px;border: solid 0 #ffffff;padding: 0;float:left;box-shadow: none;background-color: #ffffff;color: #666666;">
				</div>

		 		<a href="javascript:void(0);" class="nav_search_area_input_form_keyword_btn" onclick="document.nav_search_area_input_form.submit();">検索</a>

				<div class="nav_search_area_input_form_type" style="height: 28px;line-height: 28px;width: auto;float: left;">
					<?php
					$search_type = "OR";
					if( $_GET["search"]=="new" ){
						if( $_POST["search_type"]=="OR" || $_POST["search_type"]=="AND" ){
							$search_type = trim($_POST["search_type"]);
						}
					} else {
						if( $_GET["search_type"]=="OR" || $_GET["search_type"]=="AND" ){
							$search_type = $_GET["search_type"];
						}
					}
					?>
					<label style="float: left;margin-left: 10px;vertical-align: middle;"><input type="radio" value="AND" name="search_type"<?php if($search_type=="AND"){ ?> checked="checked"<?php } ?>><span style="vertical-align: middle;">AND検索</span></label>
					<label style="float: left;margin-left: 10px;"><input type="radio" value="OR" name="search_type"<?php if($search_type=="OR"){ ?> checked="checked"<?php } ?>><span>OR検索</span></label>
				</div>
				
			</form>
		</div>

	</div>
</div>



<style type="text/css">
	.nav_search{
		display: inline-block;
		width:100%;
		text-align: center;
	}
	.nav_search_area{
		display: inline-block;
		margin: 0 auto 0 auto;

		display: inline-block;
		width: 100%;
		text-align: center;
		margin: 0 auto 0 auto;
	}
	.nav_search_area_input{
		/*width: 650px;*/
		background: none;
		color: #000000;
		margin: 0px;
		padding: 0px;
		font-size: 18px;
		float: left;
	}
	.nav_search_area_input_form{
	}
	.nav_search_area_input_form_keyword{
		background-color:#ffffff;
		background-repeat: no-repeat;

		height: 28px;
		line-height: 28px;
		width: 420px;
		font-size: 20px;
		margin-top: 0px;
		margin-bottom: 0px;
		background-repeat: no-repeat;
		border: 1px solid rgba(0,0,0,0.25);
		border-radius: 4px;
		float: left;

	}
	.nav_search_area_input_form_keyword_icon{
		width: 28px;
		height: 28px;
		float: left;
		border: solid 0px #ffffff;
		padding: 0;
		cursor: pointer;
	}
	.nav_search_area_input_form_keyword_input{
		width: 310px;
		height: 24px;
		font-size: 20px;
		line-height: 24px;
		border: solid 0 #ffffff;
		padding: 0;
		float:left;
		box-shadow: none;
		background-color: #ffffff;
		color: #666666;
	}
	.nav_search_area_input_form_type{
		background-color: none;
		height: 28px;
		line-height: 28px;
		width: 240px;
		float: left;
	}


	.nav_search_area_input_form_type label span {
		display: inline-block;
		vertical-align: middle;
		font-size: 18px;
		margin-top: 0;
	}

	.nav_search_area_input_form_type label input[type="radio"] {
		position: absolute;
		top: 0;
		left: 0;
		opacity: 0;
	}

	.nav_search_area_input_form_type label input[type="radio"] + span::before {
		z-index: 0;
		background-color: transparent;
		width: 22px;
		height: 22px;

		border: 2px  #3B88FD solid;
		margin-right: 5px;
	}

	.nav_search_area_input_form_type label input[type="radio"] + span::before {
		position: relative;
		display: inline-block;
		content: '';
		box-sizing: border-box;
		border-radius: 22px;
		top: 4px;
	}

	.nav_search_area_input_form_type label input[type="radio"]:checked + span::before {
		border-width: 6px;
		margin-right: 5px;
	}



	.nav_search_area_input_form_keyword_btn {
		margin-top: 0px;
		margin-bottom: 0px;
		margin-left: 8px;

		font-size: 18px;
		border: solid 1px rgba(0,0,0,0.25);
		text-decoration: none;
		color: #ffffff;
		background-color:#756B6B;
		height: 28px;
		width: 64px;
		border-radius: 4px;
		line-height: 28px;
		text-align: center;
		float: left;
	}

	.nav_search_area_input_form_keyword_btn:link {
		color: #ffffff;
	}
	.nav_search_area_input_form_keyword_btn:link {
		color: #ffffff;
	}
	.nav_search_area_input_form_keyword_btn:visited {
		color: #ffffff;
	}
	.nav_search_area_input_form_keyword_btn:active, .nav_search_area_input_form_keyword_btn:hover {
		color: #ffffff;
	}
</style>
<!-- LLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLL -->
