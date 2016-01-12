<?php
	if(!Isset($_SERVER['HTTPS'])){
		$redir = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		header("Location: $redir");
	}
?>