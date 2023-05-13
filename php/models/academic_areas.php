<?php

class AcademicAreas extends data_conn
{
    private $conn;
    public function __construct(){
        $this->conn = $this->dbConn();
    }

    public function getNameAcademicArea($id_academic_area){
        $result = '';
        $get_results = $this->conn->query("
            SELECT name_academic_area FROM school_control_ykt.academic_areas WHERE id_academic_area = '$id_academic_area'");
        while ($row = $get_results->fetch(PDO::FETCH_OBJ)) {
            $result = $row->name_academic_area;
        }

        return $result;
    }
}