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
class processSelection
{
    private $conn;
    public function __construct()
    {
        $databaseConnection = new Database();
        $this->conn = $databaseConnection->conn;
    }

    public function processSelection()
    {

        $username = strtolower($_POST['name']);
        $userPass = $_POST['password'];

        if ($_POST['act'] == 'Back') {
            header("Location: listScreen.html?name=$username&password=$userPass");
        } elseif($_POST['act'] == 'Complete Purchase'){
            $this->completePurchase($username);
            echo('<script>alert("Purchase Complete")</script>');
            echo('<script>window.history.back()</script>');
        }


        $this->conn->close();
    }

    public function completePurchase($username)
    {
        if (!isset($_POST['selected_books']) || !is_array($_POST['selected_books'])) {
            echo '<script>alert("No books selected")</script>';
            echo '<script>window.history.back()</script>';
        }

        $selectedBooks = $_POST['selected_books'];
        $quantities = $_POST['quantity'];
        $totalAmount = 0;
        $user = "SELECT `ID` FROM `customer` WHERE `name` = '$username'";
        $userResult = $this->conn->query($user);
        $userRow = $userResult->fetch_assoc();
        $userID = $userRow['ID'];

        foreach ($selectedBooks as $isbn) {
            $quantity = $quantities[$isbn];

            $sql = "SELECT `price`, `title` FROM `book` WHERE `ISBN` = '$isbn'";
            $result = $this->conn->query($sql);
            $row = $result->fetch_assoc();
            $price = $row['price'];
            $title = $row['title'];
            $totalAmount += ($price * $quantity);

            $enterPurchase = "INSERT INTO `purchases` (`custID`, `ISBN`, `quantity`, `title` ) VALUES ('$userID', '$isbn', '$quantity','$title')";
            if ($quantity != 0){
                $this->conn->query($enterPurchase);
            }
            //echo("<br>");
            //echo "ISBN: $isbn, Quantity: $quantity\n";
        }

        $gatherTotal = "SELECT `total_spent` FROM `customer` WHERE `ID` = '$userID'";
        $rows = $this->conn->query($gatherTotal);
        $totalRow = $rows->fetch_assoc();
        $runningTotal = $totalRow['total_spent'];
        $runningTotal += $totalAmount;

        $updateTotal = "UPDATE `customer` SET `total_spent` = '$runningTotal' WHERE `ID` = '$userID'";
        $this->conn->query($updateTotal);
    }
}

$selection = new processSelection;
$selection->processSelection();


?>

</body>
</html>