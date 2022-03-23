<?php
require_once "connection.php";

//declare variables and initialize with empty values
$first_name = $last_name = $email = $username = $password = $confirm_password = "";
$first_name_err = $last_name_err = $email_err = $username_err = $password_err = $confirm_password_err = "";

//processing data of the form
if($_SERVER["REQUEST_METHOD"] == "POST") {
    //validate first name
    if(empty(trim($_POST["first_name"]))) {
        $first_name_err = "Please enter your first name.";
    } elseif(!preg_match('/^[a-zA-Z]+$/', trim($_POST["first_name"]))) {
        $first_name_err = "Fist name can only contain letters.";
    } else {
        //store first name
        $first_name = trim($_POST["first_name"]);
    }
    //validate last name
    if(empty(trim($_POST["last_name"]))) {
        $last_name_err = "Please enter your last name.";
    } elseif(!preg_match('/^[a-zA-Z]+$/', trim($_POST["last_name"]))) {
        $last_name_err = "Last name can only contain letters.";
    } else {
        //store last name
        $last_name = trim($_POST["last_name"]);
    }
    //validate email
    if(empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email address.";
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_err = "Invalid email format.";
    } else {
        $email = trim($_POST["email"]);
    }
    //validate username
    if(empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))) {
        $username_err = "Username can only contain letters, numbers, and underscores.";
    } else {
        //select statement
        $sql = "SELECT id FROM users WHERE username = ?";
        if($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            $param_username = trim($_POST["username"]);
            //execute statement
            if(mysqli_stmt_execute($stmt)) {
                //store result
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1) {
                    $username_err = "This username is already taken.";
                } else {
                    $username = trim($_POST["username"]);
                }
            } else {
                echo "Error!!! Please try again later.";
            }
            //close statement
            mysqli_stmt_close($stmt);
        }
    }
    //validate password
    if(empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif(strlen(trim($_POST["password"])) < 5) {
        $password_err = "Enter atleast 5 characters for the password.";
    } else {
        $password = trim($_POST["password"]);
    }
    //validate confirm password
    if(empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Password did not match.";
        }
    }
    //check errors
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)) {
        //insert statement
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
        if($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
            //set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT);
            if(mysqli_stmt_execute($stmt)) {
                header("location: login.php");
            } else {
                echo "Error!!! Please try again later.";
            }
            //close statement
            mysqli_stmt_close($stmt);
        }
    }
    // Close connection
    mysqli_close($link);
}