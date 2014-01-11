<?php
require_once('../verify_age/vendor/autoload.php');
$age = new AKlump\VerifyAge\VerifyAge('../config.yaml');
?>
<?php include '../header.php' ?>

<h1>Age Restricted Content in a Subfolder</h1>

<p class="jumbotron">You only see this page because you have verified your age.</p>

<?php include '../footer.php' ?>
