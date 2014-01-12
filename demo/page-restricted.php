<?php
require_once('vendor/autoload.php');
$age = new AKlump\VerifyAge\VerifyAge('config.yaml');

// Another way to add an ignore, besides in the yaml config.
$age->ignore('/');
?>
<?php include 'templates/header.php' ?>

<h1>Age-Restricted Content</h1>

<p class="jumbotron">You only see this page because you have verified your age.</p>

<?php include 'templates/footer.php' ?>
