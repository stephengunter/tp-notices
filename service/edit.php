<?php



function getNotice($id){
	
	if(!$id) return  [
						'Id' => 0 , 
						'Content' => '',
						'Staff' => 0,
						'Teacher' => 0,
						'Student' => 0,
						'Reviewed' => 0,
						'Units' => '',
						'Classes' => '',
						'Levels' => '',
						
						'PS' => '',

					];
	
	
     echo "damn";
	if($id)  {
		
		$serverName = "127.0.0.1\SQLEXPRESS2014"; 
		$connectionInfo = array( "Database"=>"school", "UID"=>"stephen", "PWD"=>"ss355");
		$conn = sqlsrv_connect( $serverName, $connectionInfo);
		$tsql = "SELECT * FROM Notices WHERE id=?" ;
		
		$params = array($id);
		$stmt = sqlsrv_query( $conn, $tsql , $params);
		
	    $record = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
		if($record) $notice=$record;
		
		sqlsrv_free_stmt( $stmt);
		sqlsrv_close( $conn);
	
	
	}
	
	
	return $notice;
	
}

function getAttachment($id){
	

	$attachment=[
			'Id' => 0 , 		
			'Notice_Id' => 0,
			'Title' => '',
			'Name' => '',
			'Type' => '',
			'FileData' => '',

	];
	
	if($id){
		$serverName = "127.0.0.1\SQLEXPRESS2014"; 
		$connectionInfo = array( "Database"=>"school", "UID"=>"stephen", "PWD"=>"ss355");
		$conn = sqlsrv_connect( $serverName, $connectionInfo);
		$tsql = "SELECT TOP 1 * FROM NoticeAttachment WHERE Notice_Id=?" ;
		$params = array($id);
		$stmt = sqlsrv_query( $conn, $tsql , $params);
		
	    $attachment = sqlsrv_fetch_object( $stmt);
		
		
		sqlsrv_free_stmt( $stmt);
		sqlsrv_close( $conn);
		
	}
	
	return $attachment;

 
	
}

            




?>