<?php
require 'include/Parsedown.php';

$ini = parse_ini_file("config.ini");
//
$title = $ini['title'];
$description = $ini['description'];
$keywords = $ini['keywords'];
$favicon = $ini['favicon'];
$defaultTheme = $ini['defaultTheme'];

$modules = explode(',', $ini['modules']);

$Parsedown = new Parsedown();

// Tricks

// variables that modules can edit to add additional content to each respective tag
$additionalHead = '';
$additionalBody = '';

// load the selected modules' *.php files
foreach ($modules as $module) {
    // include the .php file in modules/$module/
    include_once 'modules/' . $module . '/main.php';
}

function create_page_contents()
{
    if (isset($_GET['page'])) {
        $pageValue = $_GET['page'];
        if ($pageValue == "index") {

        }
    } else {
        // No 'page' parameter found in the URL redirect to ?page=Main
        header("Location:?page=index");
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>
        <?php echo $title; ?>
    </title>
    <link rel="icon" type="image/x-icon" href="<?php echo $favicon; ?>">
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/themes/<?php echo $defaultTheme; ?>">
    <meta name="keywords" content="<?php echo $description; ?>">
    <?php
    echo $additionalHead;
    ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <!-- Left column -->
    <div><p>Test</p></div>
    <!-- Middle column -->
    <div>
        <div class="content">
        <p>Test</p>
            <?php
            create_page_contents();
            echo $additionalBody;
            ?>
        </div>
    </div>
    <!-- Right column -->
    <div><p>Test</p></div>
</body>

</html>