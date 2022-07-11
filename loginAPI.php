<?php

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$mysqli = new mysqli('host.docker.internal', 'superstart', 'Ehzldk@3990', 'superstart_main', 3306) or die("Cannot connet");
// mysqli_query($conn, 'set name utf8');

session_start();

$type = $_POST['type'];


if ($type == "login") {

    $sql = "SELECT * FROM user WHERE identifier = ? AND password = ?";
    $stmt = $mysqli->prepare($sql);

    $stmt->bind_param("ss", $id, $pw_hash);

    $id = $_POST['id'];
    $pw = $_POST['pw'];
    $pw_hash = hash("sha256", $pw);

    $stmt->execute();
    $result = $stmt->get_result();
    // // $stmt->close();

    if ($result->num_rows > 0) {
        $identifier = $result->fetch_assoc()['identifier'];
        $_SESSION['logedin'] = $identifier;

        header("Location: /");
    } else {
        session_destroy();
        echo "<script>alert('일치 하는 회원이 없습니다'); history.back()</script>";
    }
    // echo $id . " " . $pw_hash . " " . $sql;
} else {

    print_r($_POST);
    $pw = $_POST['pw'];
    $pw_confirm = $_POST['pwconfirm'];
    if ($pw !== $pw_confirm) {
        echo "<script>alert('패스워드가 일치하지 않습니다.'); history.back()</script>";
        exit(0);
    }
    $sql = "INSERT INTO user (identifier, password) VALUES (?, ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ss", $id, $pw_hash);
    $id = $_POST['id'];
    $pw = $_POST['pw'];
    $pw_hash = hash("sha256", $pw);
    $stmt->execute();
    $result = $stmt->get_result();

    print_r($result);
}


$mysqli->close();
