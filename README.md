<h1 align="center">TinyDocs</h1>
<img src="https://img.shields.io/github/license/vortexdevsoftware/tiny-wiki"> <img alt="GitHub all releases" src="https://img.shields.io/github/downloads/vortexdevsoftware/tiny-wiki/total">
<p align="center">
  <img src="favicon.png" width=64 />
</p>
<p align="center">Flat-file CMS engine with search</p>
<sub align="center">And maybe editing in the future</sub>

Create documentation webpages with Markdown, syntax highlighting with [Highlight.js](https://highlightjs.org/)

## Why use TinyDocs?
Are you looking for a simple way to document anything?

*TinyDocs* was made with this goal in mind, here's why you would want to use it.
1. ✨ **Extremely Simple**: *TinyDocs* includes only the bare minimum needed to get started, a clean and straightforward experience.
2. 🔒 **Safe**: *TinyDocs* guarantees a completely secure documentation environment, if you want to be able to edit the documentation, you can use the 
3. 🛠️ **Hackable**: You can easily customize your installation by creating modules, CSS stylesheets.
4. 🧩 **Scalable**: *TinyDocs* provides a scalable solution, allowing your documentation to grow and expand as needed to accommodate changes and additions.
5. 📝 **Documented**: Everything that you would want to change is documented in the source code.

## Modules
*TinyDocs* offers a module system: small PHP packages that add or change functionality, such as the table of contents, search bar, and sub-title, they are located in the `Modules/` directory

You can write or edit modules yourself with minimal coding knowledge, if you prefer not to use certain modules, simply remove them from the config.ini file.

### userctrl
the *User Control* module overhauls the functionality of TinyDocs, it allows SQL database integration and adds a login and registration page.
<small>Note: Releases don't ship with userctrl enabled.</small>

if you want easy user editing, page creation, add "userctrl" to your modules in config.ini, and set up the configuration under "userctrl"

## Setting up
In order to configure TinyDocs you will need to upload the files to your web server, if this is a limited operation for you, you can set it up before uploading it.

1. Clone or Download the repository
2. Copy the contents into the subdirectory of your website you want your installation to reside.
3. **IMPORTANT: Make sure the the permission level of config.ini is set to 700**
4. Configure the style by modifying the `config.ini` file.
5. Add your Markdown files to the pages/ directory, organizing them into folders if you wish to create categories.

```diff
- ! NOTICE ! -
```
Make sure that if you are using the adminstration 

## Dependencies
* [PHP](https://secure.php.net/) >= 5.3.0 