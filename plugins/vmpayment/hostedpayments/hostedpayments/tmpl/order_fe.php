<dl>
<?php foreach($viewData['data'] as $label=>$value): ?>
    <dt style="font-weight: bold;"><?php echo $label; ?></dt>
    <dd><?php echo $value; ?></dd>
<?php endforeach; ?>
</dl>
