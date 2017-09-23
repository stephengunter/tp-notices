<?php


$serverName = "127.0.0.1\SQLEXPRESS2014"; 
$connectionInfo = array( "Database"=>"school", "UID"=>"stephen", "PWD"=>"ss355");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

$tsql = "UPDATE Notices
        SET Reviewed = ?
        WHERE id = ?";
		

$params = array($id);
$stmt = sqlsrv_query( $conn, $tsql , $params);

$stmt = sqlsrv_query( $conn, $tsql );

echo json_encode(array(
        'success' => true
      ));

sqlsrv_free_stmt( $stmt);
sqlsrv_close( $conn);

?>