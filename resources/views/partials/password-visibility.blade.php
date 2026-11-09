<script>
  document.querySelectorAll('input[type="password"]').forEach((input) => {
    if (input.dataset.visibilityToggle === 'true') return;
    input.dataset.visibilityToggle = 'true';

    const wrapper = document.createElement('span');
    wrapper.style.cssText = 'display:flex;flex:1 1 auto;min-width:0;position:relative;';
    input.parentNode.insertBefore(wrapper, input);
    wrapper.appendChild(input);

    input.style.paddingRight = '2.75rem';
    const toggle = document.createElement('button');
    toggle.type = 'button';
    toggle.setAttribute('aria-label', 'Show password');
    toggle.setAttribute('aria-pressed', 'false');
    toggle.style.cssText = 'position:absolute;right:.65rem;top:50%;transform:translateY(-50%);border:0;background:transparent;padding:.25rem;color:#64748b;cursor:pointer;line-height:1;';
    toggle.innerHTML = '<svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg>';
    toggle.addEventListener('click', () => {
      const visible = input.type === 'password';
      input.type = visible ? 'text' : 'password';
      toggle.setAttribute('aria-label', visible ? 'Hide password' : 'Show password');
      toggle.setAttribute('aria-pressed', String(visible));
      toggle.innerHTML = visible
        ? '<svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3l18 18"/><path d="M10.6 10.6a2 2 0 002.8 2.8"/><path d="M9.9 5.2A10.7 10.7 0 0112 5c6.4 0 10 7 10 7a14.8 14.8 0 01-3 3.8"/><path d="M6.2 6.2A15.1 15.1 0 002 12s3.6 7 10 7a10.7 10.7 0 004.1-.8"/></svg>'
        : '<svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg>';
    });
    wrapper.appendChild(toggle);
  });
</script>
