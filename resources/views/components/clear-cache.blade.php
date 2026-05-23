<!-- Clear Firebase cache script -->
<script>
// Clear Firebase app cache
if ('caches' in window) {
  caches.keys().then(names => {
    names.forEach(name => caches.delete(name));
  });
}

// Clear localStorage Firebase entries
Object.keys(localStorage).forEach(key => {
  if (key.includes('firebase') || key.includes('__firebase')) {
    localStorage.removeItem(key);
  }
});

console.log('Cache cleared. Please reload the page.');
</script>
