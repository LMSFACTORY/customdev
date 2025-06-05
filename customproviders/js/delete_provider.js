/* jshint esversion: 6 */


$(document).ready(function () {
    // Attach click event to dynamically handle delete buttons
    $(document).on('click', '.delete-provider-btn', function (e) {
        e.preventDefault();

        // Get the email and ID of the provider from the button's data attributes
        const email = $(this).data('email');
        const providerId = $(this).data('id');

        // Confirm deletion
        if (!confirm(`Voulez-vous vraiment supprimer le fournisseur avec l'email ${email} ?`)) {
            return;
        }

        // Send AJAX request to delete the provider
        $.ajax({
            type: 'POST',
            url: 'delete_provider.php',
            data: {
                delete_provider: true,
                email: email,
                id: providerId
            },
            error: function () {
                let html = `<div class="alert alert-warning alert-dismissible fade show mt-3" role="alert">
                    Il y a une erreur.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                    </div>`;
                $('#result').html(html);
            },
            success: function (response) {
                const result = JSON.parse(response);
                const { success, message } = result;
                let html;
                if (success) {
                    html = `
                        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                            ${message}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    `;
                    $('#result').html(html);
                    $(`#deleteProviderModal_${providerId}`).modal('hide');
                    window.location.href = "/customdev/customproviders/base_provider.php";
                } else {
                    html = `<div class="alert alert-warning alert-dismissible fade show mt-3" role="alert">
                        ${message}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                        </div>`;
                    $('#result').html(html);
                }
            }
        });
    });
});
