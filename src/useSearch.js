import ICONS from './assets';
import Fuse from 'fuse.js'
import React from 'react'

const fuse = new Fuse(Object.values(ICONS), {
  threshold: 0.2,
  keys: ['name', 'tags'],
})

function useSearch(query) {
  const [results, setResults] = React.useState(Object.values(ICONS))

  React.useEffect(() => {
    if (query.trim()) {
      setResults((fuse.search(query.trim()).map(r => r.item)))
    } else {
      setResults(Object.values(ICONS))
    }
  }, [query])

  return results
}

export default useSearch