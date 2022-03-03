<?php
// Tiny-wiki is a PHP-based code wiki engine system for simple documentation.
// using highlight.js, and a custom markup language for each page (.tny)
// unlike other wiki engines, this one provides no login system, simply displays
// the files at root/docs/ in a formatted style
//
// Tiny Markup Syntax:
// <code lang="lua">
// -- lua code
// </code>
// <code lang="cpp">
// // cpp code
// </code>
// <b>bold text</b>
// <i>italic text</i>
// <u>underline text</u>
// <s>strikethrough text</s>
// <img src="image.png">
// <link href="http://example.com">link text</link>
// # comment

// The left sidebar is a list of folders with an index.tny file at the root/docs/ folder.

// Variables
$wiki_name = "Tiny-wiki";
$wiki_description = "A PHP-based code wiki engine system for simple documentation.";
$wiki_author = "Vortex-dev";

// Don't change anything below unless you know what you're doing.

// page url example:
// docs/hello/index.tny would be displayed as: http://example.com/wiki?p=hello
// docs/hello/world/index.tny would be displayed as: http://example.com/wiki?p=hello/world
// docs/index.tny would be displayed as: http://example.com/wiki

$page = $_SERVER['REQUEST_URI'];
$page = substr($page, strpos($page, "?p=") + 3);


// set up html page

echo "<!DOCTYPE html>\n";
echo "<html>\n";
echo "<head>\n";
echo "<title>$wiki_name</title>\n";
echo "<meta charset=\"utf-8\">\n";
echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\n";
echo "<link rel=\"stylesheet\" href=\"style.css\">\n";
echo "</head>\n";
echo "<body>\n";
// sidebar
echo "<div class=\"sidebar\">\n";

echo "</body>\n";
echo "</html>\n";

// end php
?>