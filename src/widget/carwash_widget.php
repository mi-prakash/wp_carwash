<div class="carwash_dashboard">
    <div class="row">
        <div class="col-md-6">
            <div class="card text-center">
                <div class="card-body">
                    <!-- <?= admin_url() ?> -->
                    <h5 class="card-title"><?= $total_cars ?></h5>
                    <p class="card-text"><?= __('Total Cars', 'carwash') ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title"><?= $total_services ?></h5>
                    <p class="card-text"><?= __('Total Services', 'carwash') ?></p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title"><?= $total_packages ?></h5>
                    <p class="card-text"><?= __('Total Packages', 'carwash') ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title"><?= $total_appointments ?></h5>
                    <p class="card-text"><?= __('Total Appointments', 'carwash') ?></p>
                </div>
            </div>
        </div>
    </div>
</div>