/*
document.addEventListener('DOMContentLoaded', function() {
    let switchButton = document.getElementById('flexSwitchCheckChecked');

    switchButton.addEventListener('change', function() {
        let postId = this.value;
        let newStatus = this.checked ? 1 : 0;

        // Envoie une requête AJAX pour changer le statut
        fetch('../../index.php', {  
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=publish_post&post_id=${postId}&new_status=${newStatus}`,
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Statut mis à jour avec succès');
            } else {
                console.error('Erreur lors de la mise à jour du statut');
            }
        });
    });
});

*/

document.addEventListener('DOMContentLoaded', function() {
    // Gestionnaire pour le bouton "Sélectionner tout"
    document.getElementById('selectAll').addEventListener('click', function() {
        let checkboxes = document.querySelectorAll('.table-item');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    // Si un élément est désélectionné, décochez "Sélectionner tout"
    let tableItems = document.querySelectorAll('.table-item');
    tableItems.forEach(item => {
        item.addEventListener('click', function() {
            if (!this.checked) {
                document.getElementById('selectAll').checked = false;
            }
        });
    });

    // Gestionnaire pour les boutons switch
    let switches = document.querySelectorAll('input[role="switch"]');
    switches.forEach(switchButton => {
        switchButton.addEventListener('change', function() {
            const postId = this.value;
            const newStatus = this.checked ? 1 : 0;

            // Envoie de la requête AJAX pour mettre à jour le statut
            let xhr = new XMLHttpRequest();
            xhr.open('POST', 'index.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (this.status === 200) {
                    const response = JSON.parse(this.responseText);
                    if (!response.success) {
                        // Si la mise à jour a échoué, revenez à l'état précédent du switch
                        switchButton.checked = !switchButton.checked;
                        alert('Une erreur est survenue lors de la mise à jour du statut.');
                    }
                } else {
                    alert('Une erreur est survenue lors de la mise à jour du statut.');
                }
            };
            xhr.send(`action=publish_post&post_id=${postId}&new_status=${newStatus}`);
        });
    });
});





