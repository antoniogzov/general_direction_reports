<?php

class Assignments extends data_conn
{
    private $conn;
    public function __construct()
    {
        $this->conn = $this->dbConn();
    }

    public function getSubjectsFromTeachers($stmt)
    {
        $results = array();
        $query;

        try {

            $query = $this->conn->query($stmt);

            while ($row = $query->fetch(PDO::FETCH_OBJ)) {
                $results[] = $row;
            }

        } catch (Exception $e) {
            var_dump($e->getMessage());
        }

        return $results;
    }

    public function hasAssociatedLearningMap($id_assignment)
    {
        $nRows = 0;

        $nRows = $this->conn->query("SELECT count(*) 
                                    FROM iteach_grades_qualitatives.learning_maps AS lm
                                    INNER JOIN iteach_grades_qualitatives.associate_assignment_learning_map AS assc ON lm.id_learning_map = assc.id_learning_map
                                    WHERE assc.id_assignment = '$id_assignment'
        ")->fetchColumn();

        return (int)$nRows;
    }
}
