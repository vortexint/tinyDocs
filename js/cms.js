const toSnakeCase = str =>
  str &&
  str
    .match(/[A-Z]{2,}(?=[A-Z][a-z]+[0-9]*|\b)|[A-Z]?[a-z]+[0-9]*|[A-Z]|[0-9]+/g)
    .map(x => x.toLowerCase())
    .join('_');

const contentDiv = document.getElementsByClassName('content')[0];
const headings = contentDiv.querySelectorAll('h1, h2, h3, h4, h5, h6');
let tocHTML = '';
let currentLevel = 0;
headings.forEach(heading => {
  const level = parseInt(heading.tagName[1], 10);
  // Adjust indentation levels based on the heading hierarchy
  if (level > currentLevel) {
    tocHTML += '<ul>';
  } else if (level < currentLevel) {
    tocHTML += '</ul>'.repeat(currentLevel - level) + '</li>';
  } else {
    tocHTML += '</li>';
  }
  tocHTML += `<li><a href="#${toSnakeCase(heading.innerText)}">${heading.innerText}</a>`;
  currentLevel = level;
});
tocHTML += '</li>'.repeat(currentLevel);
const firstDiv = document.getElementById('toc-content');
firstDiv.insertAdjacentHTML('beforeend', tocHTML);

// Function to scroll to the heading anchor
function scrollToHeadingAnchor() {
  const hash = window.location.hash;
  console.log('Hash:', hash);
  if (hash) {
    const headings = contentDiv.querySelectorAll('h1, h2, h3, h4, h5, h6');
    headings.forEach(heading => {
      if (toSnakeCase(heading.innerText) === hash.substring(1)) {
        const yOffset = -50; // Adjust this value if you want to offset the scroll position
        const y = heading.getBoundingClientRect().top + window.pageYOffset + yOffset;
        console.log('Scrolling to Y:', y);
        window.scrollTo({ top: y, behavior: 'smooth' });
      }
    });
  } else {
    console.log('No hash found in the URL.');
  }
}

// Call the function on page load
scrollToHeadingAnchor();

// Add an event listener to scroll to the heading when the URL hash changes
window.addEventListener('hashchange', scrollToHeadingAnchor);