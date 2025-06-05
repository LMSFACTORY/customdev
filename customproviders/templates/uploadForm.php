<?php

$title = $data['title'];
?>

<div class="container">
    <h1 class="mt-3">
		<?= $title ?>
    </h1>
    <small class="font-weight-bold"> <?php echo $email ?></small>

    <div class=" container bg-light p-3">
        <div id="temp__content">
            <h4>Documents à fournir</h4>
            <p>Le compte fournisseur est en cours de création. Dès qu’il sera prêt, vous pourrez charger les pièces
                administratives ci-dessous. Cela peut prendre quelques minutes. Si au bout de 3 min le chargement n’est
                pas prêt, essayez de rafraichir la page.<br>
                En attendant nous vous invitons à préparer les pièces suivantes :
            </p>
            <ul>
                <li>Charte des prestataires</li>
                <li>CV</li>
                <li>Fiche de recrutement (salaire ou honoraire)</li>
                <li>RIB</li>
                <li>Autorisation employeur</li>
                <li>Règlement formateur / certificat</li>
            </ul>
            <div class="loader" id="links__loader">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>

        <div id="links__content"></div>


        <div class="modal fade" id="deposeModal" role="dialog" aria-hidden="true" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"></h4>
                        <button type="button" id="close_modal" class="close" data-dismiss="modal" aria-label="Close">
                            &times;
                        </button>
                    </div>
                    <div class="modal-body">
                        <iframe id="modalIframe" src="" style="border:none;width:100%;"></iframe>
                    </div>
                </div>
            </div>
        </div>
        <a href="/customdev/customproviders/base_provider.php" class="btn btn-primary">Consulter la base
            fournisseur</a>
    </div>
</div>

<script>
    function prepareModal(e, url) {

        // Cancel the default event
        e.preventDefault();

        const modal = $('#deposeModal');
        const modalIframe = document.getElementById('modalIframe');
        const closeButton = $('#close_modal');

        modalIframe.style.width = '100%';
        modalIframe.style.height = (window.innerHeight * 0.5) + 'px';
        modalIframe.src = url;

        modal.modal('show');

        closeButton.on('click', function (e) {
            e.stopPropagation();
            e.preventDefault();
            modal.modal('hide');
            modalIframe.src = 'about:blank';
        });

        modal.off('hide.bs.modal').on('hide.bs.modal', function(e) {
            e.stopPropagation();
            e.stopPropagation();
            modalIframe.src = 'about:blank';
        });

        modal.off('shown.bs.modal').on('shown.bs.modal', function () {
            modalIframe.onload = function () {
                try {
                    const iframeDoc = modalIframe.contentDocument || modalIframe.contentWindow.document;

                    const style = iframeDoc.createElement('style');
                    style.textContent = `
                    #page-footer, #page-header, nav.navbar, .secondary-navigation, header.redirect-inpi {
                        display: none;
                    }
                    #page { margin-top: 0 !important; }
                `;
                    iframeDoc.head.appendChild(style);

                    const cancelButton = iframeDoc.getElementById('id_cancel');
                    if (cancelButton) {
                        cancelButton.addEventListener('click', function () {
                            modal.modal('hide');
                            window.location.reload();  // explicitly refresh page on cancel click
                        });
                    }

                } catch (error) {
                    console.error("Unable to modify iframe CSS:", error);
                }
            };
        });
    }
</script>
