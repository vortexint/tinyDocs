<h1 align="center">TinyDocs</h1>
<img src="https://img.shields.io/github/license/vortexdevsoftware/tiny-wiki"> <img alt="GitHub all releases" src="https://img.shields.io/github/downloads/vortexdevsoftware/tiny-wiki/total">
<p align="center">
  <img src="favicon.png" width=64 />
</p>
<p align="center">Flat-file CMS engine with search</p>

Create documentation webpages with Markdown, syntax highlighting with [Highlight.js](https://highlightjs.org/)

## Why use TinyDocs?
Are you looking for a simple way to document anything?

*TinyDocs* was made with this goal in mind, here's why you would want to use it.
1.  🚀 **Highly Portable**: *TinyDocs* is designed to be lightweight and easy to move or deploy across different environments.
2. ✨ **Fresh and Simple**: *TinyDocs* includes only the essential features needed to get started, ensuring a clean and straightforward experience.
3. 🔒 **Safe**: With no database queries, *TinyDocs* guarantees a completely secure documentation environment.
4. 🎨 **Customizable**: You have the flexibility to edit hard-coded text in the configuration and use the provided CSS stylesheets or create your own to customize the installation according to your preferences.
5. 🧩 **Scalable**: *TinyDocs* provides a scalable solution, allowing your documentation to grow and expand as needed to accommodate changes and additions.
6. 📝 **Documented**: Everything that you would want to change is documented in the source code.

## Modules
*TinyDocs* offers a module system: small PHP packages that add or change functionality, such as the table of contents, search bar, and sub-title, they are located in the `Modules/` directory

You can write or edit modules yourself with minimal coding knowledge, if you prefer not to use certain modules, simply remove them from the config.yml file.

## Setting up
In order to configure TinyDocs you will need to upload the files to your web server, if this is a limited operation for you, you can set it up before uploading it.

1. Download it
2. Copy the contents the repository into the subdirectory of your website you want your installation to reside.
3. Configure the style by modifying the `config.yml` file.
4. Add your Markdown files to the pages/ directory, organizing them into folders if you wish to create categories.

## Dependencies
* [PHP](https://secure.php.net/) >= 5.3.0 