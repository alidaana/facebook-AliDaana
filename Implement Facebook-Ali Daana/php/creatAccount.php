<?php
    include "connection.php";
    session_start();
    header("Access-Control-Allow-Origin: * ");

    $error_array = array();// array to store error messages
    
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST['password'];

    //username varification
    $username = strip_tags($username);//remove html tags
    $username = str_replace(' ','',$username);// remove spaces
    $username = ucfirst(strtolower($username)); // keep first letter uppercase and lowercase everything
    $_SESSION['username'] = $username; // store first name into session variable

    //email
    $email = strip_tags($email);//remove html tags
    $email = str_replace(' ','',$email);// remove spaces
    $_SESSION['email'] = $email; // store email into session variable

    //password
    $password = strip_tags($password);//remove html tags
    $password = md5($password);//encrypting password

    // check if email is in validd format
    if(filter_var($email, FILTER_VALIDATE_EMAIL)){

        // if email is good then email equals the validated version
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);

        //check if email already exist
        $e_check = "SELECT email FROM users WHERE email = ?";
        $stmt = $connection->prepare($e_check);
        $stmt->bind_param("s",$email);
        $stmt->execute();
        $result = $stmt->get_result();

        //Count number of rows returned
        $num_rows = mysqli_num_rows($result);

        if($num_rows>0){
            array_push($error_array,"Email already in use<br>");
        }

    }else{
        array_push($error_array,"Invalid email format");
    }
       
    // check if username exist
    $check_username_query ="SELECT username FROM users WHERE username = ?";
    $stmt = $connection->prepare($check_username_query);
    $stmt->bind_param("s",$username);
    $stmt->execute();
    $result = $stmt->get_result();

    //Count number of rows returned
    $num_rows = mysqli_num_rows($result);

    if($num_rows>0){
        array_push($error_array,"Username already in use<br>");
    }

    // if the array is empty (no errors)
    if(empty($error_array)){

        // insert values to database
        $query = "INSERT INTO `users`(`username`, `email`, `password`) VALUES (?,?,?)";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("sss",$username,$email,$password);
        $stmt->execute();
        $id = $stmt->insert_id;
        $_SESSION['user_id'] = $id;

        $error_array[] = ["."];
        $json = json_encode($error_array);
        echo $json;

    }else{
        $error_array[] = ["."];
        $json = json_encode($error_array);
        echo $json;
    }

?>