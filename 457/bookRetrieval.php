<?php
error_reporting(-1);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL ^ E_WARNING ^ E_DEPRECATED);
ini_set("session.gc_maxlifetime", "180000");

ignore_user_abort(1);
set_time_limit(1800);

include_once('Database.php');
class bookRetrieval
{
    private $conn;
    public function __construct()
    {
        $databaseConnection = new Database();
        $this->conn = $databaseConnection->conn;
    }
    public function showBooks()
    {
        $keywords = isset($_GET['keywords']) ? $_GET['keywords'] : '';
        $sql = "SELECT * FROM `book`";

        if (!empty($keywords)) {
            $keywordsArray = explode(' ', $keywords);
            $sql .= " WHERE";
            foreach ($keywordsArray as $keyword) {
                $sql .= " UPPER(`title`) LIKE '%" . $this->conn->real_escape_string(strtoupper($keyword)) . "%' OR";
            }

            $sql = rtrim($sql, "OR");
        }

        $result = $this->conn->query($sql);
        ?>
        <link href="table.css" rel="stylesheet">
        <script src="setInputs.js"></script>

        <form method="post" action="processSelection.php" onsubmit="return setHiddenInputs()" >
            <table>
                <tr>
                    <th>Title</th>
                    <th>ISBN</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Select</th>
                </tr>

                <?php
                while ($row = $result->fetch_assoc()) {
                    ?>
                    <tr>
                        <td><a href='bookInfo.php?name=<?=$_GET['name']?>&password=<?=$_GET['password']?>&title=<?= $row['title'] ?>'><?= $row['title'] ?></a></td>
                        <td><?= $row['ISBN'] ?></td>
                        <td>$<?= number_format($row['price'], 2) ?></td>
                        <td><input type="number" name="quantity[<?= $row['ISBN'] ?>]" value="0" min="0"></td>
                        <td><input type="checkbox" name="selected_books[]" value="<?= $row['ISBN'] ?>"></td>
                    </tr>
                    <?php
                }
                ?>
            </table>

            <input type="submit" name='act' value="Back">
            <input type="submit" name='act' value="Complete Purchase">

            <input type="hidden" name="name" id="nameInput">
            <input type="hidden" name="password" id="passwordInput">
            <input type="hidden" name="keywords" id="keywords">


        </form>

        <div style="margin-top: 20px;"></div>

        </form>
        <form method="post" action="check.php">
            <label for="displayPassword">Display Password:</label>
            <input type="password" id="displayPassword" name="displayPassword">
            <input type="submit" name="act" value="Display Source">
            <input type="hidden" name="interface" value="5">


        </form>

        <?php
        $this->conn->close();
    }
}
$books = new bookRetrieval();
$books->showBooks();


?>

