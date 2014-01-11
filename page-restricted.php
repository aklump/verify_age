<?php
require_once('verify_age/vendor/autoload.php');
$age = new AKlump\VerifyAge\VerifyAge('config.yaml');

// Another way to add an ignore, besides in the yaml config.
$age->ignore('/');
?>
<?php include 'header.php' ?>

<h1>Age-Restricted Content</h1>

<p class="jumbotron">You only see this page because you have verified your age.</p>

<?php include 'footer.php' ?>
