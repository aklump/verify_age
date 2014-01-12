<?php $active_path = pathinfo(__FILE__, PATHINFO_BASENAME); include 'templates/header.php' ?>

<h1>Verify Age Demo</h1>

<p class="jumbotron">To see how the age verfication works click on any of the links above.  <em>Home</em> does not require age verification because there is not code implemented for it, whereas <em>Not Restricted</em> is also viewable, because it has explicitly been ignored. Download the source package and study it to learn more.</p>

<?php include 'templates/footer.php' ?>