<?php $base_path = isset($base_path) ? $base_path : ''; ?>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://code.jquery.com/jquery.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="<?php print $base_path?>js/bootstrap.min.js"></script>
    <script src="<?php print $base_path?>js/demo.min.js"></script>
  </div>
  <?php if (isset($age)): print $age->getBody(); endif; ?>
  </body>
</html>
