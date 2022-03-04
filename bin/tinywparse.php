<?php

/* TinyW code example:
# A comment, starts with a hash, only ends until the end of the line or the end of the file.
Normal text # Normal text is then enclosed in <p></p> tags
<html>html code</html> # HTML code is simply passed through without the <html> tags.
<code="lua">
-- lua code
</code> # Code for highlight.js highlighting is enclosed in <code> tags and often multilines,
# the language is specified in the "code" attribute, for the code tag translates to:
# <pre><code class="language-lua">
# -- lua code
# </code></pre>
<img="image.png"> # "<img=" is turned into "<img src="
<link="http://example.com">link text</link> # "<link=" is turned into "<a href=" and closing tag is turned into "</a>"
<u>underline text</u> # "<u>" is turned into "<p><u>" and then "</u></p>"
<s>strikethrough text</s> # "<s>" is turned into "<p><s>" and then "</s></p>"
*bold text* # is enclosed in "<p><b>" and "</b></p>"
_italic text_ # is enclosed in "<p><i>" and "</i></p>"

*/

// Regex expressions
// The expression to process:
// 1. # get hash and all text until newline (get the newline too) or end of file: /(#.*\n|#.*)/
// 2. <code="somelanguage">multiline code</code> change <code= to <pre><code class="language-somelanguage"> and </code></pre>: /<code="([^"]*)">(.*)<\/code>/



// Generalized function to parse a tinyw file and return the HTML corresponding to the tinyw code
function tinyw_parse($tinyw_code) {
    $html_code = "";
    $lines = explode("\n", $tinyw_code);
    // DONT USE CONTINUE


}

?>