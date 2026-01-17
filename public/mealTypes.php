<?php

use JetBrains\PhpStorm\NoReturn;

$storedPreferences = [];

if (isset($_COOKIE['preferences'])) {
    $storedPreferences = json_decode($_COOKIE['preferences'], true);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['deleteCookie'])) {
        deleteCookie();
    }
    if (isset($_POST['updateCookie'])) {
        $preferences = [];
        if (isset($_POST['type_appetizer'])) {
            $preferences[] = 'type_appetizer';
        }
        if (isset($_POST['type_mainCourse'])) {
            $preferences[] = 'type_mainCourse';
        }
        if (isset($_POST['type_dessert'])) {
            $preferences[] = 'type_dessert';
        }

        setcookie("preferences", json_encode($preferences), time() + 600, "/", "localhost");

        // Redirect -> new Request -> Cookie is visible
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}
#[NoReturn]
function deleteCookie(): void
{
    setcookie('preferences', "", time() - 1200);
    unset($_COOKIE['preferences']);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CORE5</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
<h2>Meal Types</h2>
<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <label>
        <input type="checkbox"
               name="type_appetizer"
                <?= in_array('type_appetizer', $storedPreferences) ? 'checked' : '' ?>
        >
        Appetizer
    </label>
    <hr>
    <label>
        <input type="checkbox"
               name="type_mainCourse"
                <?= in_array('type_mainCourse', $storedPreferences) ? 'checked' : '' ?>
        >
        Main Course
    </label>
    <hr>
    <label>
        <input type="checkbox"
               name="type_dessert"
                <?= in_array('type_dessert', $storedPreferences) ? 'checked' : '' ?>
        >
        Dessert
    </label>
    <hr>
    <input type="submit" name="updateCookie" value="Save to Cookie">
</form>
<form method="POST" action="<?= $_SERVER['PHP_SELF']; ?>">
    <input type="submit" name="deleteCookie" value="Reset Preferences">
</form>
</body>
</html>