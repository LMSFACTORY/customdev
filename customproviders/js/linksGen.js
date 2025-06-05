
/* jshint esversion: 8 */

$(document).ready(function () {
    const urlParams = new URLSearchParams(window.location.search);
    const email = urlParams.get('email');

    // If the URL has no `email` parameter, handle that gracefully
    if (!email) {
        console.error("No email parameter found in the URL.");
        return;
    }

    $('#deposeModal').on('hide.bs.modal', function(e) {
        // Remove the iframe's src (empty string is used here instead of 'about:blank')
        document.getElementById('modalIframe').src = '';
    });

    const fetchData = async () => {
        await new Promise(resolve => setTimeout(resolve, 3000));

        try {
            // Using jQuery's $.ajax which returns a promise
            const result = await $.ajax({
                url: 'getlinks.php',
                type: 'GET',
                dataType: 'json', // ensures the response is parsed as JSON
                data: { email },
                timeout: 180000 // 3 minutes
            });
            const { provider, exists } = result;
            if (exists && provider && provider.id) {
                const baseLink = 'https://academie.inpi.fr/';
                const html = `
          <div class="provider">
            <div class="provider__header">
              <h3 class="provider__name">Joindre les pièces administratives</h3>
            </div>
            <p>
                Le compte fournisseur a été crée et peut dès à présent déposer ses pièces administratives.
              Vous pouvez envoyer un courriel au fournisseur pour l’inviter à ajouter ses pièces administratives lui-même, ou les déposer vous-même.
            </p>
            <div class="provider__body">
              <ul class="provider__links">
                <li>
                  <a href="#" 
                     onclick="prepareModal(event, 'https://academie.inpi.fr/mod/assign/view.php?id=7242&userid=${provider.id}&action=editsubmission'); return false;">
                        Chartes des prestataires
                  </a>
                </li>
                <li>
                  <a href="#" onclick="prepareModal(event,'${baseLink}mod/assign/view.php?id=7243&userid=${provider.id}&action=editsubmission'); return false;">CV</a>
                </li>
                <li>
                  <a href="#" onclick="prepareModal(event,'${baseLink}mod/assign/view.php?id=7244&userid=${provider.id}&action=editsubmission'); return false;">Fiche recrutement salaire ou honoraire</a>
                </li>
                <li>
                  <a href="#" onclick="prepareModal(event,'${baseLink}mod/assign/view.php?id=7245&userid=${provider.id}&action=editsubmission'); return false;">RIB</a>
                </li>
                <li>
                  <a href="#" onclick="prepareModal(event,'${baseLink}mod/assign/view.php?id=7246&userid=${provider.id}&action=editsubmission'); return false;">Autorisation employeur</a>
                </li>
                <li>
                  <a href="#" onclick="prepareModal(event, '${baseLink}mod/assign/view.php?id=7247&userid=${provider.id}&action=editsubmission'); return false;">Règlement formateur certificat</a>
                </li>
              </ul>
            </div>
          </div>
        `;
                $('#links__content').html(html);
                $('#temp__content').hide();
            }

        } catch (error) {
            console.error('AJAX Error:', error);
            const errorHtml = `
        <div class="alert alert-warning alert-dismissible fade show mt-3" role="alert">
          Il y a une erreur: ${error.statusText || error}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      `;
            $('#result').html(errorHtml);
        }
    };

    fetchData();
});
