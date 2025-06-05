// This script is used to check if the user exists in the database.
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
                let html;
                if (result.exists) {
                    // language=HTML
                    html = `<div class="alert alert-warning alert-dismissible fade show mt-3" role="alert">
                        Vous disposez déjà d’un compte. <a href="/login/index.php">Connectez-vous</a> ou <a href="/login/forgot_password.php">réinitialisez votre mot de passe. </a><button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                    </div>`
                    $('#result').html(html);

                } else {
                    window.location.href = 'choice_page.php?email=' + encodeURIComponent(email);
                }
            },
            type: 'POST',
            url: 'check_user.php'
        });
    });
});
