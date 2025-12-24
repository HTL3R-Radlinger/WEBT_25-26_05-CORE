<?php
$preferences = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
    <label for="type_appetizer">
        <input type="checkbox" name="type_appetizer" id="type_appetizer">
        Appetizer
    </label>
    <hr>
    <label for="type_mainCourse">
        <input type="checkbox" name="type_mainCourse" id="type_mainCourse">
        Main Course
    </label>
    <hr>
    <label for="type_dessert">
        <input type="checkbox" name="type_dessert" id="type_dessert">
        Dessert
    </label>
    <hr>
    <input type="submit" name="submit" value="Send">
</form>
<?php
echo $_COOKIE["preferences"];
if (!empty($preferences)) {
    echo '<pre>' . json_encode($preferences, JSON_PRETTY_PRINT) . '</pre>';
}
?>
</body>
</html>