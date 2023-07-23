# Main Page
Welcome to your first **TinyDocs** page!

Thank you for choosing TinyDocs, a powerful documentation platform that makes creating and customizing your documentation a breeze!

# Configuration
To configure TinyDocs to your specific preferences, you can modify the [configuration file](./config.yml) which utilizes [YAML](https://yaml.org/), a human-friendly data serialization language.

## Directory structure

### Special File: index.md
One important feature of TinyDocs is the index.md file. This file serves as the default resolution whenever you access any subdirectory without specifying a particular page such as the main page itself.

### Categories
If your category lacks an index file, the engine will automatically resolve to a custom page displaying a list of pages within that category.

The `index.md` file is masked by the name of it's parent category

## Markdown
*Markdown* is a lightweight markup language that is easy to read and write. It is widely used for formatting text on the web and is supported by many platforms and documentation tools, including TinyDocs.

With *Markdown*, you can easily create headings, lists, links, code blocks, and more, making it an excellent choice for documenting your projects and sharing information in a clear and organized manner.

In *TinyDocs* documentation, you use *Markdown* to structure your pages, add code snippets, create lists, and include links to external resources. Whether you are a beginner or an experienced user, *Markdown* provides a simple and intuitive way to format your content and make it visually appealing.

If you are not familiar with *Markdown*, don't worry! It's easy to learn, and there are plenty of online resources available to help you get started. Feel free to experiment and use this platform to showcase your ideas and knowledge effectively.

For a quick reference, here are some common Markdown syntax examples:
### Headings
```md
# Heading 1
## Heading 2
### Heading 3
#### Heading 4
```
### Lists
Unordered List:
```md
- Item 1
- Item 2
- Item 3
```
Ordered List:
```md
1. First Item
2. Second Item
3. Third Item
```
### Code Blocks
Looking to document code? Markdown has you covered.

Wrap code in triple backticks ``` or wrap in single \` backticks for `inline code`

Examples:
### Python Example (```py)
```py
import something

def foo(x):
  print(x) 
```
### C Example (```c)
```c
#include <stdio.h>

int main(void) {
  printf("Hello World");
  return 0;
}
```
And much more...

<small>Note: This page is just an example provided to showcase how your documentation can be structured. You can delete this page when you start creating your own documentation.</small>