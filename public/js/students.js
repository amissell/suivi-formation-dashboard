function openModal(mode, student = null) {
    const modal = document.getElementById('student-modal');
    if (!modal) return;

    // Reset form
    const form = modal.querySelector('form');
    form.reset();

    // Remove old _method input if any
    const oldMethod = form.querySelector('input[name="_method"]');
    if (oldMethod) oldMethod.remove();

    if (mode === 'edit' && student) {
        modal.querySelector('.modal-title').textContent = 'Edit Student';
        form.action = `/students/${student.id}`;
        form.method = 'POST';

        let methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PUT';
        form.appendChild(methodInput);

        // Fill all fields
        form.querySelector('input[name="name"]').value = student.name || '';
        form.querySelector('input[name="cin"]').value = student.cin || '';
        form.querySelector('input[name="phone"]').value = student.phone || '';
        form.querySelector('input[name="email"]').value = student.email || '';
        form.querySelector('select[name="formation_id"]').value = student.formation_id || '';
        form.querySelector('select[name="status"]').value = student.status || '';
        form.querySelector('input[name="start_date"]').value = student.start_date || '';
    } else {
        modal.querySelector('.modal-title').textContent = 'Add Student';
        form.action = '/students';
        form.method = 'POST';
    }

    modal.classList.remove('hidden');
}

function closeModal() {
    const modal = document.getElementById('student-modal');
    if (modal) modal.classList.add('hidden');
}

window.openModal = openModal;
window.closeModal = closeModal;
