<?php
require_once('verify_age/vendor/autoload.php');
$age = new AKlump\VerifyAge\VerifyAge(__FILE__, 'verify_age/config_default.yaml');
?>
<!DOCTYPE html>
<html>
<head>
  <title></title>
  <?php print $age->getHead(); ?>
</head>
<body>
<h1>Age Restricted Material</h1>

<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Excepturi dicta dolorem eligendi qui doloribus? Ipsam pariatur rerum reprehenderit hic. Voluptatum, ratione repellat repudiandae facilis porro error consequatur possimus consequuntur. Dolorum. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Excepturi dicta dolorem eligendi qui doloribus? Ipsam pariatur rerum reprehenderit hic. Voluptatum, ratione repellat repudiandae facilis porro error consequatur possimus consequuntur. Dolorum. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Excepturi dicta dolorem eligendi qui doloribus? Ipsam pariatur rerum reprehenderit hic. Voluptatum, ratione repellat repudiandae facilis porro error consequatur possimus consequuntur. Dolorum.</p>

<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Excepturi dicta dolorem eligendi qui doloribus? Ipsam pariatur rerum reprehenderit hic. Voluptatum, ratione repellat repudiandae facilis porro error consequatur possimus consequuntur. Dolorum. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Excepturi dicta dolorem eligendi qui doloribus? Ipsam pariatur rerum reprehenderit hic. Voluptatum, ratione repellat repudiandae facilis porro error consequatur possimus consequuntur. Dolorum. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Excepturi dicta dolorem eligendi qui doloribus? Ipsam pariatur rerum reprehenderit hic. Voluptatum, ratione repellat repudiandae facilis porro error consequatur possimus consequuntur. Dolorum.</p>

<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Excepturi dicta dolorem eligendi qui doloribus? Ipsam pariatur rerum reprehenderit hic. Voluptatum, ratione repellat repudiandae facilis porro error consequatur possimus consequuntur. Dolorum. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Excepturi dicta dolorem eligendi qui doloribus? Ipsam pariatur rerum reprehenderit hic. Voluptatum, ratione repellat repudiandae facilis porro error consequatur possimus consequuntur. Dolorum. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Excepturi dicta dolorem eligendi qui doloribus? Ipsam pariatur rerum reprehenderit hic. Voluptatum, ratione repellat repudiandae facilis porro error consequatur possimus consequuntur. Dolorum.</p>

<?php print $age->getBody(); ?>
</body>
</html>