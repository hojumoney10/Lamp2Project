<?php
session_start();

// session_unset();
$_SESSION['PAGE'] = "login";

?>
<!DOCTYPE html>
<html>

<head>
    <!-- 
        Title:       login.php
        Application: INFO-5094 LAMP 2 Employee Project
        Purpose:     Handles login
        Author:      G. Blandford,  Group 9, INFO-5094-01-21W
        Date:        March 1st, 2021 (March 1st, 2021)
    -->

    <title>Employee Project - Login</title>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Employee Project Login">
    <meta name="author" content="Graham Blandford">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../vendor/twbs/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Metrophobic&display=swap" rel="stylesheet">

    <!-- jQuery AJAX -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <!-- Custom style -->
    <link href="../css/style.css" rel="stylesheet">
    <style>
        body {
            text-align: center;
        }
    </style>

    <?php
    // Load common
    define('__ROOT__', dirname(__FILE__));
    require_once(__ROOT__ . "/common.php");

    // Auto Mock Generator and symfony
    require_once("../vendor/autoload.php");
    ?>

</head>

<?php

$error = "";

$user_id = "";
$password = "";

// Check if we are attempting a login
// POST
if (isset($_POST['login']))
{
    // create database connection
    $db_conn = connectDB();

    $user_id = $_POST['user-id'];
    $password = $_POST['password'];

    $querySQL = "select
            user_id
            , address
       from users u
       where u.user_id = :user_id
       and u.password = MD5(:password);";

   // assign value to :landlord_id
   $data = array(":user_id" => $user_id
                ,":password" => $password);

   // prepare query
   $stmt = $db_conn->prepare($querySQL);

   // prepare error check
   if (!$stmt) {
       echo "<p>Error: " . $db_conn->errorCode() . "<br>Message: " . implode($db_conn->errorInfo()) . "</p><br>";
       exit(1);
   }

   // execute query in database
   $status = $stmt->execute($data);
   if ($status) { // no error

       if ($stmt->rowCount() == 1) { // Found

           // Return name
           $_SESSION['CURRENT_USER'] = $stmt->fetch(PDO::FETCH_ASSOC); // Assign row
           $db_conn = null;
           header("location: ../index.php");
           die();
       }
   } else {
       // execute error
       echo "<p>Error: " . $stmt->errorCode() . "<br>Message: " . implode($stmt->errorInfo()) . "</p><br>";

       // close database connection
       $db_conn = null;
       exit(1);
   }
   // close database connection
   $db_conn = null;    
}
?>

<body>
    <?php

    // navigation & search bars
    require_once("./navigationMenu.php");
    ?>

    <div class="container-fluid container-login">

        <form id="form-login" class="form form-inline form-login" method="POST">
            <fieldset class="backgroundColor">
                <legend class="text-light bg-dark">Please Log In</legend>

                <!-- User id -->
                <div class="input-group">
                    <label id="label-user-id" for="user-id">User</label>
                    <input type="text" size="10" maxlength="20" class="form-control" id="user-id" name="user-id" aria-describedby="user-id-help" placeholder="Enter your username" value="<?php echo $user_id; ?>" required>
                    <small id="user-id-help" class="form-text text-muted"></small>
                </div>

                <!-- Password -->
                <div class="input-group">
                    <label id="label-password" for="password">Password</label>
                    <input type="password" size="30" maxlength="50" class="form-control" id="password" name="password" aria-describedby="password-help" placeholder="Enter your password" value="<?php echo $password; ?>" required>
                    <small id="password-help" class="form-text text-muted"></small>
                </div>

                <table>
                    <tr>
                        <td><input type="submit" class="btn btn-primary btn-login" name="login" value="Login"></td>
                    </tr>
                </table>
            </fieldset>
        </form>
    </div>

    <?php echo $error; ?>

    <!-- Custom JS -->
    <!-- Bootstrap JS -->
    <script src="../vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>