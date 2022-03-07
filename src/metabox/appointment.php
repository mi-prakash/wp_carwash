<div class="carwash_form appointment_meta">
    <label for="post_title"><?= $label_apt_no ?></label>
    <input type="text" name="post_title" id="post_title" value="<?= $apt_no ?>" readonly />

    <label for="carwash_package_name"><?= $label_package_name ?></label>
    <input type="text" name="carwash_package_name" id="carwash_package_name" value="<?= $package_name ?>" readonly />

    <label for="carwash_customer_name"><?= $label_customer_name ?></label>
    <input type="text" name="carwash_customer_name" id="carwash_customer_name" value="<?= $customer_name ?>" readonly />

    <label for="carwash_email"><?= $label_email ?></label>
    <input type="text" name="carwash_email" id="carwash_email" value="<?= $email ?>" readonly />

    <label for="carwash_apt_datetime"><?= $label_apt_datetime ?></label>
    <input type="text" name="carwash_apt_datetime" id="carwash_apt_datetime" value="<?= $apt_date.' '.$apt_time ?>" readonly />

    <label for="carwash_price"><?= $label_price ?></label>
    <input type="text" name="carwash_price" id="carwash_price" value="<?= $price ?>" readonly />

    <label for="carwash_time"><?= $label_time ?></label>
    <input type="text" name="carwash_time" id="carwash_time" value="<?= $time ?>" readonly />

    <label for="carwash_status"><?= $label_status ?></label>
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
                <option value="<?= $key ?>" <?= $selected ?>><?= $value ?></option>
        <?php
            }
        }
        ?>
    </select>
</div>