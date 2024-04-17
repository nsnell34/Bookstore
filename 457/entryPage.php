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

class EntryPage {
    public function navigatePage() {
        $name = ($_POST['name']);
        $password = ($_POST['password']);

        if ($_POST['act'] == 'Update') {
            if ($name == 'admin' && $password == 'admin'){
                header("Location: adminScreen.html");

            } else {
                echo('<script>alert("no admin found")</script>');
                echo '<script>window.history.back();</script>';

            }
        }
        elseif ($_POST['act'] == 'List'){
            echo($name);
            echo($password);
            header("Location: listScreen.html?name=$name&password=$password");
        }
        elseif ($_POST['act'] == 'Sign Out'){
            header("Location: index.html");
        }
        elseif ($_POST['act'] == 'Help'){
            header("Location: help2.html?name=$name&password=$password");
        }

    }
}

$entryPage = new EntryPage();
$entryPage->navigatePage();

?>

</body>
</html>
