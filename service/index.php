<?php


$serverName = "127.0.0.1\SQLEXPRESS2014"; 
$connectionInfo = array( "Database"=>"school", "UID"=>"stephen", "PWD"=>"ss355");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

if( $conn === false ) {
      echo json_encode(array(
        'error' => array(
            'msg' => 'Errors',
            'code' => 500,
        ),
      ));
    return;
}


$tsql = "SELECT  * FROM Notices";

$stmt = sqlsrv_query( $conn, $tsql );

if( $stmt === false  ) {
     echo json_encode(array(
        'error' => array(
            'msg' => 'Errors',
            'code' => 500,
        ),
      ));
    return;
}

$json = array();

do {
     while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $json[] = $row;
     }
} while ( sqlsrv_next_result($stmt) );

echo json_encode($json, JSON_NUMERIC_CHECK);

sqlsrv_free_stmt( $stmt);
sqlsrv_close( $conn);

?>