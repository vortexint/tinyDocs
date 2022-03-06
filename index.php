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
    $has_config = true;
    $wiki_config = parse_ini_file("resources/wikiconf.ini");
    $wiki_name = $wiki_config['wiki_name'];
    $wiki_description = $wiki_config['wiki_description'];
    $wiki_color = $wiki_config['wiki_color'];
    $wiki_default_theme = $wiki_config['wiki_default_theme'];
}
else {
    $has_config = false; 
    $wiki_name = "Tiny-wiki";
    $wiki_description = "A simple folder-structure-based wiki engine";
    $wiki_color = "#0099ff";
    $wiki_default_theme = "default";
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

if (!$has_config) {
    // For obvious security reasons, the POST check should only be done if the wikiconf file doesn't exist
    // otherwise people could just reconfigure the site easily with POST data, crazy right?
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // collect value of form
        $wiki_name = $_POST['wiki_name'];
        $wiki_description = $_POST['wiki_description'];
        $wiki_color = $_POST['wiki_color'];
        $wiki_default_theme = $_POST['wiki_default_theme'];
        // write to ini file
        $file = fopen("resources\wikiconf.ini", "w") or die("Unable to open file!");
        fwrite($file, "wiki_name = \"$wiki_name\"\n");
        fwrite($file, "wiki_description = \"$wiki_description\"\n");
        fwrite($file, "wiki_color = \"$wiki_color\"\n");
        fwrite($file, "wiki_default_theme = \"$wiki_default_theme\"\n");
        fclose($file);
    }
    // Setup page basically
    echo "<link rel='stylesheet' href='resources/default.css' type='text/css' />";
    echo "<div class='setup-page'>";
    echo "<h1>Welcome to Tiny-wiki!</h1>";
    echo "<p>It looks like this is the first time you are using Tiny-Wiki.</p>";
    echo "<p>To make this part pleasant, we've made this configuration form for you!</p>";
    echo "<p>Configure your wiki using the options below.</p>";
    echo "<form action='index.php' method='POST' enctype='multipart/form-data'>";
    echo "<label for='wiki_name'>Wiki Name:</label>";
    echo "<input type='text' class='inputfield' name='wiki_name' value='$wiki_name' placeholder='Wiki Name'><br>";
    echo "<label for='wiki_description'>Wiki Description:</label>";
    echo "<input type='textarea' class='inputfield' name='wiki_description' value='$wiki_description' placeholder='Wiki Description'><br>";
    echo "<label for='wiki_color'>Wiki Secondary Color:</label>";
    echo "<input type='color' class='inputfield' name='wiki_color' value='$wiki_color'><br>";
    echo "<label for='wiki_default_theme'>Wiki Default Cascading Style Sheet:</label>";
    echo "<input type='text' class='inputfield' name='wiki_default_theme' value='$wiki_default_theme' placeholder='default.css'><br>";
    echo "<label for='setup'>You can change these settings later at resources/wikiconf.ini, press Setup when done.</label>";
    echo "<input type='submit' class='setup' name='setup' value='Setup'>";
    echo "<script>
    // post setup form data to resources/config.php when submit button is clicked
    document.getElementsByClassName('setup')[0].addEventListener('click', function(e) {
        e.preventDefault();
        var wiki_name = document.getElementsByName('wiki_name')[0].value;
        var wiki_description = document.getElementsByName('wiki_description')[0].value;
        var wiki_color = document.getElementsByName('wiki_color')[0].value;
        var wiki_default_theme = document.getElementsByName('wiki_default_theme')[0].value;
        var formData = new FormData();
        formData.append('wiki_name', wiki_name);
        formData.append('wiki_description', wiki_description);
        formData.append('wiki_color', wiki_color);
        formData.append('wiki_default_theme', wiki_default_theme);
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'index.php', true);
        xhr.send(formData);
        xhr.onload = function() {
            if (xhr.status === 200) {
                window.location.href = 'index.php';
            }
        }
    });
    </script>";
    echo "</form>";
    echo "</div>";
    // script to POST the form data to resources/config.php

    return;
}    
?>
<!DOCTYPE html>
<html>
<head>
<?php
echo '<title>' . $wiki_name . '</title>';
echo '<link rel="shortcut icon" href="resources\\favicon.png">';
?>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php
    echo '<link rel="stylesheet" href="resources\\' . $wiki_default_theme . '.css">';
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
echo '<h1><a href="?p=Home">' . $wiki_name . '</a></h1>';
echo '<p>' . $wiki_description . '</p>';
?>
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
        echo '<a class="page" href="?p=' . $folder . '">' . $folder_n . '</a>';
        // check for all subfolders in folder, count for index and if they have a config.ini
        // if the subfolder has a config.ini, set $config to parse config.ini
        // if it's not the last subfolder, add ├ before $folder_n
        // if it's the last subfolder, add └ before $folder_n
        $subfolders = scandir("docs/" . $folder);
        $real_subfolders = array();
        // count all subfolders
        foreach ($subfolders as $subfolder) {
            if ($subfolder != "." && $subfolder != ".." && is_dir("docs/" . $folder . "/" . $subfolder)) {
                array_push($real_subfolders, $subfolder);
            }
        }
        foreach ($real_subfolders as $subfolder) {
            if (file_exists("docs/" . $folder . "/" . $subfolder . "/config.ini")) {
                $config = parse_ini_file("docs/" . $folder . "/" . $subfolder . "/config.ini");
                $folder_n = $config["page_name"];
            } else {
                $folder_n = "ERR NO CONFIG.INI";
            }
            if ($subfolder == $real_subfolders[count($real_subfolders) - 1]) {
                echo '<a class="subpage" href="?p=' . $folder . '/' . $subfolder . '">└ ' . $folder_n . '</a>';
            } else {
                echo '<a class="subpage" href="?p=' . $folder . '/' . $subfolder . '">├ ' . $folder_n . '</a>';
            }
        }
        
    }
}?>
</div>
</div>
<div class="content">
<div class="content-topbar">
<div class="content-current-page">
<?php
// link to current page and page title
if ($page == "") {
    $current_page_ini = parse_ini_file("docs/Home/config.ini");
    echo '<h1><a href='.$current_link.'>' . $current_page_ini["page_name"] . '</a></h1>';;
} else {
    $current_page_ini = parse_ini_file("docs/" . $page . "/config.ini");
    echo '<h1><a href='.$current_link.'>' . $current_page_ini["page_name"] . '</a></h1>';
}
?>
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