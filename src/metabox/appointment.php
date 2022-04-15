<div class="carwash-form appointment_meta">
	<label for="post_title"><?php echo $label_apt_no ?></label>
	<input type="text" name="post_title" id="post_title" value="<?php echo $apt_no ?>" readonly />

	<label for="carwash_package_name"><?php echo $label_package_name ?></label>
	<input type="text" name="carwash_package_name" id="carwash_package_name" value="<?php echo $package_name ?>" readonly />

	<label for="carwash_customer_name"><?php echo $label_customer_name ?></label>
	<input type="text" name="carwash_customer_name" id="carwash_customer_name" value="<?php echo $customer_name ?>" readonly />

	<label for="carwash_email"><?php echo $label_email ?></label>
	<input type="text" name="carwash_email" id="carwash_email" value="<?php echo $email ?>" readonly />

	<label for="carwash_apt_datetime"><?php echo $label_apt_datetime ?></label>
	<input type="text" name="carwash_apt_datetime" id="carwash_apt_datetime" value="<?php echo $apt_date.' '.$apt_time ?>" readonly />

	<label for="carwash_price"><?php echo $label_price ?></label>
	<input type="text" name="carwash_price" id="carwash_price" value="<?php echo $price ?>" readonly />

	<label for="carwash_time"><?php echo $label_time ?></label>
	<input type="text" name="carwash_time" id="carwash_time" value="<?php echo $time ?>" readonly />

	<label for="carwash_payment"><?php echo $label_payment ?></label>
	<?php 
		$class_name = '';
		if ($payment == 'pending') {
			$class_name = 'text-warning';
		} elseif ($payment == 'success') {
			$class_name = 'text-success';
		} elseif ($payment == 'canceled') {
			$class_name = 'text-danger';
		}
	?>
	<input type="text" class="<?php echo $class_name ?>" name="carwash_payment" id="carwash_payment" value="<?php echo ucfirst($payment) ?>" readonly />

	<label for="carwash_status"><?php echo $label_status ?></label>
	<select name="carwash_status" id="carwash_status">
		<?php
		if ($status_fields) {
			foreach ($status_fields as $key => $value) {
				if ($status == $key) {
					$selected = 'selected';
				} else {
					$selected = '';
				}
		?>
				<option value="<?php echo $key ?>" <?php echo $selected ?>><?php echo $value ?></option>
		<?php
			}
		}
		?>
	</select>
</div>