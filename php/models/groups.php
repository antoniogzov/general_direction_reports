<?php
set_time_limit(0);

class Groups extends data_conn
{
    private $conn;
    public function __construct()
    {
        $this->conn = $this->dbConn();
    }

    public function getGroupFromTeachers($stmt)
    {
        $results = array();

        try {

            $query = $this->conn->query($stmt);

            while ($row = $query->fetch(PDO::FETCH_OBJ)) {
                $results[] = $row;
            }
        } catch (Exception $e) {
            print_r($stmt);
            var_dump($e->getMessage());
        }

        return $results;
    }
    public function getCicles()
    {
        $results = array();

        try {
            $sql = "SELECT * FROM school_control_ykt.boletas_ciclos";
            $query = $this->conn->query($sql);

            while ($row = $query->fetch(PDO::FETCH_OBJ)) {
                $results[] = $row;
            }
        } catch (Exception $e) {
            var_dump($e->getMessage());
        }

        return $results;
    }
    public function get_cicle_path($id)
    {
        $results = array();

        try {
            $sql = "SELECT path FROM school_control_ykt.boletas_ciclos WHERE id_ciclo=".$id;
            $query = $this->conn->query($sql);

            while ($row = $query->fetch(PDO::FETCH_OBJ)) {
                $results = $row;
            }
        } catch (Exception $e) {
            var_dump($e->getMessage());
        }

        return $results;
    }
    public function getAssistanceDetails($sql_attendance)
    {
        $results = array();

        try {

            $query = $this->conn->query($sql_attendance);

            while ($row = $query->fetch(PDO::FETCH_OBJ)) {
                $results[] = $row;
            }
        } catch (Exception $e) {
            var_dump($e->getMessage());
        }

        return $results;
    }
}
