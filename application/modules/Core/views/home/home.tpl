<?php echo $header?>
Welcome Home
<pre>
<?php foreach ($settings->rows as $setting) : ?>
<p><?php echo $setting['key']?></p>
<?php endforeach;?>
</pre>
<?php echo $footer?>
