<?php
session_start();

if(!isset($_SESSION['userId'])){
	header("Location: index.php");
	exit();
} else{
	include 'includes/dbConnect.php';
	include_once 'header.php';
}