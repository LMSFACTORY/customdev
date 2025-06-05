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
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="./js/app.js"></script>
</div>
