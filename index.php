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

// Variables
$wiki_name = "Tiny-wiki";
$wiki_description = "A PHP-based code wiki engine system for simple documentation.";
$wiki_author = "Vortex-dev";

// Don't change anything below unless you know what you're doing.

// page url example:
// docs/hello/index.tinyw would be displayed as: http://example.com/wiki?p=hello
// docs/hello/world/index.tinyw would be displayed as: http://example.com/wiki?p=hello/world
// docs/index.tny would be displayed as: http://example.com/wiki

$current_link = $_SERVER['REQUEST_URI'];
$page = substr($current_link, strpos($current_link, "?p=") + 3);

// .TinyW parser
// functionality:
// echo parse_tinyw($text);
// the parser will rewrite the text and replace all the tags with the appropriate html code
function tinywparse($text) {
    // check each line for a pair of opening and closing tags
    // <code="language">source code</code> should translate to <pre><code class="language">source code</code></pre>
    // <img="image.png"> should translate to <img src="image.png">
    // <link="http://example.com">link text</link> should translate to <a href="http://example.com">link text</a>
    // <html>html code</html> should get everything inside the tags
    // <u>underline text</u> should translate to <u>underline text</u>
    // <s>strikethrough text</s> should translate to <strike>strikethrough text</strike>
    // *bold text* should translate to <b>bold text</b>
    // _italic text_ should translate to <i>italic text</i>

    // lines with no matches should just be added to the output

    $output = $text;
    // tags that open and close should just be replaced with their html equivalents using preg_replace
    $output = preg_replace("/<code=\"(.*?)\">(.*?)<\/code>/", "<pre><code class=\"$1\">$2</code></pre>", $output);
    $output = preg_replace("/<img=\"(.*?)\">/", "<img src=\"$1\">", $output);
    $output = preg_replace("/<link=\"(.*?)\">(.*?)<\/link>/", "<a href=\"$1\">$2</a>", $output);
    $output = preg_replace("/<html>(.*?)<\/html>/", "$1", $output);
    $output = preg_replace("/<u>(.*?)<\/u>/", "<u>$1</u>", $output);
    // if there are opening and closing * or _, they should be replaced with their html equivalents
    $output = preg_replace("/\*(.*?)\*/", "<b>$1</b>", $output);
    $output = preg_replace("/_(.*?)_/", "<i>$1</i>", $output);
    // everything in the same line after a # (including the # but not the line break) should be made html comment
    $output = preg_replace("/\#(.*?)\n/", "<!-- $1 -->\n", $output);
    // newlines should be replaced with <br>
    $output = preg_replace("/\n/", "<br>", $output);

    return $output;
    
}

// set up html page

echo '<!DOCTYPE html>';
echo '<html>';
echo '<head>';
echo '<title>$wiki_name</title>';
echo '<meta charset="utf-8">';
echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
echo '<link rel="stylesheet" href="resources\style.css">';
echo '<link rel="stylesheet" href="js\styles\atom-one-dark.min.css">';
echo '<script src="js\highlight.min.js"></script>';
echo '<script>hljs.highlightAll();</script>';
echo '</head>';
echo '<body>';
echo '<div class="sidebar">';
echo '<!--wiki name div-->';
echo '<div class="sidebar-wiki-name">';
echo '<p>' . $wiki_name . '</p>';
echo '</div>';
echo '<!--wiki auto-generated buttons div-->';
echo '<div class="sidebar-list">';
echo '<input type="text" class="sidebar-search" id="search-input" placeholder="Search">';
echo '<a href="index.php">Home</a>';
// for each folder in root/docs/
$folders = scandir("docs");
foreach ($folders as $folder) {
    if ($folder != "." && $folder != "..") {
        echo '<a href="?p=' . $folder . '">' . $folder . '</a>';	
    }
}
echo '</div>';
echo '</div>';
echo '<div class="content">';
echo '<div class="content-topbar">';
echo '<div class="content-current-page">';
echo '<h1><a href='.$current_link.'>'.$current_link.'</a></h1>';
echo '</div>';
echo '</div>';
echo '<div class="content-container">';
echo tinywparse(file_get_contents("docs/" . $page . "/index.tinyw"));
echo '</div>';
echo '</div>';
echo '<script>';
echo 'var sidebar_list = document.getElementsByClassName("sidebar-list")[0];';
echo 'var sidebar_list_a = sidebar_list.getElementsByTagName("a");';
echo 'for (var i = 0; i < sidebar_list_a.length; i++) {';
echo 'if (i % 2 == 1) {';
echo 'sidebar_list_a[i].style.backgroundColor = "#0b0c0c";';
echo '}';
echo '}';
echo '// search bar, hides non-matching links';
echo 'var sidebar_search = document.getElementById("search-input");';
echo 'sidebar_search.addEventListener("keyup", function(event) {';
echo 'var search_input = event.target.value;';
echo 'var sidebar_list_a = sidebar_list.getElementsByTagName("a");';
echo 'for (var i = 0; i < sidebar_list_a.length; i++) {';
echo 'if (sidebar_list_a[i].innerHTML.toLowerCase().indexOf(search_input.toLowerCase()) == -1) {';
echo 'sidebar_list_a[i].style.display = "none";';
echo '} else {';
echo 'sidebar_list_a[i].style.display = "block";';
echo '}';
echo '}';
echo '});';
echo '</script>';
echo '</body>';
echo '</html>';

// end php
?>