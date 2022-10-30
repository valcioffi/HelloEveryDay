<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/identity/functions/auth.php';

session_start();
session_destroy();
logout();