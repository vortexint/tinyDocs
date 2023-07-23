<?php
$hljstheme = $ini['hljstheme'];
// add hljs script and stylesheet to $additionalHead
$additionalHead = $additionalHead . '<link rel="stylesheet" href="modules/highlight/styles/' . $hljstheme . '">
<script src="modules/highlight/highlight.min.js"></script>
'
?>