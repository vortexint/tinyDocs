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

$page = isset($_GET['p']) ? $_GET['p'] : 'index'; // if nothing, default to index

if (is_dir('content/' . $page)) // if this is true, it's a category
    $page = $page . '/index';

$md_path = 'content/' . $page . '.md';
$page_exists = file_exists($md_path);


// variables that modules can edit to add additional content to each respective tag
$additionalHead = '';
$additionalContent = '';
$additionalBody = '';

// load the selected modules' *.php files
foreach ($modules as $module) {
    // include the .php file in modules/$module/
    include_once 'modules/' . $module . '/main.php';
}

function create_page_contents()
{
    $Parsedown = new Parsedown();
    // check if the page exists
    if (!$GLOBALS['page_exists']) {
        ?>
        <h1>Error 404</h1>
        <p>The page "
            <?php echo $GLOBALS['page']; ?>" does not exist.
        </p>
        <p>Return to <a href="/">Home</a></p>
        <?php
        return;
    }
    echo $Parsedown->text(file_get_contents($GLOBALS['md_path']));
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
            <div class="left">
                <p class="breadcrumbs">
                    <?php
                    // Breadcrumbs
                    $page = isset($_GET['p']) ? $_GET['p'] : '';
                    $breadcrumbs = explode('/', $page);

                    // Simplify conditional check
                    if (count($breadcrumbs) === 1 && empty($breadcrumbs[0])) {
                        // Home page
                        echo '<a href="/">' . $title . '</a>';
                    } else {
                        $breadcrumbLinks = [];
                        $currentPath = '';

                        foreach ($breadcrumbs as $breadcrumb) {
                            // Use empty() to check for empty breadcrumbs
                            if (!empty($breadcrumb)) {
                                $currentPath .= '/' . $breadcrumb;
                                $breadcrumbLinks[] = '<a href="' . ($currentPath === '/' ? '/' : '?p=' . ltrim($currentPath, '/')) . '">' . ucfirst($breadcrumb) . '</a>';
                            }
                        }
                        echo '<a href="/">' . $title . '</a> / ' . implode(' / ', $breadcrumbLinks);
                    }
                    ?>
                </p>
            </div>
            <div class="right">
                <input type="search" placeholder="Search <?php echo $title ?> "><input class="search" type="button"
                    value="Search">
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
                if ($page_exists)
                    echo "Page last modified on " . date('F d Y H:i:s', filemtime($md_path))
                        ?>
                </p>
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