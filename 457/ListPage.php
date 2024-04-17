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

class ListPage
{
    private $conn;

    public function __construct()
    {
        $databaseConnection = new Database();
        $this->conn = $databaseConnection->conn;
    }

    public function NavigatePage()
    {
        $name = $_POST['name'];
        $password = $_POST['password'];
        $keywords = $_POST['keywords'];


        if ($_POST['act'] == 'List') {
            if ($_POST['choice'] == 'Books') {
                header("Location: bookRetrieval.php?name=$name&password=$password&keywords=$keywords");
            } elseif ($_POST['choice'] == 'Users') {
                if ($_POST['name'] == 'admin' && $_POST['password'] == 'admin') {
                    $users = $this->selectUsers();
                    ?>
                    <link href="table.css" rel="stylesheet">
                    <form method="post" action="return.php" class="form-button">
                        <input type="submit" name="act" value="Back">
                        <input type="hidden" name="interface" value="3">
                        <input type="hidden" name="name" id="nameInput">
                        <input type="hidden" name="password" id="passwordInput">
                    </form>
                <h2>USERS</h2>
                <?php
                $this->selectTotalSpent($users, 'admin');
                } else {
                $result = $this->selectSingleUser($name);
                ?>
                    <link href="table.css" rel="stylesheet">
                    <form method="post" action="return.php" class="form-button">
                        <input type="submit" name="act" value="Back">
                        <input type="hidden" name="interface" value="5">
                        <input type="hidden" name="name" value="<?php echo($_POST['name']); ?>">
                        <input type="hidden" name="password" value="<?php echo($_POST['password']); ?>">
                    </form>
                    <h2>YOUR ACCOUNT INFO</h2>
                <?php
                $this->selectTotalSpent($result, $_POST['password']);
                }
            }
        } elseif ($_POST['act'] == 'Back') {
            header("Location: enter.html?name=$name&password=$password");
        } elseif ($_POST['act'] == 'Help') {
            header("Location: help3.html?name=$name&password=$password");
        }

            $this->conn->close();
    }
    public function selectUsers()
    {
        $users = "SELECT `name` FROM `customer`";
        $result = $this->conn->query($users);

        $userList = array();
        while ($row = $result->fetch_assoc()) {
            $userList[] = $row;
        }

        return $userList;
    }

    public function selectSingleUser($username){
        $user = "SELECT `name` FROM `customer` WHERE `name` = '$username'";
        $result = $this->conn->query($user);

        $users = array();
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }

        return $users;
}

    public function selectTotalSpent($users, $password)
    {
    ?>
    <table border='1'>
        <tr>
            <th>Name</th>
            <th>Total Spent</th>
        </tr>
        <?php
        foreach ($users as $user) {
            $name = $user['name'];
            $totalSpent = $this->getTotalSpentForUser($name);
            echo "<tr><td><a href='userInfo.php?name=" . $name . "&password=" . $password . "'>" . $name . "</a></td><td>$" . number_format($totalSpent, 2) . "</td></tr>";
        }
        echo "</table>";
        }

    private function getTotalSpentForUser($userName)
    {
        $query = "SELECT `total_spent` FROM `customer` WHERE `name` = '$userName'";
        $result = $this->conn->query($query);

        if ($result !== false && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['total_spent'];
        }

    }
}

$listPage = new ListPage();
$listPage->NavigatePage();

?>
</body>
</html>

