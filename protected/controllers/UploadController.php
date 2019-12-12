<?php


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class UploadController extends Controller
{
    function actionIndex()
    {
        $dir = Yii::getPathOfAlias('application.uploads'); 
        $uploaded = false;
        $model = new Upload();
        if (isset($_POST['Upload'])) {
            $model->attributes = $_POST['Upload']; 
            $file = CUploadedFile::getInstance($model, 'file'); 
            if ($model->validate()) {
                $uploaded = $file->saveAs($dir . '/' . $file->getName()); 
            }
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Hello World !');

        $writer = new Xlsx($spreadsheet);
        $writer->save('hello world.xlsx');
        
        $this->render('index', [ 
            'model' => $model, 
            'uploaded' => $uploaded, 
            'dir' => $dir,
        ]); 
    }
}