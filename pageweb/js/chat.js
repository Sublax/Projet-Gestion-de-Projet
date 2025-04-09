const res = await fetch('projet-gestion-de-projet-production-f29a.up.railway.app', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ message })
  });