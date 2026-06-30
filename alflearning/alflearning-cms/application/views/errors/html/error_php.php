<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

<h4>A PHP Error was encountered</h4>

<p>Severity: <?php echo htmlspecialchars($severity, ENT_QUOTES, 'UTF-8'); ?></p>
<p>Message:  <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
<p>Filename: <?php echo htmlspecialchars($filepath, ENT_QUOTES, 'UTF-8'); ?></p>
<p>Line Number: <?php echo $line; ?></p>

<?php if (defined('SHOW_DEBUG_BACKTRACE') && SHOW_DEBUG_BACKTRACE === TRUE): ?>

	<p>Backtrace:</p>
	<?php foreach (debug_backtrace() as $error): ?>

		<?php if (isset($error['file']) && strpos($error['file'], realpath(BASEPATH)) !== 0): ?>

			<p style="margin-left:10px">
			File: <?php echo htmlspecialchars($error['file'], ENT_QUOTES, 'UTF-8'); ?><br />
			Line: <?php echo htmlspecialchars((string)$error['line'], ENT_QUOTES, 'UTF-8'); ?><br />
			Function: <?php echo htmlspecialchars($error['function'], ENT_QUOTES, 'UTF-8'); ?>
			</p>

		<?php endif ?>

	<?php endforeach ?>

<?php endif ?>

</div>