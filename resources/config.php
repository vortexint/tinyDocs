<?php
/* This config.php has no visuals, it is used to receive data from first time config
    and configure the site accordingly using id data in the url, then redirect to
    the configured wiki and delete itself.
    example: http://localhost/wiki/resources/config.php?name=My%20Wiki&description=My%20Wiki%20Description&color=rgb(31,68,128)&theme=default&favicon=favicon.ico
    will create a wikiconf.ini file with the data and redirect to the wiki. (and secretly delete itself for obvious reasons)
    -- Default ini
    wiki_name = "Tiny-Wiki"
    wiki_description = "A comprehensive folder-structure-based wiki engine for simple documentation."
    ; styling
    wiki_color = "#1f4480" ; dark blue, the second color is never defined inside the css stylesheet.
    wiki_default_theme = "style" ; corresponds to style.css
    wiki_favicon = "tinywikilogo.png"
*/
// parse the url and get name, description, color, theme, favicon
$name = $_GET['name'];
$description = $_GET['description'];
$color = $_GET['color'];
$theme = $_GET['theme'];
$favicon = $_GET['favicon'];

// Create the file
$file = fopen("wikiconf.ini", "w") or die("Unable to open file!");
// write out the data
fwrite($file, "wiki_name = \"$name\"\n");
fwrite($file, "wiki_description = \"$description\"\n");
fwrite($file, "wiki_color = \"$color\"\n");
fwrite($file, "wiki_default_theme = \"$theme\" ; corresponds to \"$theme.css\"\n");
fwrite($file, "wiki_favicon = \"$favicon\"\n");
fclose($file);

// redirect to the wiki at parent folder
header("Location: ../");
// self-destruct
unlink(__FILE__);
?>