/*
document.addEventListener('DOMContentLoaded', function() {
    let selectAllInput = document.getElementById('selectAll');
        
    selectAllInput.addEventListener('click', function() {
        let checkboxes = document.querySelectorAll('.table-item[type="checkbox"]');
        
        checkboxes.forEach(checkbox => {
            checkbox.checked = selectAllInput.checked;
        });
    }); 
});     

*/

document.addEventListener('DOMContentLoaded', function() {
    let selectAllInput = document.getElementById('selectAll');
    let checkboxes = document.querySelectorAll('.table-item[type="checkbox"]');
        
    selectAllInput.addEventListener('click', function() {
        checkboxes.forEach(checkbox => {
            checkbox.checked = selectAllInput.checked;
        });
    }); 

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (!checkbox.checked) {
                // Si l'un des éléments est décoché, décochez la case 'selectAll'
                selectAllInput.checked = false;
            } else {
                // Vérifiez si tous les éléments sont cochés
                let allChecked = true;
                checkboxes.forEach(cb => {
                    if (!cb.checked) {
                        allChecked = false;
                    }
                });

                // Si tous les éléments sont cochés, cochez la case 'selectAll'
                if (allChecked) {
                    selectAllInput.checked = true;
                }
            }
        });
    });
});     


