function openModal(type, formation = null) {
    const modal = document.getElementById('formation-modal');
    const title = document.getElementById('modal-title');
    const form = document.getElementById('formation-form');
    
    modal.classList.remove('hidden');

    if(type === 'edit' && formation) {
        title.textContent = 'Edit Formation';
        document.getElementById('name').value = formation.name || '';
        document.getElementById('trainer').value = formation.trainer || '';
        document.getElementById('description').value = formation.description || '';
        document.getElementById('duration').value = formation.duration || '';
        document.getElementById('enrolled').value = formation.enrolled || '';
        document.getElementById('capacity').value = formation.capacity || '';
        form.action = `/formations/${formation.id}`;
        form.method = 'POST';

        // add PUT method hidden input
        let methodInput = form.querySelector('input[name="_method"]');
        if(!methodInput) {
            methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            form.appendChild(methodInput);
        }
        methodInput.value = 'PUT';
    } else {
        title.textContent = 'Add Formation';
        form.reset();
        form.action = '/formations';
        form.method = 'POST';
        let methodInput = form.querySelector('input[name="_method"]');
        if(methodInput) methodInput.remove();
    }
}

function closeModal() {
    document.getElementById('formation-modal').classList.add('hidden');
}
