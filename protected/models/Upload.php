<?php

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Upload extends CFormModel
{
   public $file;

   const SHEETS = ['first', 'second'];

   public function rules()
   {
        return [
            ['file', 'file', 'types' => 'xlsx'],
        ];
    }


    /**
     * getReader
     *
     * @return PhpOffice
     */
    public static function getReader(): PhpOffice\PhpSpreadsheet\Reader\Xlsx  
    {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setReadDataOnly(true);
        return $reader;
    }

    /**
     * getSheetData
     *
     * @param  mixed $spreadsheet
     * @param  int $number
     *
     * @return array
     */
    public static function getSheetData($spreadsheet, int $number): array
    {
        try {
            return $spreadsheet->getSheetByName(static::SHEETS[$number])->toArray();
        } catch (\Error $e) {
            echo 'Лист файла должен иметь название first/second';
            exit();
        }
    }

    /**
     * parseFirstSheetData
     *
     * @param  array $firstSheetData
     *
     * @return void
     */
    public static function parseFirstSheetData(array $firstSheetData): void
    {
        foreach($firstSheetData as $row) {
            $id = $row[0] ?? 0;
            $name = $row[1] ?? 'undefined';
            $balance = $row[2] ?? 0;
            static::storeFirstSheetData($id, $name, $balance);
        }
    }

    /**
     * storeFirstSheetData
     *
     * @param  int $id
     * @param  string $name
     * @param  int $balance
     *
     * @return void
     */
    protected static function storeFirstSheetData(int $id, string $name, int $balance): void
    {
        $command = Yii::app()->db->createCommand();
        $command->insert('customers', [
            'id' => $id,
            'name' => $name,
            'balance' => $balance
        ]);
    }

    /**
     * parseSecondSheetData
     *
     * @param  array $secondSheetData
     *
     * @return void
     */
    public static function parseSecondSheetData(array $secondSheetData): void
    {
        foreach($secondSheetData as $row) {
            $customer_id = $row[0] ?? 0;
            $transfer = $row[1] ?? 0;

            static::storeSecondSheetData($customer_id, $transfer);
        }
    }

    /**
     * storeSecondSheetData
     *
     * @param  int $customer_id
     * @param  int $transfer
     *
     * @return void
     */
    protected static function storeSecondSheetData(int $customer_id, int $transfer): void
    {
        $command = Yii::app()->db->createCommand();
        $command->insert('bankings', [
            'customer_id' => $customer_id,
            'transfer' => $transfer
        ]);
    }

    /**
     * getResultTable
     *
     * @return array
     */
    public static function getResultTable(): array
    {
        $command = Yii::app()->db->createCommand();
        return $command->select('
                customers.name,
                SUM(bankings.transfer) + customers.balance AS result
                ')
                ->from('customers')
                ->leftJoin('bankings', 'customers.id = bankings.customer_id')
                ->group(array('customers.name'))
                ->queryAll();
    }

    /**
     * handle
     *
     * @param  string $filePath
     *
     * @return array
     */
    public static function handle(string $filePath): array
    {
        $spreadsheet = static::getReader()->load($filePath);
        $firstSheetData = static::getSheetData($spreadsheet, 0);
        $secondSheetData = static::getSheetData($spreadsheet, 1);
        
        $transaction = Yii::app()->db->beginTransaction();
        try {
            static::parseFirstSheetData($firstSheetData);
            static::parseSecondSheetData($secondSheetData);
            $result = static::getResultTable();
            $command = Yii::app()->db->createCommand();
            $command->truncateTable('customers');
            $command->truncateTable('bankings');
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollback();
        }
        return $result;
    }
}