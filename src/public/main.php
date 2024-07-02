<?php
session_start();

if(isset($_SESSION['userId'])){
    require_once './catalog.php';
} else {
    http_response_code(403);
}