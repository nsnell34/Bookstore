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

class userInfo{

    private $conn;
    public function __construct()
    {
        $databaseConnection = new Database();
        $this->conn = $databaseConnection->conn;
    }

    public function DisplayInfo(){

        $name = ($_GET['name']);
        //echo($_GET['password'] . "<br>");

        $sql = "SELECT * FROM `customer` WHERE `name` = '$name'";
        $rows = $this->conn->query($sql);
        $results = $rows->fetch_assoc();

        $bookISBNs = "SELECT `ISBN`, `quantity`, `title` FROM `purchases` WHERE `custID` = '" . $results['ID'] . "'";
        $books = $this->conn->query($bookISBNs);


        ?>
        <form method="post" action="return.php" class="form-button">
            <input type="submit" name="act" value="Back">
            <input type="hidden" name="interface" value="6">
            <?php if ($_GET['password'] == 'admin') { ?>
                <input type="hidden" name="name" value="admin">
            <?php } else { ?>
                <input type="hidden" name="name" value="<?php echo($_GET['name']); ?>">
            <?php } ?>
            <input type="hidden" name="password" value="<?php echo($_GET['password']); ?>">
        </form>


        <link href="table.css" rel="stylesheet">
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>total Spent</th>
                <th>Purchase History</th>
                <th>Quantity</th>
            </tr>
            <tr>
                <td><?=$results['ID']?></td>
                <td><?=$results['name']?></td>
                <td><?=$results['total_spent']?></td>
                <td><?php while ($bookResult = $books->fetch_assoc()) {
                        echo $bookResult['title'] . "<br>";
                    }
                    ?>
                </td>
                <td>
                    <?php $books->data_seek(0);
                    while ($bookResult = $books->fetch_assoc()) {
                        echo $bookResult['quantity'] . "<br>";
                    }
                    ?>
                </td>
            </tr>
        </table>

        <div style="margin-top: 20px;"></div>

        <form method="post" action="check.php">
            <label for="displayPassword">Display Password:</label>
            <input type="password" id="displayPassword" name="displayPassword">
            <input type="submit" name="act" value="Display Source">
            <input type="hidden" name="interface" value="6">


        </form>
<?php


    }

}

$userInfo = new userInfo();
$userInfo->DisplayInfo();

?>


</body>
</html>