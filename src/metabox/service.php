<div class="carwash_form service_meta">
    <label for="carwash_car_id"><?= $label_car ?></label>
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
                <option value="<?= $car->ID ?>" <?= $selected ?>><?= $car->post_title ?></option>
        <?php
            }
        }
        ?>
    </select>

    <label for="carwash_price"><?= $label_price ?></label>
    <input type="number" name="carwash_price" id="carwash_price" value="<?= $saved_price ?>" />

    <label for="carwash_time"><?= $label_time ?></label>
    <input type="number" name="carwash_time" id="carwash_time" value="<?= $saved_time ?>" />
</div>