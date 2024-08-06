document.addEventListener('DOMContentLoaded', function () {
    const buttons = document.querySelectorAll('.toggle-form');
    buttons.forEach(button => {
        button.addEventListener('click', function () {
            const formId = this.getAttribute('data-target');
            const form = document.getElementById(formId);
            form.classList.toggle('hidden');
            if (!form.classList.contains('hidden')) {
                form.style.maxHeight = form.scrollHeight + "px";
            } else {
                form.style.maxHeight = null;
            }
        });
    });
});
