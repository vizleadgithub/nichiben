<style type="text/css">
	#footer UL{
		background		: white;
		height			: 30px;
		margin-top		: -10px;
	}
	#footer UL LI{
		display			: inline-block;
		line-height		: 30px;
		margin			: 0px 20px;
	}
	#footer UL LI A{
		color			: #222222;
		text-decoration	: none;
	}
</style>
<div id="footer">
        <ul>
          <li><a href="http://alfredcore.com/" target="_blank">運営会社</a></li>
          <li><a href="http://alfredcore.com/privacy" target="_blank">個人情報保護方針</a></li>
        </ul>

<!--	<div style="text-align:right;margin-top:5px;">Alflearning <?= $this->config->item('alf_learning_ver'); ?> | Copyright&copy;<?= date("Y");?> Alfredcore,Inc.</div>-->
		<?php 
			$this->ci =& get_instance();
			$this->ci->load->model('model_footer'); 
			$footer_data = $this->ci->model_footer->output_footer(array('company' => 'ALF')); 
		?>
		<div style="text-align:right;margin-top:5px;"><?= $footer_data; ?></div>
</div>
