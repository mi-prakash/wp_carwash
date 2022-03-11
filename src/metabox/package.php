<div class="carwash-form package-meta">
	<?php
	if (!empty($saved_service_ids)) {
		$i = 1;
		foreach ($saved_service_ids as $service_id) {
			if ($i == 1) {
			?>
				<label for="carwash_service_ids-1"><?php echo $label_service ?></label>
				<select class="select-service" name="carwash_service_ids[]" id="carwash_service_ids-1">
					<?php
					if ($services) {
						foreach ($services as $service) {
							if($service_id == $service->ID) {
								$selected = 'selected';
							} else {
								$selected = '';
							}
					?>
							<option value="<?php echo $service->ID ?>" <?php echo $selected ?>><?php echo $service->post_title ?></option>
					<?php
						}
					}
					?>
				</select>
				<div class="added-services">
				<?php
			} else {
			?>
				<div class="service-container" id="container-<?php echo $i ?>">
					<label for="carwash_service_ids-<?php echo $i ?>"><?php echo $label_service ?></label>
					<select class="select-service" name="carwash_service_ids[]" id="carwash_service_ids-<?php echo $i ?>">
						<?php
						if ($services) {
							foreach ($services as $service) {
								if($service_id == $service->ID) {
									$selected = 'selected';
								} else {
									$selected = '';
								}
						?>
								<option value="<?php echo $service->ID ?>" <?php echo $selected ?>><?php echo $service->post_title ?></option>
						<?php
							}
						}
						?>
					</select>
					<div class="btn-grp">
						<button type="button" class="button button-link-delete button-small remove" data-index="<?php echo $i ?>">Remove</button>
					</div>
				</div>
			<?php
			}
			$i++;
		}
		?>
				</div>
		<?php
	} else {
		?>
			<label for="carwash_service_ids-1"><?php echo $label_service ?></label>
			<select class="select-service" name="carwash_service_ids[]" id="carwash_service_ids-1">
				<?php
				if ($services) {
					foreach ($services as $service) {
				?>
						<option value="<?php echo $service->ID ?>"><?php echo $service->post_title ?></option>
				<?php
					}
				}
				?>
			</select>
			<div class="added-services">

			</div>
		<?php
	}
	?>
	<button type="button" class="button button-primary button-small add-more">Add More</button>
</div>
<div class="clone-service hidden">
	<div class="service-container">
		<label for="carwash_service_ids-"><?php echo $label_service ?></label>
		<select class="select-service" name="" id="carwash_service_ids-">
			<?php
			if ($services) {
				foreach ($services as $service) {
			?>
					<option value="<?php echo $service->ID ?>"><?php echo $service->post_title ?></option>
			<?php
				}
			}
			?>
		</select>
		<div class="btn-grp">
			<button type="button" class="button button-link-delete button-small remove" data-index="">Remove</button>
		</div>
	</div>
</div>