<?php

$host = "LAPTOP-1587EARF";
$connInfo = array("Database"=>"bebastanggungan", "UID"=>"", "PWD"=>"");
$conn = sqlsrv_connect($host, $connInfo);

// if( $conn ) {
//     echo "Koneksi berhasil. <br />";
// } else {
//     echo "Koneksi gagal";
//         die(print_r(sqlrsv_errors(), true));
// }
