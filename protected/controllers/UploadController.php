<?php

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class UploadController extends Controller
{
    const SHEETS = ['first', 'second'];

    function actionIndex()
    {
        $dir = Yii::getPathOfAlias('application.uploads'); 
        $uploaded = false;
        $result = false;
        $model = new Upload();
        if (isset($_POST['Upload'])) {
            $model->attributes = $_POST['Upload']; 
            $file = CUploadedFile::getInstance($model, 'file'); 
            if ($model->validate()) {
                $filePath = $dir . '/' . $file->getName(); 
                $uploaded = $file->saveAs($filePath);
            }
        }

        if ($uploaded) {
            $result = Upload::handle($filePath);
        }

        $this->render('index', [ 
            'result' => $result,
            'model' => $model, 
            'uploaded' => $uploaded, 
            'dir' => $dir,
        ]); 
    }
}