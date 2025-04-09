const res = await fetch('https://projet-gestion-de-projet-production-f29a.up.railway.app/api/chat', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({ message })
});
s