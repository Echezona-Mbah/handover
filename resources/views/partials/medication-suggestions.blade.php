<script>
  document.querySelectorAll('.medication-name-input').forEach((input, index) => {
    const list = document.createElement('datalist');
    list.id = `medication-suggestions-${index}-${Date.now()}`;
    input.setAttribute('list', list.id);
    input.setAttribute('autocomplete', 'off');
    input.insertAdjacentElement('afterend', list);

    let timer;
    let controller;
    input.addEventListener('input', () => {
      clearTimeout(timer);
      if (controller) controller.abort();
      const query = input.value.trim();
      if (query.length < 2) {
        list.replaceChildren();
        return;
      }

      timer = setTimeout(async () => {
        controller = new AbortController();
        const url = new URL(input.dataset.suggestionsUrl, window.location.origin);
        url.searchParams.set('q', query);
        try {
          const response = await fetch(url, {headers: {'Accept': 'application/json'}, signal: controller.signal});
          if (!response.ok) throw new Error('Medication suggestions could not be loaded.');
          const names = await response.json();
          list.replaceChildren(...names.map((name) => {
            const option = document.createElement('option');
            option.value = name;
            return option;
          }));
        } catch (error) {
          if (error.name !== 'AbortError') console.error(error);
        }
      }, 200);
    });
  });
</script>
