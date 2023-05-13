<?php

class Psychopedagogy extends data_conn
{
    private $conn;
    public function __construct()
    {
        $this->conn = $this->dbConn();
    }
    public function getAllStudentsCoordinator()
    {
        $results = array();

        $query = $this->conn->query("SELECT DISTINCT u.*, student.id_student,  student.id_family, student.group_id, student.student_code, gps.group_code, cmp.campus_name, al.academic_level, alg.degree, CONCAT(student.lastname, ' ', student.name) 
                                AS name_student, student.id_status_type, CASE WHEN student.gender = 1 THEN 'MUJER' WHEN student.gender = 0 THEN 'HOMBRE' END AS sexo, 

                                CASE WHEN fam.attorney = 1 THEN (SELECT mail FROM families_ykt.fathers WHERE id_family = student.id_family) 
                                WHEN fam.attorney = 0 THEN (SELECT mail FROM families_ykt.mothers WHERE id_family = student.id_family) 
                                END AS mail, 

                                CASE WHEN fam.attorney = 1 THEN (SELECT cell_phone FROM families_ykt.fathers WHERE id_family = student.id_family) 
                                WHEN fam.attorney = 0 THEN (SELECT cell_phone FROM families_ykt.mothers WHERE id_family = student.id_family) 
                                END AS cell_phone,

                                (SELECT CONCAT(lastname, ' ', name) FROM families_ykt.fathers WHERE id_family = student.id_family) AS father_name,
                                (SELECT cell_phone FROM families_ykt.fathers WHERE id_family = student.id_family) AS father_cell_phone,
                                (SELECT mail FROM families_ykt.fathers WHERE id_family = student.id_family) AS father_mail,

                                (SELECT CONCAT(lastname, ' ', name) FROM families_ykt.mothers WHERE id_family = student.id_family) AS mother_name,
                                (SELECT cell_phone FROM families_ykt.mothers WHERE id_family = student.id_family) AS mother_cell_phone,
                                (SELECT mail FROM families_ykt.mothers WHERE id_family = student.id_family) AS mother_mail,


                                (SELECT CONCAT(street, ' ', ext_number, (CASE WHEN int_number IS NULL THEN ',' WHEN int_number = 0 THEN ',' ELSE CONCAT(' int. ',int_number,', ') END),  colony, ', ', delegation, '. ', postal_code) 
                                FROM families_ykt.addresses_families WHERE id_family_address = fam.id_family_address) AS direction 
                                
                                FROM 

                                (SELECT groups.id_group, rel_coord_aca.no_teacher
                                FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
                                INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
                                INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
                                INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade
                                INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
                                INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
                                INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
                                INNER JOIN colaboradores_ykt.colaboradores AS col ON assg.no_teacher = col.no_colaborador
                                
                                UNION 

                                SELECT  gps.id_group, rel_coord_aca.no_teacher
                                FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
                                INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
                                INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
                                INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group 
                                INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
                                INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
                                INNER JOIN colaboradores_ykt.colaboradores AS col ON asgm.no_teacher = col.no_colaborador
                                )

                                AS u
                                INNER JOIN school_control_ykt.students AS student ON u.id_group = student.group_id
                                INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = u.id_group
                                INNER JOIN families_ykt.families  AS fam ON student.id_family = fam.id_family
                                INNER JOIN school_control_ykt.academic_levels_grade AS alg ON gps.id_level_grade = alg.id_level_grade
                                INNER JOIN school_control_ykt.academic_levels AS al ON al.id_academic_level = alg.id_academic_level
                                INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus

                                WHERE no_teacher = $_SESSION[colab]  AND student.status = 1
                                ORDER BY academic_level
                                    ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getAllStudentsTeacher()
    {
        $results = array();

        $query = $this->conn->query("SELECT DISTINCT u.*, student.id_student,  student.id_family, student.group_id, student.student_code, gps.group_code, cmp.campus_name, al.academic_level, alg.degree, CONCAT(student.lastname, ' ', student.name) 
                                AS name_student, student.id_status_type, CASE WHEN student.gender = 1 THEN 'MUJER' WHEN student.gender = 0 THEN 'HOMBRE' END AS sexo, 

                                CASE WHEN fam.attorney = 1 THEN (SELECT mail FROM families_ykt.fathers WHERE id_family = student.id_family) 
                                WHEN fam.attorney = 0 THEN (SELECT mail FROM families_ykt.mothers WHERE id_family = student.id_family) 
                                END AS mail, 

                                CASE WHEN fam.attorney = 1 THEN (SELECT cell_phone FROM families_ykt.fathers WHERE id_family = student.id_family) 
                                WHEN fam.attorney = 0 THEN (SELECT cell_phone FROM families_ykt.mothers WHERE id_family = student.id_family) 
                                END AS cell_phone,

                                (SELECT CONCAT(lastname, ' ', name) FROM families_ykt.fathers WHERE id_family = student.id_family) AS father_name,
                                (SELECT cell_phone FROM families_ykt.fathers WHERE id_family = student.id_family) AS father_cell_phone,
                                (SELECT mail FROM families_ykt.fathers WHERE id_family = student.id_family) AS father_mail,

                                (SELECT CONCAT(lastname, ' ', name) FROM families_ykt.mothers WHERE id_family = student.id_family) AS mother_name,
                                (SELECT cell_phone FROM families_ykt.mothers WHERE id_family = student.id_family) AS mother_cell_phone,
                                (SELECT mail FROM families_ykt.mothers WHERE id_family = student.id_family) AS mother_mail,


                                (SELECT CONCAT(street, ' ', ext_number, (CASE WHEN int_number IS NULL THEN ',' WHEN int_number = 0 THEN ',' ELSE CONCAT(' int. ',int_number,', ') END),  colony, ', ', delegation, '. ', postal_code) 
                                FROM families_ykt.addresses_families WHERE id_family_address = fam.id_family_address) AS direction 
                                
                                FROM 

                                (SELECT groups.id_group, col.no_teacher
                                FROM school_control_ykt.groups AS groups
                                INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade
                                INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
                                INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
                                INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
                                INNER JOIN colaboradores_ykt.colaboradores AS col ON assg.no_teacher = col.no_colaborador
                                
                                UNION 

                                SELECT  gps.id_group, col.no_teacher
                                FROM  school_control_ykt.assignments AS asgm 
                                INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
                                INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group 
                                INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
                                INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
                                INNER JOIN colaboradores_ykt.colaboradores AS col ON asgm.no_teacher = col.no_colaborador
                                )

                                AS u
                                INNER JOIN school_control_ykt.students AS student ON u.id_group = student.group_id
                                INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = u.id_group
                                INNER JOIN families_ykt.families  AS fam ON student.id_family = fam.id_family
                                INNER JOIN school_control_ykt.academic_levels_grade AS alg ON gps.id_level_grade = alg.id_level_grade
                                INNER JOIN school_control_ykt.academic_levels AS al ON al.id_academic_level = alg.id_academic_level
                                INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus

                                WHERE no_teacher = $_SESSION[colab]  AND student.status = 1
                                ORDER BY academic_level
                                    ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getStudentInfo($id_student)
    {
        $results = array();

        $query = $this->conn->query("SELECT DISTINCT student.id_student,  student.id_family, student.group_id, student.student_code, student.birthdate,  cmp.campus_name, al.academic_level, alg.degree, 
        CONCAT(student.lastname, ' ', student.name) 
        AS name_student, student.id_status_type, birthdate,

        CASE WHEN student.gender = 1 THEN 'MUJER' WHEN student.gender = 0 THEN 'HOMBRE' END AS sexo, 

        CASE WHEN fam.attorney = 1 THEN (SELECT mail FROM families_ykt.fathers WHERE id_family = student.id_family) 
        WHEN fam.attorney = 0 THEN (SELECT mail FROM families_ykt.mothers WHERE id_family = student.id_family) 
        END AS mail, 

        CASE WHEN fam.attorney = 1 THEN (SELECT cell_phone FROM families_ykt.fathers WHERE id_family = student.id_family) 
        WHEN fam.attorney = 0 THEN (SELECT cell_phone FROM families_ykt.mothers WHERE id_family = student.id_family) 
        END AS cell_phone,

        (SELECT CONCAT(lastname, ' ', name) FROM families_ykt.fathers WHERE id_family = student.id_family) AS father_name,
        (SELECT cell_phone FROM families_ykt.fathers WHERE id_family = student.id_family) AS father_cell_phone,
        (SELECT mail FROM families_ykt.fathers WHERE id_family = student.id_family) AS father_mail,

        (SELECT CONCAT(lastname, ' ', name) FROM families_ykt.mothers WHERE id_family = student.id_family) AS mother_name,
        (SELECT cell_phone FROM families_ykt.mothers WHERE id_family = student.id_family) AS mother_cell_phone,
        (SELECT mail FROM families_ykt.mothers WHERE id_family = student.id_family) AS mother_mail,


        (SELECT CONCAT(street, ' ', ext_number, (CASE WHEN int_number IS NULL THEN ',' WHEN int_number = 0 THEN ',' ELSE CONCAT(' int. ',int_number,', ') END),  colony, ', ', delegation, '. ', postal_code) 
        FROM families_ykt.addresses_families WHERE id_family_address = fam.id_family_address) AS direction,
        gps.group_code
        FROM school_control_ykt.students AS student
        LEFT JOIN school_control_ykt.inscriptions AS ins ON student.id_student = ins.id_student
        LEFT JOIN families_ykt.families  AS fam ON student.id_family = fam.id_family
        LEFT JOIN school_control_ykt.assignments AS asg ON ins.id_group = asg.id_group
        LEFT JOIN school_control_ykt.groups AS gps ON gps.id_group = student.group_id
        LEFT JOIN school_control_ykt.academic_levels_grade AS alg ON gps.id_level_grade = alg.id_level_grade
        LEFT JOIN school_control_ykt.academic_levels AS al ON al.id_academic_level = alg.id_academic_level
        LEFT JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus
        

        WHERE student.id_student = '$id_student'");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function GetGroupsStudent($id_student)
    {
        $results = array();

        $query = $this->conn->query("
        SELECT gps.id_group, gps.group_code, gt.group_type 
        FROM school_control_ykt.students AS std
        INNER JOIN school_control_ykt.inscriptions AS ins ON std.id_student = ins.id_student
        INNER JOIN school_control_ykt.groups AS gps ON ins.id_group = gps.id_group
        INNER JOIN school_control_ykt.group_types AS gt ON gps.group_type_id = gt.group_type_id
        WHERE std.id_student = '$id_student' ORDER BY gt.group_type_id ASC");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getAditionalInfo($id_student)
    {
        $results = array();

        $query = $this->conn->query("SELECT id_in_students_ykt, 
        second_contact.relationship AS sec_relationship, CONCAT(second_contact.name, ' ', second_contact.lastname) AS emergency_name_contact,   second_contact.phone_number AS emergency_phone,
        CONCAT(second_address.main_street, ' ', outdoor_number, (CASE WHEN indoor_number IS NULL THEN ',' WHEN indoor_number = 0 THEN ',' ELSE CONCAT(' int. ',indoor_number,', ') END), ' ', colony, ' ', delegation) AS emergency_address
        FROM prospects.students AS std_pros
        INNER JOIN prospects.secondary_contacts AS second_contact ON std_pros.id_family_prospects = second_contact.id_family_prospects
        INNER JOIN prospects.secondary_address AS second_address ON second_contact.id_family_prospects = second_address.id_family_prospects
        WHERE std_pros.id_in_students_ykt = '$id_student'
        ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getWhoRefered1($who_reffered)
    {
        $results = array();

        $query = $this->conn->query("SELECT *
        FROM psychopedagogy.who_refered
        WHERE id_who_refered = '$who_reffered'");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    
    public function GetStudentAVG($id_student, $id_academic_area)
    {
        $results = array();

        $query = $this->conn->query("SELECT AVG(final_grade) AS average
        FROM iteach_grades_quantitatives.final_grades_assignment
        INNER JOIN school_control_ykt.assignments AS asg ON final_grades_assignment.id_assignment = asg.id_assignment
        INNER JOIN school_control_ykt.subjects AS sub ON asg.id_subject = sub.id_subject
        WHERE id_student = '$id_student' AND id_academic_area = '$id_academic_area' AND final_grade IS NOT NULL");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function GetStudentTerapeuticCards($id_student)
    {
        $results = array();

        $query = $this->conn->query("SELECT DATE(tpc.logdate) AS fecha_registro_format,  tpc.*, CONCAT (col.nombres_colaborador,' ', col.apellido_paterno_colaborador, ' ', col.apellido_materno_colaborador) AS colaborador_registro
        FROM psychopedagogy.therapeutic_cards AS tpc
        INNER JOIN colaboradores_ykt.colaboradores AS col ON tpc.no_colab_registered = col.no_colaborador
        WHERE id_student = '$id_student'");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function GetStudentParentsTracking($id_student)
    {
        $results = array();

        $query = $this->conn->query("SELECT DATE(tpt.logdate) AS fecha_registro_format, trt.description_tracking_type,
        tpt.*, CONCAT (col.nombres_colaborador,' ', col.apellido_paterno_colaborador, ' ', col.apellido_materno_colaborador) AS colaborador_registro
        FROM psychopedagogy.parents_tracking AS tpt
        INNER JOIN colaboradores_ykt.colaboradores AS col ON tpt.no_colab_registered = col.no_colaborador
        INNER JOIN psychopedagogy.tracking_type AS trt ON trt.id_tracking_type = tpt.id_tracking_type
        WHERE id_student = '$id_student'");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function GetIncidents($id_student)
    {
        $results = array();

        $query = $this->conn->query("SELECT * FROM student_incidents.student_incidents_log
        WHERE id_student = '$id_student' AND active_incident = 1");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function GetJustify($id_student)
    {
        $results = array();

        $query = $this->conn->query("SELECT * FROM absence_excuse.absences_excuse
        WHERE id_student = '$id_student' AND active_excuse = 1");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getMedicalInfo($id_student)
    {
        $results = array();

        $query = $this->conn->query("SELECT * FROM school_control_ykt.health_data_students
        WHERE id_student = '$id_student'");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    
    public function GetPsicoInfo($id_student)
    {
        $results = array();

        $query = $this->conn->query("SELECT * FROM psychopedagogy.therapeutic_cards
        WHERE id_student = '$id_student'");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function GetColaboradores()
    {
        $results = array();

        $query = $this->conn->query("SELECT colab.*, 
        CONCAT(colab.nombres_colaborador, ' ', colab.apellido_paterno_colaborador, ' ', colab.apellido_materno_colaborador) AS colaborador
         FROM colaboradores_ykt.colaboradores AS colab
         WHERE no_colaborador != 0");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function GetStudentTerapeuticCardsByID($id_terapheutic_card)
    {
        $results = array();

        $query = $this->conn->query("SELECT DATE(tpc.logdate) AS fecha_registro_format,  tpc.*, CONCAT (col.nombres_colaborador,' ', col.apellido_paterno_colaborador, ' ', col.apellido_materno_colaborador) AS colaborador_registro
        FROM psychopedagogy.therapeutic_cards AS tpc
        INNER JOIN colaboradores_ykt.colaboradores AS col ON tpc.no_colab_registered = col.no_colaborador
        WHERE id_therapeutic_cards = '$id_terapheutic_card'");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function GetStudentTerapeuticCardsInscription($id_student)
    {
        $results = array();

        $query = $this->conn->query("SELECT * FROM prospects.psychopedagogical
        WHERE id_student = '$id_student'");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function GetPsychopedagogicalData($id_student)
    {
        $results = array();

        $query = $this->conn->query("SELECT * FROM prospects.psychopedagogical
        WHERE id_student = '$id_student'");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getKindsInterviews($id)
    {
        $results = array();

        if ($id > 0) {
            $str_att = 'WHERE id_kinds_interview = ' . $id . '';
        } else {
            $str_att = '';
        }

        $query = $this->conn->query("SELECT * FROM psychopedagogy.kinds_interview $str_att  ORDER BY description ASC");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function reasonsWhyConclused($id)
    {

        if ($id > 0) {
            $str_att = 'WHERE id_reason = ' . $id . '';
        } else {
            $str_att = '';
        }
        $results = array();

        $query = $this->conn->query("SELECT * FROM psychopedagogy.reasons_why_conclused  $str_att ORDER BY description ASC");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getWhoRefered($id)
    {
        $results = array();

        if ($id > 0) {
            $str_att = 'WHERE who_reffered = '.$id.' ';
        } else {
            $str_att = '';
        }

        $query = $this->conn->query("SELECT * FROM psychopedagogy.who_refered $str_att  ORDER BY description ASC");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getKindsTracking()
    {
        $results = array();


        $query = $this->conn->query("SELECT * FROM psychopedagogy.tracking_type ORDER BY description_tracking_type ASC");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    
}
