<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/userguide3/general/hooks.html
|
*/
$hook['post_controller_constructor'][] = array(
    'class'    => 'RefererCheck',
    'function' => 'check_referer',
    'filename' => 'RefererCheck.php',
    'filepath' => 'hooks'
);

// DBのteacher情報とセッションの乖離を検出してセッションを自動更新する
$hook['post_controller_constructor'][] = array(
    'class'    => 'SessionSyncHook',
    'function' => 'sync',
    'filename' => 'SessionSyncHook.php',
    'filepath' => 'hooks'
);
