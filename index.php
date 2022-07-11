<?php
include_once "./_header.php";

$logedin_info = $_SESSION['logedin'];

if (!$logedin_info) {
    echo "<script type='text/javascript'>location.replace('./login.php');</script>";
    exit();
} else {
    print_r($_SESSION);
}


?>


<body>

</body>


<?php
include_once "./_footer.php";
?>