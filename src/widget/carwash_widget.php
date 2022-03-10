<div class="carwash_dashboard">
    <div class="row">
        <div class="col-6">
            <div class="card text-center">
                <a href="<?= admin_url().'edit.php?post_type=car' ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= $total_cars ?></h5>
                        <p class="card-text"><?= __('Total Cars', 'carwash') ?></p>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-6">
            <div class="card text-center">
                <a href="<?= admin_url().'edit.php?post_type=service' ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= $total_services ?></h5>
                        <p class="card-text"><?= __('Total Services', 'carwash') ?></p>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <div class="card text-center">
                <a href="<?= admin_url().'edit.php?post_type=package' ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= $total_packages ?></h5>
                        <p class="card-text"><?= __('Total Packages', 'carwash') ?></p>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-6">
            <div class="card text-center">
                <a href="<?= admin_url().'edit.php?post_type=appointment' ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= $total_appointments ?></h5>
                        <p class="card-text"><?= __('Total Appointments', 'carwash') ?></p>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="row px-2 mt-2">
        <div class="mx-auto">
            <h4><?= __('Latest Appointments', 'carwash') ?></h4>
        </div>
        <div class="col-12">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th><?= __('Package', 'carwash') ?></th>
                        <th><?= __('Customer', 'carwash') ?></th>
                        <th><?= __('Email', 'carwash') ?></th>
                        <th><?= __('Apt. Datetime', 'carwash') ?></th>
                        <th><?= __('Required Time', 'carwash') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($appointments as $appointment) :?>
                        <tr>
                            <td><a href="<?= admin_url().'post.php?post='.$appointment->ID.'&action=edit' ?>"><?= $appointment->package_name ?></a></td>
                            <td><?= $appointment->customer_name ?></td>
                            <td><?= $appointment->email ?></td>
                            <td class="text-end"><?= $appointment->apt_date_time ?></td>
                            <td class="text-end"><?= $appointment->time ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>