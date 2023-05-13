<?php

class ClassSchendule extends data_conn
{
    private $conn;
    public function __construct()
    {
        $this->conn = $this->dbConn();
    }

    public function getDays()
    {
        $results = array();

        $query = $this->conn->query("SELECT * FROM class_schedule.days WHERE status = 1");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getBlocks()
    {
        $results = array();

        $query = $this->conn->query("SELECT * FROM class_schedule.class_block");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getDayBlockAssignment($id_days, $id_class_block, $id_period_calendar)
    {
        $results = array();
        $no_teacher = $_GET['id_teacher_sbj'];
        $query = $this->conn->query("SELECT DISTINCT sbj.name_subject, gps.group_code, sbj.color_hex
                         FROM class_schedule.relationship_assignments_class_block AS rcab
                         INNER JOIN school_control_ykt.assignments AS asg ON rcab.id_assignment = asg.id_assignment
                         INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = asg.id_group
                         INNER JOIN school_control_ykt.subjects AS sbj ON sbj.id_subject = asg.id_subject
                         WHERE rcab.id_days = $id_days AND rcab.id_class_block = $id_class_block AND rcab.no_teacher = $no_teacher AND rcab.id_period_calendar = '$id_period_calendar'
                         ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getDayBlockClassroom($id_days, $id_class_block, $id_period_calendar)
    {
        $results = array();
        $no_teacher = $_GET['id_teacher_sbj'];
        $query = $this->conn->query("SELECT cls.*
                         FROM class_schedule.relationship_assignments_class_block AS rcab
                         INNER JOIN class_schedule.classrooms AS cls ON rcab.id_classrooms = cls.id_classrooms
                         WHERE rcab.id_days = $id_days AND rcab.id_class_block = $id_class_block AND rcab.no_teacher = $no_teacher AND rcab.id_period_calendar = '$id_period_calendar'
                         ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    
}
