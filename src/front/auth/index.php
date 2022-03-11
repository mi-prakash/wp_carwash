<div class="carwash-auth">
	<div class="text-center">
		<p><?php echo $page_info ?></p>
		<button type="button" class="btn btn-warning btn-sm bg-warning text-dark apt-btn" data-bs-toggle="modal" data-bs-target="#loginModal"><?php echo __('Log In', 'carwash') ?></button>
		<button type="button" class="btn btn-dark btn-sm bg-dark text-warning apt-btn border-warning" data-bs-toggle="modal" data-bs-target="#registerModal"><?php echo __('Register', 'carwash') ?></button>
	</div>
</div>
<!-- Log In Modal -->
<div class="modal fade" id="loginModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title text-dark" id="loginModalLabel"><?php echo __('Log In', 'carwash') ?></h5>
			</div>
			<form method="POST">
				<div class="modal-body text-dark">
					<div class="main">
						<?php wp_nonce_field('carwash_front_login', 'carwash_login_token'); ?>

						<label class="form-label text-dark"><?php echo __('Username', 'carwash') ?></label>
						<input type="text" class="form-control form-control-sm bg-light text-dark mb-2 username" name="username" required="">

						<label class="form-label text-dark"><?php echo __('Password', 'carwash') ?></label>
						<input type="password" class="form-control form-control-sm bg-light text-dark mb-2 password" name="password" required="">
					</div>
					<div class="response hidden">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger btn-sm bg-danger text-light btn-exit" data-bs-dismiss="modal"><?php echo __('Cancel', 'carwash') ?></button>
					<button type="submit" class="btn btn-dark btn-sm bg-dark text-warning btn-submit"><?php echo __('Log In', 'carwash') ?></button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Register Modal -->
<div class="modal fade" id="registerModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title text-dark" id="registerModalLabel"><?php echo __('Registration', 'carwash') ?></h5>
			</div>
			<form method="POST">
				<div class="modal-body text-dark">
					<div class="main">
						<?php wp_nonce_field('carwash_front_register', 'carwash_register_token'); ?>

						<label class="form-label text-dark"><?php echo __('Email', 'carwash') ?></label>
						<input type="email" class="form-control form-control-sm bg-light text-dark mb-2 email" name="email" required="">

						<label class="form-label text-dark"><?php echo __('Username', 'carwash') ?></label>
						<input type="text" class="form-control form-control-sm bg-light text-dark mb-2 username" name="username" required="">

						<label class="form-label text-dark"><?php echo __('Password', 'carwash') ?></label>
						<input type="password" class="form-control form-control-sm bg-light text-dark mb-2 password" name="password" required="">
					</div>
					<div class="response hidden">
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger btn-sm bg-danger text-light btn-exit" data-bs-dismiss="modal"><?php echo __('Cancel', 'carwash') ?></button>
					<button type="submit" class="btn btn-dark btn-sm bg-dark text-warning btn-submit"><?php echo __('Register', 'carwash') ?></button>
				</div>
			</form>
		</div>
	</div>
</div>