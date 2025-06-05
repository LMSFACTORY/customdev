<?php // This is the template for the check email form ?>
<div id="check_email" class="col-md-8">
    <h3 class="p-3 fw-semibold">Courriel</h3>
    <div class="container-fluid ">
        <form id="checkUserForm">
            <div class="input_container">
                <input class="form-control" type="email" id="email" name="email" required>
                <div id="result"></div>
            </div>
            <div class="button_container py-2">
                <button type="submit" class="btn btn-primary">Vérifier mon courriel</button>
            </div>
        </form>

        <!-- Modal Structure -->
        <!-- Modal -->
        <div class="modal fade" id="responseModal" role="dialog" aria-hidden="true" tabindex="-1">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Un compte existe déjà</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body" id="modalBodyContent">
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                        <a id="profile_link" class="btn btn-primary">Modifier
                            ce compte</a>
                    </div>
                </div>
            </div>
        </div>


    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="./js/app.js"></script>
</div>
