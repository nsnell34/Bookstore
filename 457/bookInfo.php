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

class bookInfo{

    private $conn;
    public function __construct()
    {
        $databaseConnection = new Database();
        $this->conn = $databaseConnection->conn;
    }

    public function DisplayInfo(){

        $title = ($_GET['title']);

        $sql = "SELECT * FROM `book` WHERE `title` = '$title'";
        $rows = $this->conn->query($sql);
        $results = $rows->fetch_assoc();

        $quantities = "SELECT `quantity` FROM `purchases` WHERE `title` = '" . $results['title'] . "'";
        $books = $this->conn->query($quantities);
        $totalQuantity = 0;

        $bookResults = array();
        if ($books && $books->num_rows > 0) {
            while ($bookResult = $books->fetch_assoc()) {
                $bookResults[] = $bookResult['quantity'];
            }
            $totalQuantity = array_sum($bookResults);
        }


        ?>
        <form method="post" action="return.php" class="form-button">
            <input type="submit" name="act" value="Back">
            <input type="hidden" name="interface" value="7">
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
                <th>ISBN</th>
                <th>Title</th>
                <th>Price</th>
                <th>Quantity Sold</th>
            </tr>
            <tr>
                <td><?=$results['ISBN']?></td>
                <td><?=$results['title']?></td>
                <td><?=$results['price']?></td>
                <td><?=$totalQuantity?></td>
            </tr>



        </table>

        <div style="margin-top: 20px;"></div>

        <form method="post" action="check.php">
            <label for="displayPassword">Display Password:</label>
            <input type="password" id="displayPassword" name="displayPassword">
            <input type="submit" name="act" value="Display Source">
            <input type="hidden" name="interface" value="7">


        </form>
        <?php


    }

}

$bookInfo = new bookInfo();
$bookInfo->DisplayInfo();

?>


</body>
</html>
