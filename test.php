<?php

require('include/csrf_guard.php');

$cg = Csrf_Guard::forge();

$ts = 1370225715.3623;
var_dump($cg->get_token($ts));
