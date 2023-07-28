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

$searchQuery = isset($_GET['q']) ? $_GET['q'] : '';

$page = isset($_GET['p']) ? $_GET['p'] : 'index';

if (!$searchQuery) {

if (is_dir('content/' . $page)) // if this is true, it's a category
    $page = $page . '/index';

$md_path = 'content/' . $page . '.md';
$page_exists = file_exists($md_path);
}
else
    $page = null;

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
    if ($GLOBALS['searchQuery']) {
        $startTime = microtime(true);

        echo "<h1>Search results for " . $GLOBALS['searchQuery'] . "</h1>";

        // Get all the files and subdirectories in the content directory
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator('content/')
        );

        $matchingTitles = [];

        // Iterate through each file in the content directory
        foreach ($files as $file) {
            if ($file->isFile()) {
                // extract the file name without the extension
                $fileName = pathinfo($file->getFilename(), PATHINFO_FILENAME);

                // levenshtein distance between the search query and the filename
                $distance = levenshtein(strtolower($GLOBALS['searchQuery']), strtolower($fileName));

                // similarity match
                $threshold = 3;

                if ($distance !== false && $distance <= $threshold) {
                    // Store the file name and its distance for later use
                    $matchingTitles[$fileName] = $distance;
                }
            }
        }

        // Sort the matching titles based on their Levenshtein distance (optional step)
        asort($matchingTitles);

        $numResults = count($matchingTitles);

        $endTime = microtime(true);
        $timeTaken = number_format(($endTime - $startTime),5);

        echo "<p>About $numResults results ($timeTaken seconds)</p>";
        echo "<h2>Matching titles</h2>";
        echo "<ol>";

        $i = 1;
        foreach ($matchingTitles as $title => $distance) {
            $urlTitle = strtolower(str_replace(' ', '-', $title));
            echo "<li><a href=\"$urlTitle\" class=\"searchlink\">$title</a>";
            // Read the contents of the Markdown file
            $md_path = 'content/' . $urlTitle . '.md';
            $md_contents = file_get_contents($md_path);

            // number of characters to show in the sample
            $numCharacters = 200;
            $sample = strip_tags($Parsedown->text($md_contents));
            $sample = substr($sample, 0, $numCharacters);

            // If the content is longer than the defined number of characters, find the last space and truncate the text
            if (strlen($md_contents) > $numCharacters) {
                $lastSpacePos = strrpos($sample, ' ');
                if ($lastSpacePos !== false) {
                    $sample = substr($sample, 0, $lastSpacePos);
                }
                $sample .= '...';
            }

            // file data
            $fileSize = formatFileSize(filesize($md_path));

            echo "<p class=\"search-sample\">$sample</p>";
            echo "<p class=\"search-result-data\">$fileSize</p>";
            echo "</li>";
        }

        echo "</ol>";
    }
    else
        if (!$GLOBALS['page_exists']) {
        ?>
        <h1>Error 404</h1>
        <p>The page "
            <?php echo $GLOBALS['page']; ?>" does not exist.
        </p>
        <p>Return to <a href="/">Home</a></p>
        <?php
    }
    else
        echo $Parsedown->text(file_get_contents($GLOBALS['md_path']));

    }

    function formatFileSize($bytes) {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $base = 1024;
        $index = floor(log($bytes, $base));
        $formattedSize = round($bytes / pow($base, $index), 2) . ' ' . $units[$index];
        return $formattedSize;
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
        <h1><?php echo $title ?></h1>

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

                    if (count($breadcrumbs) === 1 && empty($breadcrumbs[0]))
                        echo '<a href="/">' . $title . '</a>'; // Homepage
                    else {
                        $breadcrumbLinks = [];
                        $currentPath = '';

                        foreach ($breadcrumbs as $breadcrumb) {
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
            <form method="GET" action="/">
            <div class="right">
                <input type="search" name="q" placeholder="Search <?php echo $title ?> "><input class="search" type="submit" value="Search">
            </div>
            </form>
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
        </div>
        <?php
            echo $additionalBody;
        ?>
</body>

</html>