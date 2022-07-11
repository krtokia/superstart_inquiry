<?php
$conn = mysqli_connect('host.docker.internal', 'ss_inquiry', '2017tbtm!1004', 'superstart_inquiry', 3306) or die("Cannot connet");
mysqli_query($conn, 'set name utf8');

$qry = mysqli_query($conn, "SELECT * FROM user");

while ($row = mysqli_fetch_array($qry)) {
    print_r($row);
}


// phpinfo();



// var_dump(extension_loaded('mysqli'));
