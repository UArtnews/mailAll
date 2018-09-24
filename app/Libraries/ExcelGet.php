<?

class ExcelGet {

    function __construct(){
    }

    function asArray($fileName, $columnNames)
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
                        if(in_array($colName, $columnNames)){
                            $tmpArray[$colName] = $value;
                        }
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

}