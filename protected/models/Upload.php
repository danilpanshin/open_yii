<?php
class Upload extends CFormModel
{
   public $file;

   public function rules()
   {
        return [
            ['file', 'file', 'types' => 'xlsx'],
        ];
    }
}