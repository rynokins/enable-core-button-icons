self.addEventListener('message', (e) => {
  let dataURL = e.data.url;
  fetch( dataURL, {
    mode: 'no-cors',
  })
  .then(t => t.text())
  .then((fetchData) => {
    console.log('fetch success');
    self.postMessage({
      fetchData: fetchData
    });
  })
  .catch(e => {
    console.log('worker error', e);
    self.postMessage({type: 'error', error: e.reason});
  });
});