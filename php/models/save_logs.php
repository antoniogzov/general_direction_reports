<?php

class ActionsLogs extends data_conn {
    private $conn;
    public function __construct() {
        $this->conn = $this->dbConn();
    }

    /*public function save_log($no_teacher, $table, $column, $id, $action, $previous_value, $new_value, $additional_comments, $date_log){
        // To Reac CSV File
        $csvfile = '../../log_grades.csv';
        if(file_exists($csvfile)){

            // Append to new data in csv file  
            $data[] = [$no_teacher, $table, $column, $id, $action, $previous_value, $new_value, $additional_comments, $date_log];
            // csv File update 
            $file_Path = fopen($csvfile, 'a');

            foreach ($data AS $dat) {
                fputcsv($file_Path, $dat);
            }

            fclose($file_Path);
        }
    }*/

    public function save_log($no_teacher, $table, $column, $id, $action, $previous_value, $new_value, $id_assignment, $id_period_calendar, $student_code, $additional_comments){

        $date_log = date('Y-m-d H:i:s');
        try {
            $sql = "INSERT INTO audits.iteach (no_teacher, table_, column_, id_column, action_, previous_value, new_value, id_assignment, id_period_calendar, student_code, additional_comments, date_log) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
            $stmt = $this->conn->prepare($sql);
            $exc = $stmt->execute([$no_teacher, $table, $column, $id, $action, $previous_value, $new_value, $id_assignment, $id_period_calendar, $student_code, $additional_comments, $date_log]);
        } catch(Exception $e) {
            echo 'Exception -> ';
            var_dump($e->getMessage());
        }
    }

}