<?

class ExcelGet {

    function __construct(){
    }

    function asArray($fileName, $columnNames = NULL)
    {
        $sheetCount = 0;
        $rows = array();


        $sheetCount = Excel::filter('chunk')->load($fileName)->getSheetCount();

        foreach(range(0, $sheetCount-1) as $sheetIndex){
            Excel::selectSheetsByIndex($sheetIndex)->filter('chunk')->load($fileName)->chunk(1000, function($results) use (&$rows, &$columnNames){
                foreach($results as $row){
					$tmpArray = array();
                    foreach($row as $colName => $value){
                        $colName = strtolower(str_replace(' ','_',$colName));
						if(is_array($columnNames)){
							if(in_array($colName, $columnNames)){
								$tmpArray[$colName] = $value;
							}
						}else{$tmpArray[$colName] = $value;}
						
                    }
                    array_push($rows, $tmpArray);
                }
            });
        }

        return $rows;
    }

    function oneRow($fileName)
    {
        $thisRow = array();

        Excel::selectSheetsByIndex(0)->filter('chunk')->load($fileName)->limit(1)->chunk(1000, function($results) use (&$thisRow){
            foreach($results as $row){
                $thisRow = $row->toArray();
                break;
            }
        });

        return $thisRow;
    }
//+++++++++++++++++++++++++++++RICHARD'S IMPLEMENTATION+++++++++++++++++++++++++++++++++++++
	function getArrayFromXL($fileName){
		//+EXAMPLE PATH 'uploads/FilenamezTRt.xls'
		$objPHPExcel = PHPExcel_IOFactory::load(base_path($fileName));
			foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
				$worksheetTitle     = $worksheet->getTitle();
				$highestRow         = $worksheet->getHighestRow(); // e.g. 10
				$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
				$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
				$nrColumns = ord($highestColumn) - 64;
				for ($row = 1; $row <= $highestRow; ++ $row) {
					for ($col = 0; $col < $highestColumnIndex; ++ $col) {
						$cell = $worksheet->getCellByColumnAndRow($col, $row);
						$val = $cell->getValue();
						$dataType = PHPExcel_Cell_DataType::dataTypeForValue($val);
						//echo '<td>' . $val . '<br>(Typ ' . $dataType . ')</td>';
						$xldata[$i][] = $val;
					}//end nested foreach
					//$xldata[$i]['xlindex'] = $i;
					$i++;
				}
				
			}
	}
}