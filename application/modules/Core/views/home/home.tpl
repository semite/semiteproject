<?php echo $header?>
<h1>Semite Project</h1>
<?php foreach ($settings->rows as $setting) : ?>
<p><?php echo $setting['key']?></p>
<?php endforeach;?>
<?php echo $footer?>
