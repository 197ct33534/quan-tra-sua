import 'bootstrap';

document.addEventListener('change', async (event) => {
    const input = event.target.closest('.availability-toggle');
    if (!input) {
        return;
    }

    const label = input.parentElement.querySelector('.availability-label');
    const token = document.querySelector('meta[name="csrf-token"]').content;
    const body = {};
    body[input.dataset.field] = input.checked;

    const response = await fetch(input.dataset.url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': token,
        },
        body: JSON.stringify(body),
    });

    if (!response.ok) {
        input.checked = !input.checked;
        return;
    }

    if (label) {
        label.textContent = input.checked ? input.dataset.on : input.dataset.off;
    }
});
