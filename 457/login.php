
<html>
<body>
<?php

error_reporting(-1);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL ^ E_WARNING ^ E_DEPRECATED);
ini_set("session.gc_maxlifetime", "180000");

ignore_user_abort(1);
set_time_limit(1800);

include_once('Database.php');

class Login
{
    private $conn;
    public function __construct()
    {
        $databaseConnection = new Database();
        $this->conn = $databaseConnection->conn;
    }
    public function loginUser()
    {
        //print_r($_POST);
        $username = strtolower($_POST['name']);
        $userPass = $_POST['password'];

        if ($_POST['act'] == 'Register') {
            $result = $this->checkUser($username);
            if ($result == 0) {
                $authorized = $this->registerUser($username, $userPass);
                if ($authorized == 1) {
                    header("Location: enter.html?name=$username&password=$userPass");
                } else {
                    echo("Error registering user");
                }
            } else {
                echo '<script>alert("User already registered");</script>';
                echo '<script>window.history.back();</script>';

            }

        } elseif ($_POST['act'] == 'Enter') {
            $result = $this->checkUserPass($username, $userPass);
            if ($result == 1) {
                header("Location: enter.html?name=$username&password=$userPass");
            } elseif ($result == 0) {
                echo '<script>alert("User not found");</script>';
                echo '<script>window.history.back();</script>';
            }


        } elseif ($_POST['act'] == 'Clear System') {
            $sql = file_get_contents('clearTables.sql');
            if ($this->conn->multi_query($sql) === TRUE) {
                echo '<script>alert("Database Cleared.");</script>';
                echo '<script>window.history.back();</script>';
            } else {
                echo "Could not clear system - " . $this->conn->error;
            }
        } elseif ($_POST['act'] == 'Help') {
            header("Location: help.html");
        }

        $this->conn->close();
    }

    public function registerUser($username, $password)
    {
        $sql = "INSERT INTO `customer` (`name`, `password`) VALUES ('$username', '$password')";
        if ($this->conn->query($sql) === TRUE) {
            return 1;
        } else {
            echo "Error: " . $sql . "<br>" . $this->conn->error;
            return 0;
        }
    }

    public function checkUser($username)
    {
        $userInfo = "SELECT `name`FROM `customer` WHERE `name` = '$username'";
        $result = $this->conn->query($userInfo);

        if ($result !== false && $result->num_rows > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public function checkUserPass($username, $password)
    {
        $userInfo = "SELECT `name`, `password` FROM `customer` WHERE `name` = '$username' AND `password` = '$password'";
        $result = $this->conn->query($userInfo);

        if ($result !== false && $result->num_rows > 0) {
            //echo "Selection returned";
            return 1;
        } else {
            return 0;
        }
    }
}

$login = new Login();
$login->loginUser();

?>

</body>
</html>