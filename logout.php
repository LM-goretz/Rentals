<?php
require 'config.php';
session_unset();
session_destroy();
json_response(['success'=>true,'message'=>'Logged out']);
?>