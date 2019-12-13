<?php if ($uploaded) :?>
<table>
    <th>Имя</th>
    <th>Баланс (руб.)</th>
    <?php foreach ($result as $row) :?>
        <tr>
            <td><?= $row['name'] ?></td>
            <td><?= $row['result']?></td>
        </tr>    
    <?php endforeach ?>
</table>
<?php endif ?>
<?= CHtml::beginForm('', 'post', array
    ('enctype' => 'multipart/form-data'))?>
    <?= CHtml::error($model, 'file')?>
    <?= Chtml::activeFileField($model, 'file')?>
    <?= Chtml::submitButton('Upload')?>
<?= Chtml::endForm()?>
