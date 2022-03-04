<pre>
    <?php print_r($packages); ?>
</pre>
<div class="row mb-3">
    <?php
    $i = 0;
    foreach ($packages as $package) : ?>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-dark"><?= $package->post_title ?></h5>
                    <p class="card-text text-secondary">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    <button type="button" class="btn btn-dark btn-sm w-100" data-bs-toggle="modal" data-bs-target="#appointmentModal"><?= __('Appointment', 'carwash') ?></button>
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

<!-- Modal -->
<div class="modal fade" id="appointmentModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="appointmentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-dark" id="appointmentModalLabel">Modal title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-secondary">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><?= __('Cancel', 'carwash') ?></button>
        <button type="button" class="btn btn-primary btn-sm"><?= __('Submit', 'carwash') ?></button>
      </div>
    </div>
  </div>
</div>