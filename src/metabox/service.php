<div class="carwash-form service_meta">
	<label for="carwash_car_id"><?php echo $label_car ?></label>
	<select name="carwash_car_id" id="carwash_car_id">
		<?php
		if ($cars) {
			foreach ($cars as $car) {
				if ($saved_car_id == $car->ID) {
					$selected = 'selected';
				} else {
					$selected = '';
				}
		?>
				<option value="<?php echo $car->ID ?>" <?php echo $selected ?>><?php echo $car->post_title ?></option>
		<?php
			}
		}
		?>
	</select>

	<label for="carwash_price"><?php echo $label_price ?></label>
	<input type="number" name="carwash_price" id="carwash_price" step=".10" value="<?php echo $saved_price ?>" />

	<label for="carwash_time"><?php echo $label_time ?></label>
	<input type="number" name="carwash_time" id="carwash_time" value="<?php echo $saved_time ?>" />
</div>