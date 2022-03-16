<?php
require_once(plugin_dir_path(__DIR__) . '../../../../plugins/carwash/vendor/autoload.php');

use Stripe\StripeClient;

session_start();

if (isset($_SESSION['stripe_session_id'])) {
	$stripe_session_id = $_SESSION['stripe_session_id'];

	$stripe = new StripeClient(STRIPE_API_KEY);
	$session_obj = $stripe->checkout->sessions->retrieve(
		$stripe_session_id,
		[]
	);

	// Update the appointment payment status
	update_post_meta($session_obj->metadata->id, 'carwash_payment', 'canceled');
?>
	<!DOCTYPE html>
	<html lang="en">

	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title><?php echo __('Stripe Payment Cancel', 'carwash') ?></title>
		<style>
			body {
				font-family: "Libre Franklin", "Helvetica Neue", helvetica, arial, sans-serif;
				background-color: #222;
				color: #eee;
			}

			.cancel-info {
				width: 400px;
				margin: 15% auto 0;
				padding: 10px 20px;
				border: 1px solid #ffc107;
				border-radius: 10px;
				box-sizing: border-box;
			}

			.text-center {
				text-align: center;
			}

			h4 {
				color: #ffc107;
			}

			h5 {
				color: #7099bf;
				margin-bottom: 10px;
			}

			p {
				font-size: 14px;
			}

			.item-txt {
				color: #eee;
				margin: 5px 0;
			}

			.go-back a {
				color: #ffc107;
				text-decoration: none;
			}
		</style>
	</head>

	<body>
		<div class="cancel-info text-center">
			<h4><?php echo __('Payment Canceled', 'carwash') ?></h4>
			<h5><?php echo __('Your Appointment details are', 'carwash') ?></h5>
			<p class="item-txt"><?php echo __('Appointment No.: ', 'carwash') . $session_obj->metadata->appointment_id ?></p>
			<p class="item-txt"><?php echo __('Package Name: ', 'carwash') . $session_obj->metadata->package_name ?></p>
			<p class="item-txt"><?php echo __('Price: $', 'carwash') . '<b>' . number_format($session_obj->amount_total / 100, 2) . '<b>' ?></p>
		</div>
		<div class="text-center go-back">
			<p><?php echo __('Go back to ') ?><a href="<?php echo site_url() ?>"><?php echo __('Home') ?></a></p>
		</div>
	</body>

	</html>
<?php
	// Clear the Stripe session 
	unset($_SESSION['stripe_session_id']);
} else {
	wp_safe_redirect(site_url());
	exit;
}
?>