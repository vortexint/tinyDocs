<h1 align="center">TinyWiki</h1>
<p align="center">
  <img src="resources/tinywikilogo.png" width=156 />
</p>

Mad simple PHP folder structure based wiki engine for auto-generating code documentation
webpages with support for markdown using [Parsedown](http://parsedown.org/) or
alternatively it's own markup WIP language, also featuring [Highlight.js](https://highlightjs.org/) for syntax highlighting.

# using .tinyw file
tinyw is a work-in-progress simple markup language, it aims to provide a simple standardized way to
write code documentation.

## Syntax:
```
# comment

<code="lua">
-- lua code
</code>

<code="cpp">
// cpp code
</code>

*bold text*

_italic text_

_*bold and italic*_

<u>underline text</u>

<s>strikethrough text</s>

<img="image.png">

<link="http://example.com">link text</link>

<html><button>Custom HTML Button</button></html>
```

# Running Dependencies
* [PHP](https://secure.php.net/) >= 5.6.0

# Setting up
Simply drag the source files to any directory of your website, it's not recommended to mix these
files with other files.

## Permissions:
By default you don't really need to modify the permissions on your server, but if you do want that,
the only ones you should hide are the `/docs/` & `/resources/page.html` directory.
