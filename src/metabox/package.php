<div class="carwash-form package-meta">
    <?php
    if (!empty($saved_service_ids)) {
        $i = 1;
        foreach ($saved_service_ids as $service_id) {
            if ($i == 1) {
            ?>
                <label for="carwash_service_ids-1"><?= $label_service ?></label>
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
                            <option value="<?= $service->ID ?>" <?= $selected ?>><?= $service->post_title ?></option>
                    <?php
                        }
                    }
                    ?>
                </select>
                <div class="added-services">
                <?php
            } else {
            ?>
                <div class="service-container" id="container-<?= $i ?>">
                    <label for="carwash_service_ids-<?= $i ?>"><?= $label_service ?></label>
                    <select class="select-service" name="carwash_service_ids[]" id="carwash_service_ids-<?= $i ?>">
                        <?php
                        if ($services) {
                            foreach ($services as $service) {
                                if($service_id == $service->ID) {
                                    $selected = 'selected';
                                } else {
                                    $selected = '';
                                }
                        ?>
                                <option value="<?= $service->ID ?>" <?= $selected ?>><?= $service->post_title ?></option>
                        <?php
                            }
                        }
                        ?>
                    </select>
                    <div class="btn-grp">
                        <button type="button" class="button button-link-delete button-small remove" data-index="<?= $i ?>">Remove</button>
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
            <label for="carwash_service_ids-1"><?= $label_service ?></label>
            <select class="select-service" name="carwash_service_ids[]" id="carwash_service_ids-1">
                <?php
                if ($services) {
                    foreach ($services as $service) {
                ?>
                        <option value="<?= $service->ID ?>"><?= $service->post_title ?></option>
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
        <label for="carwash_service_ids-"><?= $label_service ?></label>
        <select class="select-service" name="" id="carwash_service_ids-">
            <?php
            if ($services) {
                foreach ($services as $service) {
            ?>
                    <option value="<?= $service->ID ?>"><?= $service->post_title ?></option>
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