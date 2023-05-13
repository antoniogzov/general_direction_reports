<?php

class gPeriods extends data_conn
{
    private $conn;
    public function __construct()
    {
        $this->conn = $this->dbConn();
    }

   
    public function getPeriods()
    {
        $periods = '';
        $id_assignment ='32';
        $sql     = $this->conn->query("
        SELECT DISTINCT pc.no_period FROM iteach_grades_quantitatives.period_calendar AS pc 
        INNER JOIN school_control_ykt.level_combinations AS lvc ON lvc.id_level_combination = pc.id_level_combination 
        INNER JOIN school_control_ykt.assignments AS asgm 
        INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = asgm.id_group 
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade 
        INNER JOIN school_control_ykt.academic_levels AS acl ON acl.id_academic_level = aclg.id_academic_level 
        WHERE asgm.id_assignment = '$id_assignment' AND lvc.id_academic_level = acl.id_academic_level
        ");
        
        return $periods;
    }
}