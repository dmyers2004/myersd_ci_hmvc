<link rel="stylesheet" type="text/css" href="<?=base_url() ?>/modules/flash_msg/jquery.bootstrap.growl.css"/>
<script src="<?=base_url() ?>/modules/flash_msg/jquery.bootstrap.growl.js"></script>
<?php if (is_array($msgs)) { ?>
  <script>
  $(document).ready(function(){
    <?php foreach ($msgs as $msg) { ?>
      jQuery.noticeAdd(<?=json_encode($msg) ?>);
    <?php } ?>
  })
  </script>
<?php } ?>
