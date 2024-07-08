
<?php

// Get the posted values
$username = $_POST['username'];
$emailid  = $_POST['emailid'];
$createpassword = $_POST['createpassword'];
$confirmpassword = $_POST['confirmpassword'];

// Check if all fields are filled
if (!empty($username) && !empty($emailid) && !empty($createpassword) && !empty($confirmpassword)) {

    // Check if passwords match
    if ($createpassword !== $confirmpassword) {
        echo "Passwords do not match";
        die();
    }

    // Database connection settings
    $host = "localhost";
    $dbusername = "root";
    $dbpassword = "sriram@123";
    $dbname = "register";

    // Create connection
    $conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

    // Check connection
    if (mysqli_connect_error()) {
        die('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
    } else {
        // Check if email already exists
        $SELECT = "SELECT email From registerform Where email = ? Limit 1";
        $stmt = $conn->prepare($SELECT);
        $stmt->bind_param("s", $emailid);
        $stmt->execute();
        $stmt->store_result();
        $rnum = $stmt->num_rows;

        if ($rnum > 0) {
            echo "Someone already registered using this email";
        } else {
            // Insert new user
            $INSERT = "INSERT Into registerform (username, emailid, createpassword, confirmpassword) values(?,?,?,?)";
            $stmt = $conn->prepare($INSERT);
            $stmt->bind_param("ssss", $username, $emailid, $createpassword, $confirmpassword);
            $stmt->execute();
            echo "New record inserted successfully";
        }
        $stmt->close();
        $conn->close();
    }
} else {
    echo "Please fill all fields";
    die();
}
?>