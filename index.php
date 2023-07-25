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

// Tricks //

// State

$page = $_GET['p'];


if ( $page === 'index') {
    header('Location: /');
    exit;
}

if (!$page)
    $page = 'index';

$page_path = 'content/' . $page . '.md';
$page_exists = file_exists($page_path);

// if "index" is in the URL, remove it

// variables that modules can edit to add additional content to each respective tag
$additionalHead;
$additionalContent = '';
$additionalBody = '';

// load the selected modules' *.php files
foreach ($modules as $module) {
    // include the .php file in modules/$module/
    include_once 'modules/' . $module . '/main.php';
}

function create_page_contents()
{
    if ($GLOBALS['page']) {
        $Parsedown = new Parsedown();
        // check if the page exists
        if (!$GLOBALS['page_exists']) {
            ?>
            <h1>Error 404</h1>
            <p>The page "<?php echo $GLOBALS['page']; ?>" does not exist.</p>
            <?php
            return;
        }
        echo $Parsedown->text(file_get_contents($GLOBALS['page_path']));
    } else {
        // No 'page' parameter found in the URL redirect to ?page=Main
        header("Location:?p=index");
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
    <script>
    hljs.highlightAll();
    </script>
</head>

<body>
    <!-- Left column -->
    <div>
        <p>Test</p>
    </div>
    <!-- Middle column -->
    <div>
        <div class="navigation">
            <div class="right">
                <input type="search" placeholder="Search <?php echo $title?> "><input class="search" type="button" value="Search">
            </div>
        </div>
        <div class="content">
            <?php
            create_page_contents();
            echo $additionalContent;
            ?>
        </div>
        <footer>
            <p>
            <?php
            if (isset($page))
                echo "Page last modified on " . date('F d Y H:i:s', filemtime($page_path))
            ?></p>
        </footer>
    </div>
    <!-- Right column -->
    <div>
        <p>Test</p>
    </div>
    <?php
    echo $additionalBody;
    ?>
</body>
</html>