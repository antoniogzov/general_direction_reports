<?php

class SchoolReports extends data_conn
{
    private $conn;
    public function __construct()
    {
        $this->conn = $this->dbConn();
    }

    public function gsrPrimaryMenWomenAllCampus($id_student)
    {
        $results = array();

        $get_results = $this->conn->query("SELECT * FROM school_control_ykt.subjects WHERE id_subject = '$id_subject'");
        if ($row = $get_results->fetch(PDO::FETCH_OBJ)) {
            $results = $row;
        }

        return $results;
    }
}