// This script is used to check if the user exists in the database.

/* jshint esversion: 6 */
$(document).ready(function () {
    $('#checkUserForm').on('submit', function (event) {
        event.preventDefault(); // Prevent the default form submission
        const email = $('#email').val();
        $.ajax({
            data: {email: email},
            error: function () {
                let html = `<div class="alert alert-warning alert-dismissible fade show mt-3" role="alert">
                    Il y a une erreur.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                    </div>`
                $('#result').html(html);
            },
            success: function (response) {
                const result = JSON.parse(response);
                const {provider, exists} = result;

                console.log(provider);
                console.log(exists);
                let html;
                if (exists) {
                    // language=HTML
                    html = `
                        <div class="provider">
                            <div class="provider__header">
                                <h3 class="provider__name"> ${provider.firstname} ${provider.lastname}</h3>
                            </div>
                            <div class="provider__body">
                                <p class="provider__email mt-2">Adresse de courriel : ${provider.email}</p>
                                <p class="provider__raison mt-2">Raison sociale: ${provider.raison || ''} </p>
                                <p class="provider__account mt-2">Compte fournisseur : ${provider.is_provider || 'Non'} </p>
                                <p class="provider__account_type mt-2">
                                    Double casquette : <span
                                        class="font-weight-bold">${provider.account_type || ''}</span>
                                </p>
                            </div>
                        </div>
                    `;
                    $('#modalBodyContent').html(html);
                    // Update the anchor tag href in the modal footer
                    $('#profile_link').attr('href', 'update_provider.php?email=' + encodeURIComponent(provider.email));
                    $('#responseModal').modal('show');
                } else {
                    window.location.href = 'information_form.php?email=' + encodeURIComponent(email);
                }
            },
            type: 'POST',
            url: 'lib.php'
        });
    });
});
