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


// document.addEventListener('DOMContentLoaded', () => {
//     const formationSelect = document.querySelector('select[name="formation_id"]');
//     const paymentDoneInput = document.getElementById('payment_done');
//     const paymentRemainingInput = document.getElementById('payment_remaining');
//     const formationPriceInput = document.getElementById('formation_price');

//     // ila tbdal formation
//     formationSelect.addEventListener('change', (e) => {
//         const formationId = e.target.value;
//         if(!formationId) return;

//         // fetch price dial formation men server
//         fetch(`/formations/${formationId}/price`)
//             .then(res => res.json())
//             .then(data => {
//                 formationPriceInput.value = data.price; // price men server
//                 updateRemaining();
//             });
//     });

//     // ila tbdal payment_done
//     paymentDoneInput.addEventListener('input', updateRemaining);

//     function updateRemaining() {
//         const price = parseFloat(formationPriceInput.value || 0);
//         const done = parseFloat(paymentDoneInput.value || 0);
//         paymentRemainingInput.value = (price - done).toFixed(2);
//     }
// });
const formationSelect = document.getElementById('formation_id');
const paymentDone = document.getElementById('payment_done');
const paymentRemaining = document.getElementById('payment_remaining');

function updateRemaining() {
    const price = parseFloat(formationSelect.selectedOptions[0]?.dataset.price) || 0;
    const done = parseFloat(paymentDone.value) || 0;
    paymentRemaining.value = (price - done).toFixed(2);
}

formationSelect.addEventListener('change', updateRemaining);
paymentDone.addEventListener('input', updateRemaining);
