<?php
require_once dirname(__FILE__, 4) . '/general/php/models/Connection.php';
class ModelCalculations extends data_conn
{
    private $conn;

    public function __construct(){
        $this->conn = $this->dbConn();
    }

    public function getInfoModelCalc($operation_model_id){
        
        $results = array();

        $query = $this->conn->query("
            SELECT *
            FROM iteach_dynamic_calculations.operations_models
            WHERE operation_model_id = $operation_model_id
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results = $row;
        }

        return $results;
    }

    public function getConfiguredModels($id_assignment, $id_period_calendar){

        $results = array();

        $query = $this->conn->query("
            SELECT t1.*
            FROM iteach_dynamic_calculations.operation_model_assignment AS t1
            INNER JOIN iteach_dynamic_calculations.operations_models AS t2 ON t1.operation_model_id = t2.operation_model_id
            WHERE t1.id_assignment = $id_assignment
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results = $row;
        }

        return $results;

    }

    public function getModelsCalculation(){

        $results = array();

        $query = $this->conn->query("
            SELECT t1.*
            FROM iteach_dynamic_calculations.operations_models AS t1
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;

    }

    public function applymodel($sql, $data){

        $id_evaluation_plan = 0;

        try {

            $stmt = $this->conn->prepare($sql);

            if($stmt->execute($data)){
                $id_evaluation_plan = $this->conn->lastInsertId();
            }

        } catch (\Throwable $th) {

            print_r($data);

            $this->conn->rollBack() ;
            echo "Mensaje de Error: " . $th->getMessage();
        }

        return $id_evaluation_plan;

    }

    public function getNameEvaluationSourceByIdEVP($id_evaluation_plan){

        $results = array();

        $query = $this->conn->query("
            SELECT t2.id_evaluation_source, t2.evaluation_name
            FROM iteach_grades_quantitatives.evaluation_plan AS t1
            INNER JOIN iteach_grades_quantitatives.evaluation_source AS t2 ON t1.id_evaluation_source = t2.id_evaluation_source
            WHERE t1.id_evaluation_plan = $id_evaluation_plan
            ");

        if ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results = $row;
        }

        return $results;

    }

    public function addGatherings($no_gathering, $id_evaluation_plan){

        $sql = "INSERT INTO iteach_grades_quantitatives.conf_grade_gathering (id_evaluation_plan, name_item) VALUES (?, ?)";

        $data_evp = $this->getNameEvaluationSourceByIdEVP($id_evaluation_plan);
        $stmt = $this->conn->prepare($sql);

        try {

            for ($i=0; $i < $no_gathering; $i++) { 
                $stmt->execute([$id_evaluation_plan, $data_evp->evaluation_name . ' ' . ($i+1)]);
            }

        } catch (\Throwable $th) {

            print_r($data);

            $this->conn->rollBack() ;
            echo "Mensaje de Error: " . $th->getMessage();
        }

        return $id_evaluation_plan;

    }

    public function checkAnyCriteriaConfAssg($id_assignment, $id_period_calendar){
        
        $results = 0;

        $query = $this->conn->query("
            SELECT count(*)
            FROM iteach_grades_quantitatives.evaluation_plan AS ev_plan
            WHERE ev_plan.id_assignment = '$id_assignment' AND ev_plan.id_period_calendar = '$id_period_calendar'
            ");

        $results = $query->fetchColumn();
        
        return $results;
    }


}