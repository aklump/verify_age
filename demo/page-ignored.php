<?php
require_once dirname(__FILE__) . '/verify_age/vendor/autoload.php';
$age = new AKlump\VerifyAge\VerifyAge('config.yaml');

// Even though we instantiate age verification, by calling ignore() the path
// to this file will not require verification.
$age->ignore($_SERVER['REQUEST_URI']);
?>
<?php $active_path = pathinfo(__FILE__, PATHINFO_BASENAME); include 'templates/header.php' ?>

<h1>Non-Restricted Content</h1>

<p class="jumbotron">You see this page because it has been ignored.  It will never require age verification.</p>

<?php include 'templates/footer.php' ?>
