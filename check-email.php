<?php
include 'config.php';

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    $query = "SELECT * FROM account WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    // Check if any rows are returned
    if (mysqli_num_rows($result) > 0) {
        echo "exists"; // Email exists in the database
    } else {
        echo "not_exists"; // Email does not exist in the database
    }
}
?>