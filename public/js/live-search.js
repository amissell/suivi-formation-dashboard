document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('live-search');
    const resultsDiv = document.getElementById('search-results');
    let timeout = null;

    if (!searchInput || !resultsDiv) return;

    searchInput.addEventListener('input', function () {
        clearTimeout(timeout);

        const query = this.value.trim();

        if (query.length < 2) {
            resultsDiv.classList.add('hidden');
            resultsDiv.innerHTML = '';
            return;
        }

        timeout = setTimeout(() => {
            fetch(`/students/search?q=${encodeURIComponent(query)}`, {
                headers: {
                    'Accept': 'application/json',
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Search request failed');
                    }
                    return response.json();
                })
                .then(data => {
                    if (!Array.isArray(data) || data.length === 0) {
                        resultsDiv.innerHTML =
                            `<p class="p-3 text-sm text-muted-foreground">Aucun résultat</p>`;
                    } else {
                        resultsDiv.innerHTML = data.map(student => `
                            <a href="/students?search=${encodeURIComponent(student.name)}"
                               class="block p-3 hover:bg-muted border-b border-border last:border-0">
                                <p class="font-medium text-foreground">
                                    ${student.name}
                                </p>
                                <p class="text-sm text-muted-foreground">
                                    ${student.formation_name ?? '-'} • ${student.city ?? '-'}
                                </p>
                            </a>
                        `).join('');
                    }

                    resultsDiv.classList.remove('hidden');
                })
                .catch(() => {
                    resultsDiv.innerHTML =
                        `<p class="p-3 text-sm text-red-500">Erreur de recherche</p>`;
                    resultsDiv.classList.remove('hidden');
                });
        }, 300);
    });

    // Hide results when clicking outside
    document.addEventListener('click', (e) => {
        if (!searchInput.contains(e.target) && !resultsDiv.contains(e.target)) {
            resultsDiv.classList.add('hidden');
        }
    });
});
