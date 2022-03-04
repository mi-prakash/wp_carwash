<div class="carwash-appointment">
    <div class="row mb-3">
        <?php
        $i = 0;
        foreach ($packages as $package) : ?>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center">
                            <h5 class="card-title text-dark"><?= $package->post_title ?></h5>
                            <p class="card-text text-secondary mb-2"><?= __('Available services', 'carwash') ?></p>
                        </div>
                        <table class="table table-bordered table-striped table-responsive table-sm table-appointment">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center"><?= __('Service Name', 'carwash') ?></th>
                                    <th class="text-center"><?= __('Price', 'carwash') ?></th>
                                    <th class="text-center"><?= __('Required Time', 'carwash') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $total_price = 0;
                                    $total_time = 0;
                                    foreach ($package->services as $service) :?>
                                    <tr>
                                        <td class="ps-1"><?= $service->post_title ?></td>
                                        <td class="text-end pe-1"><?= is_numeric($service->price) ? '$'.number_format($service->price, 2) : '$'.number_format(0, 2) ?></td>
                                        <td class="text-end pe-1"><?= $service->time.' mins' ?></td>
                                    </tr>
                                <?php 
                                    $total_price = $total_price + $service->price;
                                    $total_time = $total_time + $service->time;
                                    endforeach; 
                                ?>
                            </tbody>
                            <tfoot>
                                <th class="ps-1"><?= __('Total', 'carwash') ?></th>
                                <th class="text-end pe-1">$<?= number_format($total_price, 2) ?></th>
                                <th class="text-end pe-1"><?= $total_time ?> mins</th>
                            </tfoot>
                        </table>
                        <input type="hidden"  >
                        <button type="button" class="btn btn-dark btn-sm w-100 bg-dark text-warning" data-bs-toggle="modal" data-bs-target="#appointmentModal"><?= __('Make Appointment', 'carwash') ?></button>
                    </div>
                </div>
            </div>
        <?php
        $i++;
        if ($i % 2 == 0 && $i != count($packages)) { 
            echo "
    </div>
    <div class='row mb-3'>";
        }
        endforeach;
        ?>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="appointmentModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="appointmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark" id="appointmentModalLabel">Modal title</h5>
            </div>
            <div class="modal-body text-secondary">
                <div class="row mb-3">
                    <p class="mb-1"><b class="text-dark"><?= __('Selected Package', 'carwash') ?></b>: Package Name<span class="text-secondary pack-name"></span></p>
                    <p class="mb-1"><b class="text-dark"><?= __('Total Price', 'carwash') ?></b>: <span class="text-secondary pack-price">$00.00</span></p>
                    <p class="mb-1"><b class="text-dark"><?= __('Required Time', 'carwash') ?></b>: <span class="text-secondary pack-time">00 mins</span></p>
                </div>
                <form method="POST">
                    <input type="hidden" class="pack-id" name="package_id" value="0">
                    <label class="form-label text-secondary">Customer Name</label>
                    <input type="text" class="form-control form-control-sm bg-light text-secondary mb-2 customer_name" name="customer_name" required="">
                    <label class="form-label text-secondary">Email</label>
                    <input type="email" class="form-control form-control-sm bg-light text-secondary mb-2 email" name="email" required="">
                    <label class="form-label text-secondary">Appointment Date</label>
                    <input type="date" class="form-control form-control-sm bg-light text-secondary mb-2 apt_date" name="apt_date" min="2022-03-04" required="">
                    <label class="form-label text-secondary">Appointment Time</label>
                    <input type="time" class="form-control form-control-sm bg-light text-secondary mb-2 apt_time" name="apt_time" required="">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm bg-danger text-light" data-bs-dismiss="modal"><?= __('Cancel', 'carwash') ?></button>
                <button type="button" class="btn btn-dark btn-sm bg-dark text-warning"><?= __('Submit', 'carwash') ?></button>
            </div>
        </div>
    </div>
</div>