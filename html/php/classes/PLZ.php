<?php
/**
 * if you use PLZ-Class in an other file, include also <>-Class
 */

class PLZ{
    private $location; //string
    private $plzNumber; //string
    private $plzID; //int

    public function __construct(string $location, string $plzNumber, int $plzID){
        $this->location = $location;
        $this->plzNumber = $plzNumber;
        $this->plzID = $plzID;
    }

    /**
     * get specific PLZ
     */
    public static function getPLZ(int $plzID){
        //not implemented in prototype
        return $plz;
    }

    /**
     * get all PLZ that start with $firstNumbers
     */
    public static function getPLZStartWithNumbers(string $firstNumbers){
        require('dbconnect.php');
        mysqli_select_db($connection, 'db_sharey');
        
        $query = "SELECT `pz_location`,`pz_plz`,`pz_plzID` 
                    FROM `tbl_plz` 
                    WHERE `pz_plz` LIKE '".$firstNumbers."%'";

        $res = mysqli_query($connection, $query);

        $plzs = [];
        
        while(($data = mysqli_fetch_array($res)) != false){
            $plzs[] = new PLZ(utf8_encode($data['pz_location']), $data['pz_plz'], $data['pz_plzID']);
        }

        return $plzs;
    }

    /**
     * convert a PLZ-Object to JSON-Object
     */
    public function toJson() {
        return json_encode(array(
            'location' => $this->getLocation(),
            'plzNumber' => $this->getPlzNumber(),
            'plzID' => $this->getPlzID()      
        ));
    }

    #region getter

    public function getLocation(){
        return $this->location;
    }

    public function getPlzNumber(){
        return $this->plzNumber;
    }

    public function getPlzID(){
        return $this->plzID;
    }

    #endregion
}

?>