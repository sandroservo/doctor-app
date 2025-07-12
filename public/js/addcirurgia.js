document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('addModal');
    const selectField = document.getElementById('surgery_id'); // Campo select da p�gina principal
    const closeModalButton = document.getElementById('closeModal');
    const addSurgeryForm = document.getElementById('addSurgeryForm');
    const mainForm = document.getElementById('mainForm'); // Formul�rio principal

    // Verifique se o campo selectField existe
    if (selectField) {
        // Quando o select muda de valor
        selectField.addEventListener('change', function () {
            if (this.value === 'add-option') {
                if (modal) {
                    modal.classList.add('active'); // Exibe o modal
                    modal.style.display = 'flex'; // Exibe o modal
                }
            }
        });
    } else {
        console.error('O elemento "surgery_id" n�o foi encontrado.');
    }

    // Verifique se o bot�o de fechar modal existe
    if (closeModalButton && modal) {
        // Fechar o modal ao clicar no bot�o "Cancelar"
        closeModalButton.addEventListener('click', function () {
            modal.classList.remove('active');
            modal.style.display = 'none'; // Esconde o modal
            if (selectField) {
                selectField.value = ""; // Reseta o select para "Selecione"
            }
        });
    } else {
        console.error('O bot�o "closeModal" ou o modal n�o foi encontrado.');
    }

    // Verifique se o formul�rio "addSurgeryForm" existe
    if (addSurgeryForm) {
        // Submiss�o do formul�rio para adicionar nova cirurgia
        addSurgeryForm.addEventListener('submit', function (event) {
            event.preventDefault(); // Previne a valida��o e submiss�o do formul�rio principal

            const newSurgery = document.getElementById('new_surgery');
            const newSurgeryType = document.getElementById('new_surgery_type');

            if (newSurgery && newSurgeryType) {
                // Enviar os dados via AJAX para o backend
                fetch('/add-surgery', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        descricao: newSurgery.value,
                        tipo: newSurgeryType.value
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        // Adicionar a nova cirurgia ao campo select com o ID retornado do backend
                        if (selectField) {
                            const newOption = document.createElement('option');
                            newOption.text = data.descricao;
                            newOption.value = data.id; // Usando o ID retornado do backend
                            selectField.add(newOption);
                            selectField.value = newOption.value; // Seleciona a nova cirurgia
                        }

                        // Fecha o modal e reseta o formul�rio do modal
                        addSurgeryForm.reset();
                        modal.style.display = 'none'; // Esconde o modal
                    })
                    .catch(error => {
                        console.error('Erro ao adicionar a cirurgia:', error);
                    });
            } else {
                console.error('Campos "new_surgery" ou "new_surgery_type" n�o encontrados.');
            }
        });
    } else {
        console.error('O formul�rio "addSurgeryForm" n�o foi encontrado.');
    }

    // Verifique se o formul�rio principal "mainForm" existe
    if (mainForm) {
        // Submiss�o do formul�rio principal
        mainForm.addEventListener('submit', function (event) {
            // Aqui vai o c�digo de submiss�o do formul�rio principal se necess�rio
            console.log('Submetendo formul�rio principal');
        });
    } else {
        console.error('O formul�rio "mainForm" n�o foi encontrado.');
    }
});
