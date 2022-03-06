<?php
// Tiny-wiki is a PHP-based code wiki engine system for simple documentation.
// using highlight.js, and a custom markup language for each page (.tinyw)
// unlike other wiki engines, this one provides no login system, simply displays
// the files at root/docs/ in a formatted style
//
// Tiny Markup Syntax:
// <code="lua">
// -- lua code
// </code>
// <code="cpp">
// // cpp code
// </code>
// *bold text*
// _italic text_
// _*bold and italic*_
// <u>underline text</u>
// <s>strikethrough text</s>
// <img="image.png">
// <link="http://example.com">link text</link>
// <html>html code</html>
// # comment

// The left sidebar is a list of folders with an index.tinyw file at the root/docs/ folder.

// USER-DEFINED Variables
// if resources/wikiconf.ini exists
if (file_exists("resources/wikiconf.ini")) {
    $wiki_config = parse_ini_file("resources/wikiconf.ini");
    $wiki_name = $wiki_config['wiki_name'];
    $wiki_description = $wiki_config['wiki_description'];
    $wiki_color = $wiki_config['wiki_color'];
    $wiki_default_theme = $wiki_config['wiki_default_theme'];
    $wiki_favicon = $wiki_config['wiki_favicon'];
}

// Don't change anything below unless you know what you're doing.

// page url example:
// docs/hello/index.tinyw would be displayed as: http://example.com/wiki?p=hello
// docs/hello/world/index.tinyw would be displayed as: http://example.com/wiki?p=hello/world

$current_link = $_SERVER['REQUEST_URI'];
if (strpos($current_link, "?p=") !== false) {
    $page = substr($current_link, strpos($current_link, "?p=") + 3);
} else {
    $page = "";
}

/* Include Parsers */
include "bin/tinywparse.php";
include "bin/Parsedown.php";

/* --- Page Setup --- */

?>
<!DOCTYPE html>
<html>
<head>
<?php
echo '<title>' . $wiki_name . '</title>';
if ($wiki_favicon != "") {
    echo '<link rel="shortcut icon" href="resources\\' . $wiki_favicon . '">';
}
?>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php
    echo '<link rel="stylesheet" href="resources/' . $wiki_default_theme . '">';
?>
<link rel="stylesheet" href="js\styles\atom-one-dark.min.css">
<script src="js\highlight.min.js"></script>
<script>hljs.highlightAll();</script>
</head>
<body>
<div class="sidebar" name="second-color">
<!--wiki name div-->
<div class="sidebar-wiki-name" name="second-color">
<?php
echo '<p><a href="?p=home">' . $wiki_name . '</a></p>';?>
</div>
<!--wiki auto-generated buttons div-->
<div class="sidebar-list">
<input type="text" class="sidebar-search" id="search-input" placeholder="Search">
<?php
// for each folder in root/docs/
$folders = scandir("docs");
foreach ($folders as $folder) {
    if ($folder != "." && $folder != "..") {
        $config = parse_ini_file("docs/" . $folder . "/config.ini");
        // if folder's name is "Home"
        if ($folder == "Home") 
        {
            $folder_n = $config["page_name"];
            continue;
        }
        // if folder has config.ini, set $folder to value after "page_name =" in config.ini
        if (file_exists("docs/" . $folder . "/config.ini")) {
            $folder_n = $config["page_name"];
        }
        echo '<a href="?p=' . $folder . '">' . $folder_n . '</a>';	
    }
}?>
</div>
</div>
<div class="content">
<div class="content-topbar">
<div class="content-current-page">
<?php
// link to current page and page title
echo '<h1><a href='.$current_link.'>'.$folder_n.'</a></h1>';?>
</div>
</div>
<div class="content-container">
<?php
// if $page is empty, echo the docs/Home/index.md or index.tinyw file
if ($page == "") {
    if (file_exists("docs/Home/index.md")) {
        $file = file_get_contents("docs/Home/index.md");
        $parsedown = new Parsedown();
        echo $parsedown->text($file);
    }
    else if (file_exists("docs/Home/index.tinyw")) {
        $file = file_get_contents("index.tinyw");
        tinyw_parse($file);
    }
    else {
        echo '<p>No Home page found.</p>';
    }
}
else {
    // if $page is not empty, echo the docs/page/index.md or index.tinyw file
    if (file_exists("docs/" . $page . "/index.md")) {
        $file = file_get_contents("docs/" . $page . "/index.md");
        $parsedown = new Parsedown();
        echo $parsedown->text($file);
    }
    else if (file_exists("docs/" . $page . "/index.tinyw")) {
        $file = file_get_contents("docs/" . $page . "/index.tinyw");
        tinyw_parse($file);
    }
}
?>
</div>
</div>
<script>
var sidebar_list = document.getElementsByClassName("sidebar-list")[0];
var sidebar_list_a = sidebar_list.getElementsByTagName("a");
for (var i = 0; i < sidebar_list_a.length; i++) {
    if (i % 2 == 1) {
        sidebar_list_a[i].style.backgroundColor = "#0b0c0c";
    }
}
// search bar, hides non-matching links';
var sidebar_search = document.getElementById("search-input");
sidebar_search.addEventListener("keyup", function(event) {
var search_input = event.target.value;
var sidebar_list_a = sidebar_list.getElementsByTagName("a");
for (var i = 0; i < sidebar_list_a.length; i++) {
    if (sidebar_list_a[i].innerHTML.toLowerCase().indexOf(search_input.toLowerCase()) == -1) {
        sidebar_list_a[i].style.display = "none";
    }
    else {
        sidebar_list_a[i].style.display = "block";
    }
}
}
);
// Make elements with id="second-color" have the second color
// get elements by name
var second_colors = document.getElementsByName("second-color");
for (var i = 0; i < second_colors.length; i++) {
    second_colors[i].style.background = "<?php echo $wiki_color; ?>";
}
</script>
</body>
</html>