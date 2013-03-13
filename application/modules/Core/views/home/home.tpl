<?php echo $header?>
<h1>Semite Project</h1>
<pre>
<?php foreach ($settings->rows as $setting) : ?>
<p><?php echo $setting['key']?></p>
<?php endforeach;?>
</pre>
<?php echo $footer?>
