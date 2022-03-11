<div class="carwash-appointment">
	<p class="text-center"><?= $page_info ?></p>
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
						<div class="card-content">
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
							<button type="button" class="btn btn-dark btn-sm w-100 bg-dark text-warning apt-btn" data-bs-toggle="modal" data-bs-target="#appointmentModal" data-id="<?= $package->ID ?>" data-pack-name="<?= $package->post_title; ?>" data-price="<?= number_format($total_price, 2) ?>" data-time="<?= $total_time ?>"><?= __('Make Appointment', 'carwash') ?></button>
						</div>
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
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title text-dark" id="appointmentModalLabel">Modal title</h5>
			</div>
			<form method="POST">
				<div class="modal-body text-dark">
					<div class="main">
						<div class="row mb-3">
							<p class="mb-1"><b class="text-dark"><?= __('Selected Package', 'carwash') ?></b>: <span class="text-dark pack-name"></span></p>
							<p class="mb-1"><b class="text-dark"><?= __('Total Price', 'carwash') ?></b>: $<span class="text-dark pack-price">00.00</span></p>
							<p class="mb-1"><b class="text-dark"><?= __('Required Time', 'carwash') ?></b>: <span class="text-dark pack-time">00</span> mins</p>
						</div>
						<?php wp_nonce_field('carwash_front_appointment', 'carwash_appointment_token'); ?>
						<input type="hidden" class="pack-id" name="package_id" value="0">
						<label class="form-label text-dark"><?= __('Customer Name', 'carwash') ?></label>
						<input type="text" class="form-control form-control-sm bg-light text-dark mb-2 customer_name" name="customer_name" required="">
						<label class="form-label text-dark"><?= __('Email', 'carwash') ?></label>
						<input type="email" class="form-control form-control-sm bg-light text-dark mb-2 email" name="email" required="">
						<label class="form-label text-dark"><?= __('Appointment Date', 'carwash') ?></label>
						<input type="date" class="form-control form-control-sm bg-light text-dark mb-2 apt_date" name="apt_date" min="<?= date('Y-m-d'); ?>" required="">
						<label class="form-label text-dark"><?= __('Appointment Time', 'carwash') ?></label>
						<input type="time" class="form-control form-control-sm bg-light text-dark mb-2 apt_time" name="apt_time" required="">
						<input type="hidden" class="r_price" name="price" value="">
						<input type="hidden" class="r_time" name="time" value="">
					</div>
					<div class="response hidden">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger btn-sm bg-danger text-light btn-exit" data-bs-dismiss="modal"><?= __('Cancel', 'carwash') ?></button>
					<button type="submit" class="btn btn-dark btn-sm bg-dark text-warning btn-submit"><?= __('Submit', 'carwash') ?></button>
				</div>
			</form>
		</div>
	</div>
</div>