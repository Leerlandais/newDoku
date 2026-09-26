console.log("detailPageHandler.js loaded");
function toggleClue(id, btn) {
    const el = document.getElementById(id);
    if (!el) return;
    const isHidden = el.classList.contains('hidden');
    if (isHidden) {
        el.classList.remove('hidden');
        btn.textContent = 'Hide';
        btn.setAttribute('aria-expanded', 'true');
        btn.classList.add('bg-primary', 'text-on-primary');
        btn.classList.remove('bg-surface-container', 'text-on-surface');
    } else {
        el.classList.add('hidden');
        btn.textContent = 'Reveal';
        btn.setAttribute('aria-expanded', 'false');
        btn.classList.remove('bg-primary', 'text-on-primary');
        btn.classList.add('bg-surface-container', 'text-on-surface');
    }
}

function revealAllClues() {
    const clues = ['clue-1', 'clue-2', 'clue-3', 'clue-4'];
    clues.forEach(id => {
        const el = document.getElementById(id);
        if (el && el.classList.contains('hidden')) {
            const btn = el.previousElementSibling.querySelector('button');
            if (btn) toggleClue(id, btn);
        }
    });
}

function toggleExportNotification() {
    const alertBox = document.getElementById('exportAlert');
    if (alertBox) {
        alertBox.classList.remove('hidden');
        setTimeout(() => {
            alertBox.classList.add('hidden');
        }, 3500);
    }
}