console.log("searchController.js loaded");
const dateInputs = document.querySelectorAll('input[type="date"]');
const searchForm = document.getElementById('archiveSearchForm');



dateInputs.forEach(input => {
    input.addEventListener('change', () => {
        const dateFilled = Array.from(dateInputs).some(inp => inp.value !== "");
        dateInputs.forEach(inp => {
            inp.required = dateFilled;
            if (dateFilled) {
                console.log("Required added to:", inp.id);
            } else {
                console.log("Required removed from:", inp.id);
            }
        });
    });
});

searchForm.addEventListener('submit', (e) => {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    const todayDate = new Date();

    if (startDate && endDate) {
        const start = new Date(startDate);
        const end = new Date(endDate);
        if (end < start) {
            e.preventDefault();
            alert("The end date must be after the start date.");
            return;
        } else if (start > todayDate || end > todayDate) {
            e.preventDefault();
            alert("The dates cannot be in the future.");
            return;
        }
    }

    const fields = searchForm.querySelectorAll('input, select');
    let hasValue = false;
    fields.forEach(field => {
        if (field.type === "submit" || field.id === "csrfToken") return;
        if (field.value && field.value.trim() !== "") {
            hasValue = true;
        }
    });

    if (!hasValue) {
        e.preventDefault();
        alert("Please fill in at least one field in order to search.");
    }
});

