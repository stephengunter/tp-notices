<?php

   
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		require_once("service.php");
        $service=new NoticeService();
		
		echo "test";
		
		
	}else{
		/* 回到列表index */
		header("Location: http://localhost/tp-notice/index.php");
		exit;
		
	}