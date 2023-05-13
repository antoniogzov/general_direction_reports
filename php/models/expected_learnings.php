<?php
include_once 'groups.php';
include_once 'attendance.php';

class ExpectedLearnings extends data_conn
{
    private $conn;
    public function __construct()
    {
        $this->conn = $this->dbConn();
    }
    public function getPeriodCatalog($id_assignment, $id_period_calendar)
    {
        $results = array();

        $query = $this->conn->query("
            SELECT elc.* 
            FROM expected_learning.relationship_expected_learning_assignments AS rela
            INNER JOIN expected_learning.expected_learning_index AS eli ON rela.id_expected_learning_index = eli.id_expected_learning_index
            INNER JOIN expected_learning.expected_learning_subindex AS els ON eli.id_expected_learning_index = els.id_expected_learning_index
            INNER JOIN expected_learning.expected_learning_catalog AS elc ON elc.id_expected_learning_subindex = els.id_expected_learning_subindex
            WHERE rela.id_assignment = '$id_assignment' AND els.id_period_calendar = '$id_period_calendar' ORDER BY no_position ASC");




        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getExistCatalog($id_assignment)
    {
        $results = array();
        $query = $this->conn->query("SELECT DISTINCT  eli.index_description, eli.id_expected_learning_index,
        CONCAT(colab.nombres_colaborador, ' ', colab.apellido_paterno_colaborador ,' ', colab.apellido_materno_colaborador) AS nombre_colaborador
        FROM school_control_ykt.assignments AS asg
        INNER JOIN school_control_ykt.subjects AS sub ON sub.id_subject = asg.id_subject
        INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = asg.id_group
        INNER JOIN expected_learning.expected_learning_subindex AS els ON els.id_subject = sub.id_subject AND els.id_level_grade = gps.id_level_grade
        INNER JOIN expected_learning.expected_learning_index AS eli ON eli.id_expected_learning_index = els.id_expected_learning_index
        INNER JOIN expected_learning.relationship_expected_learning_assignments AS rela ON rela.id_expected_learning_index = eli.id_expected_learning_index
        INNER JOIN colaboradores_ykt.colaboradores AS colab ON colab.no_colaborador = eli.no_teacher_created
        WHERE asg.id_assignment = '$id_assignment' AND rela.id_assignment = '$id_assignment'");
        if ($query->rowCount() == 0) {
            $query = $this->conn->query("
            SELECT DISTINCT  eli.index_description, eli.id_expected_learning_index,
            CONCAT(colab.nombres_colaborador, ' ', colab.apellido_paterno_colaborador ,' ', colab.apellido_materno_colaborador) AS nombre_colaborador
            FROM school_control_ykt.assignments AS asg
            INNER JOIN school_control_ykt.subjects AS sub ON sub.id_subject = asg.id_subject
            INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = asg.id_group
            INNER JOIN expected_learning.expected_learning_subindex AS els ON els.id_subject = sub.id_subject AND els.id_level_grade = gps.id_level_grade
            INNER JOIN expected_learning.expected_learning_index AS eli ON eli.id_expected_learning_index = els.id_expected_learning_index
            INNER JOIN expected_learning.relationship_expected_learning_assignments AS rela ON rela.id_expected_learning_index = eli.id_expected_learning_index
            INNER JOIN colaboradores_ykt.colaboradores AS colab ON colab.no_colaborador = eli.no_teacher_created
            WHERE asg.id_assignment = '$id_assignment' AND rela.id_assignment != '$id_assignment'");




            while ($row = $query->fetch(PDO::FETCH_OBJ)) {
                $results[] = $row;
            }
        }


        return $results;
    }
    public function getExistCatalogCoordinator($no_teacher, $id_academic_level, $id_subject)
    {
        $results = array();
        $query = $this->conn->query("SELECT * FROM (
        SELECT acdlvldg.id_academic_level, acdlvldg.academic_level, rel_coord_aca.no_teacher, sbj.id_academic_area, 
        eli.index_description, eli.id_expected_learning_index, CONCAT(colab.nombres_colaborador, ' ', colab.apellido_paterno_colaborador ,' ', colab.apellido_materno_colaborador) AS nombre_colaborador
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
        INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON lvl_com.id_academic_level = aclg.id_academic_level AND aclg.id_academic_level = '$id_academic_level' AND groups.id_level_grade = aclg.id_level_grade
    	INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
        INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND sbj.id_subject = $id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
        INNER JOIN school_control_ykt.academic_levels AS acdlvldg ON acdlvldg.id_academic_level = aclg.id_academic_level
        INNER JOIN expected_learning.expected_learning_subindex AS els ON els.id_subject = sbj.id_subject AND els.id_level_grade = groups.id_level_grade
        INNER JOIN expected_learning.expected_learning_index AS eli ON eli.id_expected_learning_index = els.id_expected_learning_index
        INNER JOIN expected_learning.relationship_expected_learning_assignments AS rela ON rela.id_expected_learning_index = eli.id_expected_learning_index
        INNER JOIN colaboradores_ykt.colaboradores AS colab ON colab.no_colaborador = eli.no_teacher_created
        

        UNION
        SELECT  acdlvldg.id_academic_level, acdlvldg.academic_level, rel_coord_aca.no_teacher, sbj.id_academic_area, 
        eli.index_description, eli.id_expected_learning_index, CONCAT(colab.nombres_colaborador, ' ', colab.apellido_paterno_colaborador ,' ', colab.apellido_materno_colaborador) AS nombre_colaborador
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
        INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group  
        INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
        INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON lvl_com.id_academic_level = aclg.id_academic_level AND aclg.id_academic_level = '$id_academic_level' AND groups.id_level_grade = aclg.id_level_grade
        INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
        INNER JOIN school_control_ykt.academic_levels AS acdlvldg ON acdlvldg.id_academic_level  =  aclg.id_academic_level
        INNER JOIN expected_learning.expected_learning_subindex AS els ON els.id_subject = sbj.id_subject AND els.id_level_grade = groups.id_level_grade
        INNER JOIN expected_learning.expected_learning_index AS eli ON eli.id_expected_learning_index = els.id_expected_learning_index
        INNER JOIN expected_learning.relationship_expected_learning_assignments AS rela ON rela.id_expected_learning_index = eli.id_expected_learning_index
        INNER JOIN colaboradores_ykt.colaboradores AS colab ON colab.no_colaborador = eli.no_teacher_created
        

         )
        AS u
        WHERE no_teacher = $no_teacher 
        ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getExistCatalogTeacher($no_teacher, $id_academic_level, $id_subject)
    {
        $results = array();
        $query = $this->conn->query("SELECT DISTINCT  eli.index_description, eli.id_expected_learning_index,
        CONCAT(colab.nombres_colaborador, ' ', colab.apellido_paterno_colaborador ,' ', colab.apellido_materno_colaborador) AS nombre_colaborador
        FROM school_control_ykt.assignments AS asg
        INNER JOIN school_control_ykt.subjects AS sub ON sub.id_subject = asg.id_subject AND asg.id_subject = '$id_subject'
        INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = asg.id_group
        INNER JOIN expected_learning.expected_learning_subindex AS els ON els.id_subject = sub.id_subject AND els.id_level_grade = gps.id_level_grade
        INNER JOIN school_control_ykt.academic_levels_grade AS alg ON alg.id_level_grade = gps.id_level_grade AND alg.id_academic_level = '$id_academic_level'
        INNER JOIN expected_learning.expected_learning_index AS eli ON eli.id_expected_learning_index = els.id_expected_learning_index
        INNER JOIN expected_learning.relationship_expected_learning_assignments AS rela ON rela.id_expected_learning_index = eli.id_expected_learning_index
        INNER JOIN colaboradores_ykt.colaboradores AS colab ON colab.no_colaborador = eli.no_teacher_created
        WHERE asg.no_teacher = '$no_teacher'");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getExistCatalogAssignments($id_assignment)
    {
        $results = array();
        $query = $this->conn->query("
        SELECT DISTINCT  eli.index_description, eli.id_expected_learning_index,
        CONCAT(colab.nombres_colaborador, ' ', colab.apellido_paterno_colaborador ,' ', colab.apellido_materno_colaborador) AS nombre_colaborador
        FROM school_control_ykt.assignments AS asg
        INNER JOIN school_control_ykt.subjects AS sub ON sub.id_subject = asg.id_subject
        INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = asg.id_group
        INNER JOIN expected_learning.expected_learning_subindex AS els ON els.id_subject = sub.id_subject AND els.id_level_grade = gps.id_level_grade
        INNER JOIN expected_learning.expected_learning_index AS eli ON eli.id_expected_learning_index = els.id_expected_learning_index
        INNER JOIN expected_learning.relationship_expected_learning_assignments AS rela ON rela.id_expected_learning_index = eli.id_expected_learning_index
        INNER JOIN colaboradores_ykt.colaboradores AS colab ON colab.no_colaborador = eli.no_teacher_created
        WHERE asg.id_assignment = '$id_assignment' AND rela.id_assignment = '$id_assignment'");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }


        return $results;
    }
    public function getExistCatalogAssignmentsIndex($id_assignment)
    {
        $results = array();
        $query = $this->conn->query("
        SELECT DISTINCT  eli.index_description, eli.id_expected_learning_index,
        CONCAT(colab.nombres_colaborador, ' ', colab.apellido_paterno_colaborador ,' ', colab.apellido_materno_colaborador) AS nombre_colaborador
        FROM school_control_ykt.assignments AS asg
        INNER JOIN school_control_ykt.subjects AS sub ON sub.id_subject = asg.id_subject
        INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = asg.id_group
        INNER JOIN expected_learning.expected_learning_subindex AS els ON els.id_subject = sub.id_subject AND els.id_level_grade = gps.id_level_grade
        INNER JOIN expected_learning.expected_learning_index AS eli ON eli.id_expected_learning_index = els.id_expected_learning_index
        inner join expected_learning.expected_learning_catalog AS elc ON elc.id_expected_learning_subindex = els.id_expected_learning_subindex
        INNER JOIN expected_learning.relationship_expected_learning_assignments AS rela ON rela.id_expected_learning_index = eli.id_expected_learning_index
        INNER JOIN colaboradores_ykt.colaboradores AS colab ON colab.no_colaborador = eli.no_teacher_created
        WHERE asg.id_assignment = '$id_assignment' AND rela.id_assignment = '$id_assignment'");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }


        return $results;
    }
    public function getSubindexCatalog($id_expected_learning_index)
    {
        $results = array();

        $query = $this->conn->query("SELECT * 
        FROM expected_learning.expected_learning_subindex
        WHERE id_expected_learning_index = '$id_expected_learning_index'");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getCatalogAverage($id_expected_learning_catalog)
    {
        $results = array();

        $query = $this->conn->query("SELECT learning_average
        FROM expected_learning.catalog_learning_averages
        WHERE id_expected_learning_catalog = '$id_expected_learning_catalog'");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getPeriodAverage($id_expected_learning_subindex)
    {
        $results = array();

        $query = $this->conn->query("SELECT avg(learning_average) AS period_average
        FROM expected_learning.catalog_learning_averages AS cla 
        INNER JOIN expected_learning.expected_learning_catalog AS elc ON elc.id_expected_learning_catalog = cla.id_expected_learning_catalog
        WHERE elc.id_expected_learning_subindex = '$id_expected_learning_subindex' AND cla.learning_average IS NOT NULL");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getSubindexCatalogItems($id_expected_learning_subindex)
    {
        $results = array();

        $query = $this->conn->query("SELECT * 
        FROM expected_learning.expected_learning_catalog
        WHERE id_expected_learning_subindex = '$id_expected_learning_subindex'");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function checkStructureCatalog($id_assignment, $id_period_calendar, $id_expected_learning_catalog)
    {
        $groups = new Groups;

        $results = array();

        $stmt_gt_std_gp = "SELECT stud.id_student, eld.id_expected_learning_catalog, eld.id_expected_learning_deliverables
        FROM school_control_ykt.assignments AS asg 
        INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = asg.id_group 
        INNER JOIN school_control_ykt.inscriptions AS ins ON ins.id_group = gps.id_group 
        INNER JOIN school_control_ykt.students AS stud ON stud.id_student = ins.id_student 
        LEFT JOIN  expected_learning.expected_learning_deliverables AS eld ON eld.id_student = stud.id_student  AND eld.id_period_calendar = '$id_period_calendar'  AND eld.id_expected_learning_catalog = '$id_expected_learning_catalog'
        WHERE asg.id_assignment = '$id_assignment' AND eld.id_expected_learning_deliverables IS NULL";

        $students_wo_deliverables = $groups->getGroupFromTeachers($stmt_gt_std_gp);
        foreach ($students_wo_deliverables as $student) {
            $id_student = $student->id_student;
            $stmt_ins_std_gp = "INSERT INTO expected_learning.expected_learning_deliverables
         (id_student,
        id_period_calendar,
        id_expected_learning_catalog,
        date_log)
        VALUES ('$id_student',
        '$id_period_calendar',
        '$id_expected_learning_catalog',
        NOW()
        )";
            $this->conn->query($stmt_ins_std_gp);
        }


        $query = $this->conn->query("SELECT 
        stud.id_student,
        eld.id_expected_learning_catalog,
        eld.id_expected_learning_deliverables
        FROM school_control_ykt.assignments AS asg
        INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = asg.id_group
        INNER JOIN school_control_ykt.inscriptions AS ins ON ins.id_group = gps.id_group
        INNER JOIN school_control_ykt.students AS stud ON stud.id_student = ins.id_student
        INNER JOIN  expected_learning.expected_learning_deliverables AS eld ON eld.id_student = stud.id_student  AND eld.id_period_calendar = '$id_period_calendar'  AND eld.id_expected_learning_catalog = '$id_expected_learning_catalog'
        WHERE asg.id_assignment = '$id_assignment' AND eld.id_expected_learning_deliverables");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }
        /* $query = $this->conn->query($stmt_gt_std_gp);
        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }
        return $results; */
    }
    public function getStudentsByGroupAssignment($id_assignment)
    {



        $results = array();

        $query = $this->conn->query("SELECT  *, 
        UPPER(CONCAT(lastname, ' ', name)) AS student_name
         FROM school_control_ykt.students WHERE status=1 AND id_student IN (SELECT id_student 
         FROM school_control_ykt.inscriptions 
         WHERE id_group IN (SELECT id_group FROM school_control_ykt.assignments WHERE id_assignment = '$id_assignment')) ORDER BY student_name");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getCommentPeriodnotQualified($id_assignment, $id_period_calendar)
    {



        $results = array();

        $query = $this->conn->query("SELECT  cpnq.*, CONCAT (colab.apellido_paterno_colaborador, ' ', colab.apellido_materno_colaborador, ' ', colab.nombres_colaborador) AS teacher_name
        FROM expected_learning.comment_period_not_qualified AS cpnq
        INNER JOIN colaboradores_ykt.colaboradores AS colab ON colab.no_colaborador = cpnq.no_teacher
        WHERE cpnq.id_assignment = '$id_assignment' AND cpnq.id_period_calendar = '$id_period_calendar'");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getQualificationTeacher($id_student, $id_expected_learning_catalog, $id_period_calendar)
    {
        $results = array();

        $query = $this->conn->query("SELECT * 
        FROM expected_learning.expected_learning_deliverables
        WHERE id_student = '$id_student' AND id_expected_learning_catalog = '$id_expected_learning_catalog'
        AND id_period_calendar = '$id_period_calendar'");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getStudentAverage($id_student, $id_assignment, $id_period_calendar)
    {
        $results = array();

        $query = $this->conn->query("SELECT * FROM expected_learning.expected_learning_period_average 
        WHERE id_student = '$id_student' AND id_period_calendar = '$id_period_calendar' AND id_assignment = '$id_assignment'");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getLearningAverage($id_expected_learning_catalog)
    {
        $results = array();

        $query = $this->conn->query("SELECT AVG(teacher_evidence_quailification) AS learning_avg 
        FROM expected_learning.expected_learning_deliverables 
        WHERE `id_expected_learning_catalog` = '$id_expected_learning_catalog' AND teacher_evidence_quailification IS NOT NULL");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getGroupAverage($id_assignment, $id_period_calendar)
    {
        $results = array();

        $query = $this->conn->query("SELECT AVG(student_average) AS group_average
        FROM expected_learning.expected_learning_period_average
        WHERE `id_assignment` = '$id_assignment' AND student_average IS NOT NULL AND id_period_calendar = '$id_period_calendar'");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getCatalogArchives($id_expected_learning_catalog)
    {
        $results = array();

        $query = $this->conn->query("SELECT *
        FROM expected_learning.expected_learning_archives
        WHERE id_expected_learning_catalog = '$id_expected_learning_catalog'");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getAcademicLevel($id_academic_area, $no_teacher)
    {
        $results = array();
        $query = $this->conn->query("SELECT * FROM (
        SELECT acdlvldg.id_academic_level, acdlvldg.academic_level, rel_coord_aca.no_teacher, lvl_com.id_academic_area
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
        INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON lvl_com.id_academic_level = aclg.id_academic_level AND groups.id_level_grade = aclg.id_level_grade
    	INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
        INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
        INNER JOIN school_control_ykt.academic_levels AS acdlvldg ON acdlvldg.id_academic_level = aclg.id_academic_level
        UNION
        SELECT  acdlvldg.id_academic_level, acdlvldg.academic_level, rel_coord_aca.no_teacher, sbj.id_academic_area
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
        INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group  
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
        INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
        INNER JOIN school_control_ykt.academic_levels AS acdlvldg ON acdlvldg.id_academic_level  =  aclg.id_academic_level
         )
        AS u
        WHERE no_teacher = $no_teacher AND id_academic_area = '$id_academic_area'");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getAcademicLevelTeacher($id_academic_area, $no_teacher)
    {
        $results = array();

        $query = $this->conn->query("SELECT DISTINCT al.academic_level, al.id_academic_level
        FROM school_control_ykt.assignments AS asg
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = asg.id_group
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
        INNER JOIN school_control_ykt.academic_levels AS al ON al.id_academic_level = aclg.id_academic_level
        INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
        WHERE asg.no_teacher = '$no_teacher' AND aca.id_academic_area='$id_academic_area'");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getAcademicLevelGrade($id_academic_area, $id_academic_level, $no_teacher)
    {
        $results = array();

        $query = $this->conn->query("SELECT * FROM (
            SELECT acdlvldg.id_academic_level,  aclg.id_level_grade, aclg.degree AS level_grade_write, acdlvldg.academic_level, rel_coord_aca.no_teacher, lvl_com.id_academic_area
            FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
            INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
            INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
            INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON lvl_com.id_academic_level = aclg.id_academic_level AND groups.id_level_grade = aclg.id_level_grade
            INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
            INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
            INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
            INNER JOIN school_control_ykt.academic_levels AS acdlvldg ON acdlvldg.id_academic_level = aclg.id_academic_level
            UNION
            SELECT  acdlvldg.id_academic_level, aclg.id_level_grade,  aclg.degree AS level_grade_write, acdlvldg.academic_level, rel_coord_aca.no_teacher, sbj.id_academic_area
            FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
            INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
            INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group  
            INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
            INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
            INNER JOIN school_control_ykt.academic_levels AS acdlvldg ON acdlvldg.id_academic_level  =  aclg.id_academic_level
             )
            AS u
        WHERE no_teacher = '$no_teacher' AND id_academic_area='$id_academic_area' AND id_academic_level='$id_academic_level'
        ORDER BY level_grade_write");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getAcademicPeriods($id_academic_area, $id_academic_level, $no_teacher)
    {
        $results = array();

        $arr_id_level_combination = array();
        $query = $this->conn->query("SELECT DISTINCT lvl_com.id_level_combination
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
        INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
        INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade
        INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
        INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject
        WHERE rel_coord_aca.no_teacher = $_SESSION[colab] AND lvl_com.id_academic_area = $id_academic_area AND lvl_com.id_academic_level = $id_academic_level");
        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $arr_id_level_combination[] = $row;
        }
        $id_level_combination = '';
        if (!empty($arr_id_level_combination)) {
            $id_level_combination = $arr_id_level_combination[0]->id_level_combination;

            $query = $this->conn->query("SELECT *
        FROM iteach_grades_quantitatives.period_calendar 
        WHERE id_level_combination = '$id_level_combination'");

            while ($row = $query->fetch(PDO::FETCH_OBJ)) {
                $results[] = $row;
            }
        } else {
            $query = $this->conn->query("SELECT DISTINCT lvl_com.id_level_combination, rel_coord_aca.no_teacher, sbj.id_academic_area, CASE WHEN lvl_com.id_section = 1 THEN ' - V' WHEN lvl_com.id_section = 2 THEN ' - M'  WHEN lvl_com.id_section = 3 THEN ' - MX' END AS section_descr
            FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
            INNER JOIN school_control_ykt.assignments AS assg ON assg.coordinators_group_id = rel_coord_aca.coordinators_group_id
            INNER JOIN school_control_ykt.groups AS groups ON groups.id_group = assg.id_group
            INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON groups.id_level_grade = acdlvldg.id_level_grade
            INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject
            INNER JOIN school_control_ykt.academic_areas as acar ON acar.id_academic_area = sbj.id_academic_area
            INNER JOIN school_control_ykt.level_combinations AS lvl_com ON lvl_com.id_academic_area = sbj.id_academic_area
             AND lvl_com.id_academic_level = acdlvldg.id_academic_level  AND groups.id_campus = lvl_com.id_campus AND groups.id_section = lvl_com.id_section
            INNER JOIN colaboradores_ykt.colaboradores AS col ON assg.no_teacher = col.no_colaborador
            WHERE rel_coord_aca.no_teacher = $_SESSION[colab] AND sbj.id_academic_area = $id_academic_area AND acdlvldg.id_academic_level = $id_academic_level");
            while ($row = $query->fetch(PDO::FETCH_OBJ)) {
                $arr_id_level_combination[] = $row;
            }

            if (!empty($arr_id_level_combination)) {
                if (count($arr_id_level_combination) > 1) {

                    $id_level_combination = $arr_id_level_combination[0]->id_level_combination;
                    $id_level_combination2 = $arr_id_level_combination[1]->id_level_combination;

                    $query2 = $this->conn->query("  SELECT percal.*, CASE WHEN lvc.id_section = 1 THEN ' - V' WHEN lvc.id_section = 2 THEN ' - M'  WHEN lvc.id_section = 3 THEN ' - MX' END AS section_descr
                        FROM school_control_ykt.level_combinations AS lvc
                        INNER JOIN iteach_grades_quantitatives.period_calendar AS percal ON lvc.id_level_combination = percal.id_level_combination
                        INNER JOIN school_control_ykt.academic_areas AS acar on acar.id_academic_area = lvc.id_academic_area
                        WHERE lvc.id_level_combination = '$id_level_combination' ORDER BY lvc.id_section");

                    $query = $this->conn->query("  SELECT percal.*, CASE WHEN lvc.id_section = 1 THEN ' - V' WHEN lvc.id_section = 2 THEN ' - M'  WHEN lvc.id_section = 3 THEN ' - MX' END AS section_descr
                        FROM school_control_ykt.level_combinations AS lvc
                        INNER JOIN iteach_grades_quantitatives.period_calendar AS percal ON lvc.id_level_combination = percal.id_level_combination
                        INNER JOIN school_control_ykt.academic_areas AS acar on acar.id_academic_area = lvc.id_academic_area
                        WHERE lvc.id_level_combination = '$id_level_combination2' ORDER BY lvc.id_section");

                    while ($row = $query->fetch(PDO::FETCH_OBJ)) {
                        $results[] = $row;
                    }
                    while ($row2 = $query2->fetch(PDO::FETCH_OBJ)) {
                        $results[] = $row2;
                    }
                } else {

                    $id_level_combination = $arr_id_level_combination[0]->id_level_combination;

                    $query = $this->conn->query("  SELECT percal.*, CASE WHEN lvc.id_section = 1 THEN ' - V' WHEN lvc.id_section = 2 THEN ' - M'  WHEN lvc.id_section = 3 THEN ' - MX' END AS section_descr
    FROM school_control_ykt.level_combinations AS lvc
    INNER JOIN iteach_grades_quantitatives.period_calendar AS percal ON lvc.id_level_combination = percal.id_level_combination
    WHERE lvc.id_level_combination = '$id_level_combination' ORDER BY lvc.id_section");

                    while ($row = $query->fetch(PDO::FETCH_OBJ)) {
                        $results[] = $row;
                    }
                }
            }
        }


        return $results;
    }
    public function getPeriodsGroupAcademic($id_academic_area, $id_group, $no_period)
    {
        $results = array();

        $arr_id_level_combination = array();
        $query = $this->conn->query("SELECT pc.*
        FROM school_control_ykt.level_combinations AS lvl_com
        INNER JOIN school_control_ykt.groups AS groups ON groups.id_campus = lvl_com.id_campus AND groups.id_section = lvl_com.id_section
        INNER JOIN school_control_ykt.academic_levels_grade AS ac_le_gra ON groups.id_level_grade = ac_le_gra.id_level_grade
        INNER JOIN school_control_ykt.academic_levels AS ac_le ON ac_le_gra.id_academic_level = ac_le.id_academic_level AND lvl_com.id_academic_level = ac_le.id_academic_level
        INNER JOIN iteach_grades_quantitatives.period_calendar AS pc ON lvl_com.id_level_combination = pc.id_level_combination
        WHERE lvl_com.id_academic_area = $id_academic_area AND groups.id_group = $id_group AND pc.no_period = $no_period");
        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $arr_id_level_combination[] = $row;
        }
        $id_level_combination = '';
        if (!empty($arr_id_level_combination)) {
            $id_level_combination = $arr_id_level_combination[0]->id_level_combination;
        }

        $query = $this->conn->query("SELECT *
        FROM iteach_grades_quantitatives.period_calendar 
        WHERE id_level_combination = '$id_level_combination'");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getAcademicArea($no_teacher)
    {
        $results = array();

        $query = $this->conn->query(" SELECT * FROM ( 
            SELECT sbj.id_academic_area, aca.name_academic_area, rel_coord_aca.no_teacher
            FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
            INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
            INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
            INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON groups.id_level_grade = aclg.id_level_grade
            INNER JOIN school_control_ykt.academic_levels AS acdlvldg ON acdlvldg.id_academic_level  =  aclg.id_academic_level
            INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
            INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
            INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
            INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
            UNION
            SELECT  sbj.id_academic_area, aca.name_academic_area, rel_coord_aca.no_teacher
            FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
            INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
            INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
            INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group 
            INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
            INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
            INNER JOIN school_control_ykt.academic_levels AS acdlvldg ON acdlvldg.id_academic_level  =  aclg.id_academic_level
            INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
             )
            AS u
            WHERE no_teacher = $no_teacher");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getAcademicAreaTeacher($no_teacher)
    {
        $results = array();

        $query = $this->conn->query("SELECT DISTINCT sbj.id_academic_area, aca.name_academic_area
        FROM school_control_ykt.assignments AS asg 
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
        WHERE asg.no_teacher = '$no_teacher'");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getSubjectsLG($id_academic_area, $id_academic_level, $no_teacher)
    {
        $results = array();

        $query = $this->conn->query("SELECT * FROM 

        (SELECT sbj.name_subject, sbj.id_subject, sbj.id_academic_area, rel_coord_aca.no_teacher
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
        INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
        INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON acdlvldg.id_academic_level  =  '$id_academic_level'
        INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
        INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
        UNION
        SELECT  sbj.name_subject, sbj.id_subject, sbj.id_academic_area, rel_coord_aca.no_teacher
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
        INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
        INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group 
        INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
                )
        AS u
        WHERE no_teacher = '$no_teacher' AND id_academic_area = '$id_academic_area' ORDER BY name_subject ASC");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getSubjectsLG2($id_academic_area, $id_academic_level, $no_teacher)
    {
        $results = array();

        $query = $this->conn->query("SELECT * FROM 

        (SELECT sbj.name_subject, sbj.id_subject, sbj.id_academic_area, rel_coord_aca.no_teacher
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
        INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section AND groups.id_level_grade= '$id_academic_level'
        INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON acdlvldg.id_academic_level  =  lvl_com.id_academic_level
        INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
        INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
        UNION
        SELECT  sbj.name_subject, sbj.id_subject, sbj.id_academic_area, rel_coord_aca.no_teacher
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
        INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
        INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group AND gps.id_level_grade= '$id_academic_level'
        INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
                )
        AS u
        WHERE no_teacher = '$no_teacher' AND id_academic_area = '$id_academic_area' ORDER BY name_subject ASC");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getSubjectsLGTeacher($id_academic_area, $id_academic_level, $no_teacher)
    {
        $results = array();

        $query = $this->conn->query("SELECT DISTINCT sbj.name_subject, sbj.id_subject
        FROM school_control_ykt.assignments AS asgm 
        INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group 
        INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade AND aclg.id_academic_level = '$id_academic_level'
        INNER JOIN colaboradores_ykt.colaboradores AS col ON asgm.no_teacher = col.no_colaborador
        WHERE (asgm.no_teacher = '$no_teacher' OR asgm.no_teacher = '$no_teacher') AND sbj.id_academic_area = '$id_academic_area' ORDER BY sbj.name_subject ASC");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getExpectedLearningArchive($id_expected_learning_catalog)
    {
        $results = array();

        $query = $this->conn->query("SELECT *
        FROM expected_learning.expected_learning_archives WHERE id_expected_learning_catalog = '$id_expected_learning_catalog'");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getMutualCriteriaLG($id_academic_area, $id_academic_level, $no_teacher, $id_period, $id_level_grade)
    {
        $results = array();

        $query = $this->conn->query("SELECT * FROM (
            SELECT elc.short_description
            FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
            INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
            INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
            INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON lvl_com.id_academic_level = aclg.id_academic_level AND groups.id_level_grade = aclg.id_level_grade
            INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
            INNER JOIN school_control_ykt.assignments AS asg ON groups.id_group = asg.id_group
            INNER JOIN expected_learning.expected_learning_subindex AS els ON  els.id_level_grade = '$id_level_grade'
            INNER JOIN expected_learning.expected_learning_index AS eli ON eli.id_expected_learning_index = els.id_expected_learning_index
            INNER JOIN expected_learning.relationship_expected_learning_assignments AS rela ON rela.id_expected_learning_index = eli.id_expected_learning_index
            INNER JOIN expected_learning.expected_learning_catalog AS elc ON els.id_expected_learning_subindex = elc.id_expected_learning_subindex
            INNER JOIN expected_learning.expected_learning_catalog as t2 ON elc.short_description = t2.short_description
            WHERE els.id_period_calendar = '$id_period' and rel_coord_aca.no_teacher = '$no_teacher' 
            and rela.id_assignment = asg.id_assignment and t2.id_expected_learning_subindex != elc.id_expected_learning_subindex
            
            UNION
            
            SELECT  elc.short_description
            FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
            INNER JOIN school_control_ykt.assignments AS asg ON rel_coord_aca.coordinators_group_id = asg.coordinators_group_id
            INNER JOIN school_control_ykt.groups AS gps ON gps.id_level_grade = '$id_level_grade'
            INNER JOIN expected_learning.expected_learning_subindex AS els ON  els.id_level_grade = '$id_level_grade'
            INNER JOIN expected_learning.expected_learning_index AS eli ON eli.id_expected_learning_index = els.id_expected_learning_index
            INNER JOIN expected_learning.relationship_expected_learning_assignments AS rela ON rela.id_expected_learning_index = eli.id_expected_learning_index
            INNER JOIN expected_learning.expected_learning_catalog AS elc ON els.id_expected_learning_subindex = elc.id_expected_learning_subindex
            INNER JOIN expected_learning.expected_learning_catalog as t2 ON elc.short_description = t2.short_description
            WHERE els.id_period_calendar = '$id_period' and rel_coord_aca.no_teacher = '$no_teacher' 
            and rela.id_assignment = asg.id_assignment and t2.id_expected_learning_subindex != elc.id_expected_learning_subindex
             )
            AS u
        ");


        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getMutualCriteriaEP($id_academic_area, $id_academic_level, $no_teacher, $id_period, $id_level_grade)
    {
        $results = array();

        $query = $this->conn->query("SELECT * FROM (
            SELECT es.*, groups.id_level_grade, acdlvldg.id_academic_level, acdlvldg.academic_level, rel_coord_aca.no_teacher, sbj.id_academic_area
            FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
            INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
            INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
            INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON lvl_com.id_academic_level = aclg.id_academic_level AND groups.id_level_grade = aclg.id_level_grade
            INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
            INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
            INNER JOIN iteach_grades_quantitatives.evaluation_plan as ep ON ep.id_assignment = assg.id_assignment AND ep.id_period_calendar = $id_period
            INNER JOIN iteach_grades_quantitatives.evaluation_source AS es ON es.id_evaluation_source = ep.id_evaluation_source
            INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
            INNER JOIN school_control_ykt.academic_levels AS acdlvldg ON acdlvldg.id_academic_level = aclg.id_academic_level
            UNION
            SELECT  es.*, gps.id_level_grade, acdlvldg.id_academic_level, acdlvldg.academic_level, rel_coord_aca.no_teacher, sbj.id_academic_area
            FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
            INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
            INNER JOIN iteach_grades_quantitatives.evaluation_plan as ep ON ep.id_assignment = asgm.id_assignment AND ep.id_period_calendar = $id_period
            INNER JOIN iteach_grades_quantitatives.evaluation_source AS es ON es.id_evaluation_source = ep.id_evaluation_source
            INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group  
            INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
            INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
            INNER JOIN school_control_ykt.academic_levels AS acdlvldg ON acdlvldg.id_academic_level  =  aclg.id_academic_level
             )
            AS u
        
            WHERE no_teacher = $_SESSION[colab] AND id_academic_area = $id_academic_area AND id_level_grade = $id_level_grade AND id_evaluation_source !=1  ");


        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getMutualExistCatalogCoordinator($no_teacher, $id_academic_level)
    {
        $results = array();
        $query = $this->conn->query("SELECT DISTINCT  gps.group_code, gps.id_group
        FROM iteach_academic.relationship_managers_assignments AS rma
        INNER JOIN school_control_ykt.assignments AS asg ON asg.id_assignment = rma.id_assignment
        INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = asg.id_group
        INNER JOIN expected_learning.expected_learning_subindex AS els ON els.id_level_grade = gps.id_level_grade
        INNER JOIN school_control_ykt.academic_levels_grade AS alg ON alg.id_level_grade = gps.id_level_grade AND alg.id_academic_level = '$id_academic_level'
        INNER JOIN expected_learning.expected_learning_index AS eli ON eli.id_expected_learning_index = els.id_expected_learning_index
        INNER JOIN expected_learning.relationship_expected_learning_assignments AS rela ON rela.id_expected_learning_index = eli.id_expected_learning_index
        INNER JOIN colaboradores_ykt.colaboradores AS colab ON colab.no_colaborador = eli.no_teacher_created
        WHERE rma.no_teacher = '$no_teacher' AND rela.id_assignment = asg.id_assignment");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getSubindexByLevelGrade($no_teacher, $id_academic_level, $id_level_grade, $no_period)
    {
        $results = array();
        $query = $this->conn->query("SELECT * FROM (SELECT els.level_grade_write, els.id_expected_learning_subindex, groups.letter, groups.group_code, groups.id_section, sbj.name_subject,
        CONCAT(colab_materia.nombres_colaborador, ' ', colab_materia.apellido_paterno_colaborador ,' ', colab_materia.apellido_materno_colaborador) AS teacher_assignment,
        groups.id_group, rel_coord_aca.no_teacher, assg.id_assignment, els.id_period_calendar
            FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
            INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
            INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
            INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON groups.id_level_grade = '$id_level_grade'
            INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group AND print_school_report_card = 1 AND assignment_active = 1
            INNER JOIN colaboradores_ykt.colaboradores AS colab_materia ON colab_materia.no_colaborador = assg.no_teacher
            INNER JOIN colaboradores_ykt.colaboradores AS colab ON assg.no_teacher = colab.no_colaborador
            INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area

            INNER JOIN expected_learning.expected_learning_subindex AS els ON els.id_level_grade = groups.id_level_grade AND els.no_period = '$no_period'
            INNER JOIN expected_learning.expected_learning_index AS eli ON eli.id_expected_learning_index = els.id_expected_learning_index
            INNER JOIN expected_learning.relationship_expected_learning_assignments AS rela ON rela.id_expected_learning_index = eli.id_expected_learning_index AND rela.id_assignment = assg.id_assignment

            UNION
            SELECT els.level_grade_write, els.id_expected_learning_subindex, gps.letter, gps.group_code, gps.id_section, sbj.name_subject,
            CONCAT(colab.nombres_colaborador, ' ', colab.apellido_paterno_colaborador ,' ', colab.apellido_materno_colaborador) AS teacher_assignment,
            gps.id_group, rel_coord_aca.no_teacher, asgm.id_assignment, els.id_period_calendar
            FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
            INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id AND print_school_report_card = 1 AND assignment_active = 1
            INNER JOIN colaboradores_ykt.colaboradores AS colab ON asgm.no_teacher = colab.no_colaborador
            INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject
            INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group AND gps.id_level_grade  = '$id_level_grade' 
            INNER JOIN school_control_ykt.academic_levels AS acdlvldg ON acdlvldg.id_academic_level  = '$id_level_grade'

            INNER JOIN expected_learning.expected_learning_subindex AS els ON els.id_level_grade = gps.id_level_grade
            INNER JOIN expected_learning.expected_learning_index AS eli ON eli.id_expected_learning_index = els.id_expected_learning_index AND els.id_period_calendar = '$no_period'
            INNER JOIN expected_learning.relationship_expected_learning_assignments AS rela ON rela.id_expected_learning_index = eli.id_expected_learning_index AND rela.id_assignment = asgm.id_assignment
            )
            AS u
            WHERE no_teacher = $no_teacher
            ORDER BY group_code, name_subject
        ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getSubindexByLevelGradePROGR($no_teacher, $id_academic_level, $id_level_grade, $no_period, $id_period)
    {
        $results = array();
        $query = $this->conn->query("SELECT * FROM (SELECT els.level_grade_write, els.id_expected_learning_subindex, groups.letter, groups.group_code, groups.id_section, sbj.name_subject,
        CONCAT(colab_materia.nombres_colaborador, ' ', colab_materia.apellido_paterno_colaborador ,' ', colab_materia.apellido_materno_colaborador) AS teacher_assignment,
        groups.id_group, rel_coord_aca.no_teacher, assg.id_assignment, els.id_period_calendar
            FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
            INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
            INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
            INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON groups.id_level_grade = '$id_level_grade'
            INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group AND print_school_report_card = 1 AND assignment_active = 1
            INNER JOIN colaboradores_ykt.colaboradores AS colab_materia ON colab_materia.no_colaborador = assg.no_teacher
            INNER JOIN colaboradores_ykt.colaboradores AS colab ON assg.no_teacher = colab.no_colaborador
            INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area

            INNER JOIN expected_learning.expected_learning_subindex AS els ON els.id_level_grade = groups.id_level_grade AND els.no_period = '$no_period'
            INNER JOIN expected_learning.expected_learning_index AS eli ON eli.id_expected_learning_index = els.id_expected_learning_index
            INNER JOIN expected_learning.relationship_expected_learning_assignments AS rela ON rela.id_expected_learning_index = eli.id_expected_learning_index AND rela.id_assignment = assg.id_assignment

            UNION
            SELECT els.level_grade_write, els.id_expected_learning_subindex, gps.letter, gps.group_code, gps.id_section, sbj.name_subject,
            CONCAT(colab.nombres_colaborador, ' ', colab.apellido_paterno_colaborador ,' ', colab.apellido_materno_colaborador) AS teacher_assignment,
            gps.id_group, rel_coord_aca.no_teacher, asgm.id_assignment, els.id_period_calendar
            FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
            INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id AND print_school_report_card = 1 AND assignment_active = 1
            INNER JOIN colaboradores_ykt.colaboradores AS colab ON asgm.no_teacher = colab.no_colaborador
            INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject
            INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group AND gps.id_level_grade  = '$id_level_grade' 
            INNER JOIN school_control_ykt.academic_levels AS acdlvldg ON acdlvldg.id_academic_level  = '$id_academic_level'

            INNER JOIN expected_learning.expected_learning_subindex AS els ON els.id_level_grade = gps.id_level_grade AND els.id_period_calendar = '$id_period'
            INNER JOIN expected_learning.expected_learning_index AS eli ON eli.id_expected_learning_index = els.id_expected_learning_index 
            INNER JOIN expected_learning.relationship_expected_learning_assignments AS rela ON rela.id_expected_learning_index = eli.id_expected_learning_index AND rela.id_assignment = asgm.id_assignment
            )
            AS u
            WHERE no_teacher = $no_teacher
            ORDER BY group_code, name_subject
        ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getMutualCriteriaAverages($no_teacher, $id_assignment, $criteria)
    {
        $results = array();
        $query = $this->conn->query("SELECT learning_average 
        FROM expected_learning.catalog_learning_averages AS cla
        INNER JOIN expected_learning.expected_learning_catalog AS elc ON elc.id_expected_learning_catalog = cla.id_expected_learning_catalog
        INNER JOIN expected_learning.expected_learning_subindex AS els ON els.id_expected_learning_subindex = elc.id_expected_learning_subindex
        INNER JOIN expected_learning.expected_learning_index AS eli ON eli.id_expected_learning_index = els.id_expected_learning_index
        INNER JOIN expected_learning.relationship_expected_learning_assignments AS rela ON rela.id_expected_learning_index = eli.id_expected_learning_index
        WHERE rela.id_assignment = '$id_assignment' AND elc.short_description = '$criteria'");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getMutualExistCatalogCoordinatorEP($no_teacher, $id_academic_level, $criteria, $period)
    {

        $results = array();
        $query = $this->conn->query("SELECT * FROM ( 
            SELECT rel_coord_aca.no_teacher, groups.group_code, groups.id_group, aclg.id_level_grade
            FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
            INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
            INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
            INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON groups.id_level_grade = aclg.id_level_grade
            INNER JOIN school_control_ykt.academic_levels AS acdlvldg ON acdlvldg.id_academic_level  =  aclg.id_academic_level
            INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
            INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
            INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
            INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
            INNER JOIN iteach_grades_quantitatives.evaluation_plan as ep  ON ep.id_assignment = assg.id_assignment AND ep.id_period_calendar = $period AND ep.id_evaluation_source = $criteria
            UNION
            SELECT  rel_coord_aca.no_teacher, gps.group_code, gps.id_group, aclg.id_level_grade
            FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
            INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
            INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
            INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group 
            INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
            INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
            INNER JOIN school_control_ykt.academic_levels AS acdlvldg ON acdlvldg.id_academic_level  =  aclg.id_academic_level
            INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
            INNER JOIN iteach_grades_quantitatives.evaluation_plan as ep  ON ep.id_assignment = asgm.id_assignment AND ep.id_period_calendar = $period AND ep.id_evaluation_source = $criteria
             )
            AS u
            WHERE no_teacher = $no_teacher AND id_level_grade = $id_academic_level");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getMutualCriteriaAveragesEP($no_teacher, $id_assignment, $criteria, $period)
    {
        $results = array();
        $query = $this->conn->query("SELECT AVG(gec.grade_evaluation_criteria_teacher) AS learning_average
        FROM iteach_grades_quantitatives.evaluation_plan as ep
        INNER JOIN iteach_grades_quantitatives.grades_evaluation_criteria AS gec
             ON gec.id_evaluation_plan = ep.id_evaluation_plan AND gec.grade_evaluation_criteria_teacher IS NOT NULL
        WHERE ep.id_assignment = '$id_assignment' AND ep.id_period_calendar = $period

        AND ep.id_evaluation_source = $criteria");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getCatalogFromSubindex($id_subindex)
    {
        $results = array();
        $query = $this->conn->query("SELECT * FROM expected_learning.expected_learning_catalog AS elc
        WHERE elc.id_expected_learning_subindex = $id_subindex");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getCatalogQualifiquied($id_expected_learning_catalog)
    {
        $results = array();
        $query = $this->conn->query("SELECT eld.* FROM expected_learning.expected_learning_catalog AS elc
        INNER JOIN expected_learning.expected_learning_deliverables AS eld ON eld.id_expected_learning_catalog = elc.id_expected_learning_catalog
        WHERE elc.id_expected_learning_catalog = $id_expected_learning_catalog AND eld.teacher_evidence_quailification IS NOT NULL");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getNotQualifiquiedComment($id_assignment, $id_period_calendar)
    {
        $results = array();
        $query = $this->conn->query("SELECT * FROM expected_learning.comment_period_not_qualified
        WHERE id_assignment = '$id_assignment' AND id_period_calendar = $id_period_calendar");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }


    public function getGroupAVGAE($id_assignment, $id_period_calendar)
    {
        $results = array();
        $query = $this->conn->query("SELECT group_average FROM expected_learning.expected_learning_average_group
        WHERE  id_assignment = $id_assignment AND id_period_calendar = $id_period_calendar");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getListStudentsByIDgroup($group_id)
    {

        $results = array();

        $query = $this->conn->query("
            SELECT student.id_student, student.student_code, CONCAT(student.lastname , ' ', student.name) AS student_name
            FROM school_control_ykt.students AS student
            INNER JOIN school_control_ykt.inscriptions AS inscription ON student.id_student = inscription.id_student
            WHERE inscription.id_group = '$group_id' AND student.status = 1
            ORDER BY student.lastname
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getMutualCriteriaAveragesEPStudent($no_teacher, $id_assignment, $criteria, $period, $id_student)
    {
        $results = array();
        $query = $this->conn->query("SELECT gec.grade_evaluation_criteria_teacher
        FROM iteach_grades_quantitatives.evaluation_plan as ep
        INNER JOIN iteach_grades_quantitatives.grades_evaluation_criteria AS gec
             ON gec.id_evaluation_plan = ep.id_evaluation_plan AND gec.grade_evaluation_criteria_teacher IS NOT NULL
        INNER JOIN iteach_grades_quantitatives.final_grades_assignment AS fga ON fga.id_final_grade = gec.id_final_grade AND fga.id_student = '$id_student'
        WHERE ep.id_assignment = '$id_assignment' AND ep.id_period_calendar = $period
        AND ep.id_evaluation_source = $criteria");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getStudentAverageEP($id_student, $period)
    {
        $results = array();

        $query = $this->conn->query("
            SELECT SUM(gp.grade_period) AS average
            FROM iteach_grades_quantitatives.`final_grades_assignment`  AS fga
            INNER JOIN iteach_grades_quantitatives.grades_period AS gp ON fga.id_final_grade = gp.id_final_grade
            INNER JOIN school_control_ykt.students AS std ON std.student_code = fga.student_code
            WHERE std.id_student = '$id_student' AND gp.id_period_calendar = $period");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
    public function getAssignmentsGroupCriteria($id_assignment, $criteria, $id_period)
    {
        $results = array();

        $query = $this->conn->query(" SELECT *
            FROM iteach_grades_quantitatives.`evaluation_plan` 
            WHERE id_assignment = '$id_assignment' AND id_evaluation_source = $criteria AND id_period_calendar = $id_period");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }
}
