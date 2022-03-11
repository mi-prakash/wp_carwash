<div class="carwash-dashboard">
	<div class="row">
		<div class="col-6">
			<div class="card text-center">
				<a href="<?php echo admin_url().'edit.php?post_type=car' ?>">
					<div class="card-body">
						<h5 class="card-title"><?php echo $total_cars ?></h5>
						<p class="card-text"><?php echo __('Total Cars', 'carwash') ?></p>
					</div>
				</a>
			</div>
		</div>
		<div class="col-6">
			<div class="card text-center">
				<a href="<?php echo admin_url().'edit.php?post_type=service' ?>">
					<div class="card-body">
						<h5 class="card-title"><?php echo $total_services ?></h5>
						<p class="card-text"><?php echo __('Total Services', 'carwash') ?></p>
					</div>
				</a>
			</div>
		</div>
		<div class="col-6">
			<div class="card text-center">
				<a href="<?php echo admin_url().'edit.php?post_type=package' ?>">
					<div class="card-body">
						<h5 class="card-title"><?php echo $total_packages ?></h5>
						<p class="card-text"><?php echo __('Total Packages', 'carwash') ?></p>
					</div>
				</a>
			</div>
		</div>
		<div class="col-6">
			<div class="card text-center">
				<a href="<?php echo admin_url().'edit.php?post_type=appointment' ?>">
					<div class="card-body">
						<h5 class="card-title"><?php echo $total_appointments ?></h5>
						<p class="card-text"><?php echo __('Total Appointments', 'carwash') ?></p>
					</div>
				</a>
			</div>
		</div>
	</div>
	<div class="row px-2 mt-2">
		<div class="mx-auto">
			<h4><?php echo __('Latest Appointments', 'carwash') ?></h4>
		</div>
		<div class="col-12">
			<table class="table table-bordered table-striped table-responsive">
				<thead class="table-dark">
					<tr>
						<th><?php echo __('Package', 'carwash') ?></th>
						<th><?php echo __('Customer', 'carwash') ?></th>
						<th><?php echo __('Email', 'carwash') ?></th>
						<th><?php echo __('Apt. Datetime', 'carwash') ?></th>
						<th><?php echo __('Required Time', 'carwash') ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($appointments as $appointment) :?>
						<tr>
							<td><a href="<?php echo admin_url().'post.php?post='.$appointment->ID.'&action=edit' ?>"><?php echo $appointment->package_name ?></a></td>
							<td><?php echo $appointment->customer_name ?></td>
							<td><?php echo $appointment->email ?></td>
							<td class="text-end"><?php echo $appointment->apt_date_time ?></td>
							<td class="text-end"><?php echo $appointment->time ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>