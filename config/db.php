<?php

try {
	$conn = new mysqli('localhost', 'root', '','inv');
} catch (Exception $e) {
	echo $e->getMessage();
}