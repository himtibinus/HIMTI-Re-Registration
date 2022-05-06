<?php
    // This module shows the error/success popup from the server.
?>
@if (session('status'))
    <div class="row justify-content-center mb-8">
        <div class="col-md-8">
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        </div>
    </div>
    <?php
        Session::forget('status');
    ?>
@endif
@if (session('error'))
    <div class="row justify-content-center mb-8">
        <div class="col-md-8">
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        </div>
    </div>
    <?php
        Session::forget('error');
    ?>
@endif
@if (session('status'))
    <div class="row justify-content-center mb-8">
        <div class="col-md-8">
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        </div>
    </div>
    <?php
        Session::forget('status');
    ?>
@endif
