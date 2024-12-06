// Get module's URL location
let { pathname, hostname, protocol } = new URL(import.meta.url);
const baseURL = protocol + '//' + hostname + pathname.substring(0,pathname.lastIndexOf('/'));

// Construct the URL to the svg sprite
const coreButtonIconsSprite = baseURL + '/node_modules/feather-icons/dist/feather-sprite.svg';

// Fetch JSON worker
const fetchWorker = new Worker(baseURL + '/fetchWorker.js', { type: 'module' });

// -----------------------
// Respond to worker messages
// -----------------------
fetchWorker.onmessage = (e) => {

  // If there is an error, display it
  if (e.data.type === 'error') {
    console.error('Service worker error:', e.data.error);
    return;
  }

  // Add the svg to the dom
  const div = document.createElement('div');
  div.innerHTML = e.data.fetchData;
  document.body.appendChild(div);

  // Die
  fetchWorker.terminate();
}

// -----------------------
// Worker error listeners
// -----------------------
fetchWorker.addEventListener('error', (e) => {
  console.error('Service worker error:',
    JSON.stringify(e, ['message', 'arguments', 'type', 'name']));
  e.preventDefault();
  return;
});

// -----------------------
// Init worker
// -----------------------
fetchWorker.postMessage({ url: coreButtonIconsSprite});