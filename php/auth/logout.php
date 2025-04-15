<?php
include_once(__DIR__ . '/../../lib/common.php');

session_unset();
session_destroy();

header("Location: /index.php");
exit;
