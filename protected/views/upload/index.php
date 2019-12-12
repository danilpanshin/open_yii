<?php if ($uploaded) :?>
<p>File was uploaded. Check <?php echo $dir?>.</p>
<?php endif ?>
<?php echo CHtml::beginForm('', 'post', array
    ('enctype' => 'multipart/form-data'))?>
    <?php echo CHtml::error($model, 'file')?>
    <?php echo Chtml::activeFileField($model, 'file')?>
    <?php echo Chtml::submitButton('Upload')?>
<?php echo Chtml::endForm()?>
