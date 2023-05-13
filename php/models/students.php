<?php
class Students extends data_conn
{

    private $conn;
    public function __construct()
    {
        $this->conn = $this->dbConn();
    }

    public function getListStudentsByAssignment($id_assignment)
    {

        $results = array();

        $query = $this->conn->query("
            SELECT student.id_student, student.student_code, CONCAT(student.lastname , ' ', student.name) AS name_student
            FROM school_control_ykt.students AS student
            INNER JOIN school_control_ykt.inscriptions AS inscription ON student.id_student = inscription.id_student
            INNER JOIN school_control_ykt.assignments AS assignment ON inscription.id_group = assignment.id_group
            WHERE assignment.id_assignment = '$id_assignment' AND student.status = 1
            ORDER BY student.lastname
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getListStudentsByIDgroup($group_id)
    {

        $results = array();

        $query = $this->conn->query("
            SELECT student.id_student, student.student_code, UPPER(CONCAT(student.lastname , ' ', student.name)) AS student_name
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

    public function getInfoStudent($id_student)
    {

        $results = array();

        $query = $this->conn->query("
            SELECT student.id_student, student.student_code, CONCAT(student.lastname,' ', student.name) AS student_name, student.group_id, groups.*
            FROM school_control_ykt.students AS student
            INNER JOIN school_control_ykt.groups AS groups ON student.group_id = groups.id_group
            WHERE student.id_student = '$id_student'
            ");

        if ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results = $row;
        }

        return $results;
    }

    public function get_all_students()
    {

        $sql = "SELECT t1.id_student,t1.name,t1.status,t1.lastname,t2.group_code,t1.gender,t1.student_code,  
        t2.letter,t3.campus_name,t4.degree,CONCAT(t5.name,' ',t5.lastname) as father_name,CONCAT(t6.name,' ',t6.lastname) as mother_name,
        t5.cell_phone as father_phone,t6.cell_phone as mother_phone 
        FROM 
        school_control_ykt.students as t1 
        INNER JOIN 
        school_control_ykt.groups as t2 ON 
        t1.group_id = t2.id_group 
        INNER JOIN 
        school_control_ykt.campus as t3 ON 
        t2.id_campus = t3.id_campus 
        INNER JOIN 
        school_control_ykt.academic_levels_grade as t4 ON 
        t2.id_level_grade = t4.id_level_grade 
        INNER JOIN
        families_ykt.fathers as t5 ON 
        t1.id_family  = t5.id_family
        INNER JOIN
        families_ykt.mothers as t6 ON 
        t1.id_family  = t6.id_family
        WHERE t1.status = 1;";
        $res = $this->conn->query($sql);
        $res = $this->dbConn()->query($sql);
        $students = [];
        if ($res) {
            while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                $students[] = $row;
            }
        }
        return $students;
    }
    public function get_all_students_bangueolo()
    {
        $students = $this->get_students_by_id_campus(1);
        $degrees = $this->get_degrees_into_campus(1);
        return array('students' => $students, 'degrees' => $degrees);
    }
    public function get_students_bangueolo($id_academic_level, $id_campus)
    {
        $students = $this->get_students_of_campus_and_grade($id_academic_level, $id_campus);
        return $students;
    }
    public function get_students_of_campus_and_grade($id_academic_level, $id_campus)
    {
        $students = [];
        $sql = "SELECT t1.id_student,t1.name,t1.status,t1.lastname,t1.curp,t2.group_code,t1.gender,t1.student_code,  
        t2.letter,t3.campus_name,t4.degree,CONCAT(t5.name,' ',t5.lastname) as father_name,CONCAT(t6.name,' ',t6.lastname) as mother_name,
        t5.cell_phone as father_phone,t6.cell_phone as mother_phone,t5.mail as father_mail,t6.mail as mother_mail  
        FROM 
        school_control_ykt.students as t1 
        INNER JOIN 
        school_control_ykt.groups as t2 ON 
        t1.group_id = t2.id_group 
        INNER JOIN 
        school_control_ykt.campus as t3 ON 
        t2.id_campus = t3.id_campus 
        INNER JOIN 
        school_control_ykt.academic_levels_grade as t4 ON 
        t2.id_level_grade = t4.id_level_grade 
        INNER JOIN
        families_ykt.fathers as t5 ON 
        t1.id_family  = t5.id_family
        INNER JOIN
        families_ykt.mothers as t6 ON 
        t1.id_family  = t6.id_family
        WHERE t1.status = 1 
        AND 
        t3.id_campus = $id_campus
        AND 
        t4.id_academic_level = $id_academic_level 
        ;";
        $res = $this->dbConn()->query($sql);
        $students = [];
        if ($res) {
            while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                $students[] = $row;
            }
        }

        return $students;
    }
    public function get_degrees_into_campus($id_campus)
    {
        $sql = "SELECT DISTINCT acl.academic_level, camp.id_campus 
        FROM school_control_ykt.campus as camp 
        INNER JOIN school_control_ykt.groups AS gps ON gps.id_campus = camp.id_campus 
        INNER JOIN school_control_ykt.academic_levels_grade as alg ON gps.id_level_grade = alg.id_level_grade  
        INNER JOIN school_control_ykt.academic_levels as acl ON acl.id_academic_level = alg.id_academic_level 
        where camp.id_campus =$id_campus;";
        $res = $this->dbConn()->query($sql);
        $degrees = [];
        if ($res) {
            while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                $degrees[] = $row;
            }
        }
        return $degrees;
    }
    public function get_students_by_id_campus($id_campus)
    {
        $sql = "SELECT t1.id_student,t1.name,t1.status,t1.lastname,t1.curp,t2.group_code,t1.gender,t1.student_code,  
        t2.letter,t3.campus_name,t4.degree,CONCAT(t5.name,' ',t5.lastname) as father_name,CONCAT(t6.name,' ',t6.lastname) as mother_name,
        t5.cell_phone as father_phone,t6.cell_phone as mother_phone,t5.mail as father_mail,t6.mail as mother_mail  
        FROM 
        school_control_ykt.students as t1 
        INNER JOIN 
        school_control_ykt.groups as t2 ON 
        t1.group_id = t2.id_group 
        INNER JOIN 
        school_control_ykt.campus as t3 ON 
        t2.id_campus = t3.id_campus 
        INNER JOIN 
        school_control_ykt.academic_levels_grade as t4 ON 
        t2.id_level_grade = t4.id_level_grade 
        INNER JOIN
        families_ykt.fathers as t5 ON 
        t1.id_family  = t5.id_family
        INNER JOIN
        families_ykt.mothers as t6 ON 
        t1.id_family  = t6.id_family
        WHERE t1.status = 1 AND t3.id_campus = $id_campus;";
        $res = $this->dbConn()->query($sql);
        $students = [];
        if ($res) {
            while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                $students[] = $row;
            }
        }
        return $students;
    }
    public function get_all_students_lafontaine()
    {
        $students = $this->get_students_by_id_campus(2);
        $degrees = $this->get_degrees_into_campus(2);
        return array('students' => $students, 'degrees' => $degrees);
    }
    public function get_all_students_interlomas()
    {
        $students = $this->get_students_by_id_campus(3);
        $degrees = $this->get_degrees_into_campus(3);
        return array('students' => $students, 'degrees' => $degrees);
    }
    public function get_all_students_tecamachalco()
    {
        $students = $this->get_students_by_id_campus(4);
        $degrees = $this->get_degrees_into_campus(4);
        return array('students' => $students, 'degrees' => $degrees);
    }
    public function get_expedient_student()
    {
        $sql = "SELECT t2.*,t3.*,t4.* FROM 
        prospects.students as t1 
        LEFT JOIN 
        prospects.medical_info as t2 ON t2.id_student = t1.id_student 
        LEFT JOIN 
        prospects.pediatry as t3 ON t3.id_student = t1.id_student 
        LEFT JOIN 
        prospects.psychopedagogical as t5 ON t5.id_student = t1.id_student 
        LEFT JOIN 
        prospects.psico_studies as t4 ON t4.id_psychopedagogical = t5.id_psychopedagogical 
        WHERE 
        t1.id_in_students_ykt = $_GET[id_student];";
        $res = $this->dbConn()->query($sql);
        $data = [];
        if ($res) {
            while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
        }
        return $data;
    }
    public function get_consultas_student()
    {
        $sql = "SELECT t1.*,
        CONCAT(t2.nombres_colaborador,' ',t2.apellido_paterno_colaborador,' ',t2.apellido_materno_colaborador) as nombre_c,
        t3.morbidity 
        FROM 
        school_control_ykt.consultas as t1 
        INNER JOIN 
        colaboradores_ykt.colaboradores as t2 ON 
        t1.no_colab = t2.no_colaborador 
        INNER JOIN 
        school_control_ykt.morbidities as t3 ON 
        t1.id_morbidity = t3.id_morbidity 
        WHERE 
        t1.id_student = $_GET[id_student] 
        ORDER BY `t1`.`fecha` DESC
        ";
        $res = $this->dbConn()->query($sql);
        $data = [];
        if ($res) {
            while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
        }
        return $data;
    }
    public function get_enfermedades_student()
    {
        $sql = "SELECT * FROM school_control_ykt.enfermedades_estudiantes WHERE id_student = $_GET[id_student]";
        $res = $this->dbConn()->query($sql);
        $data = [];
        if ($res) {
            while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
        }
        return $data;
    }
    public function get_alergias_student()
    {
        $sql = "SELECT * FROM school_control_ykt.alergias_estudiantes WHERE id_student = $_GET[id_student]";
        $res = $this->dbConn()->query($sql);
        $data = [];
        if ($res) {
            while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
        }
        return $data;
    }
    public function addAlergia()
    {
        $data = $this->build_query_insert($_POST['alergia']);
        $sql = "INSERT INTO school_control_ykt.alergias_estudiantes($data[columns]) VALUES($data[values])";
        $this->status = $this->dbConn()->query($sql) !== false;
        $sql = str_replace("'", '"', $sql);
        $this->audit($_SESSION["colab"], "school_control_ykt.alergias_estudiantes", "all", 0, "addAlergia $sql");
        return $this;
    }
    public function addEnfermedad()
    {
        $data = $this->build_query_insert($_POST['enfermedad']);
        $sql = "INSERT INTO school_control_ykt.enfermedades_estudiantes($data[columns]) VALUES($data[values])";
        $this->status = $this->dbConn()->query($sql) !== false;
        $sql = str_replace("'", '"', $sql);
        $this->audit($_SESSION["colab"], "school_control_ykt.enfermedades_estudiantes", "all", 0, "addEnfermedad $sql");
        return $this;
    }
    public function close_consulta()
    {
        $_POST['consulta']['seguimiento'] = 0;
        $_POST['consulta']['fecha_actualizacion'] = date('Y-m-d H:i:s');
        $id = $_POST['consulta']['id_consulta'];
        unset($_POST['consulta']['type']);
        $update = $this->build_query_update($_POST['consulta']);
        $sql = "UPDATE school_control_ykt.consultas SET $update WHERE id_consulta = $id";
        $this->status = $this->dbConn()->query($sql) !== false;
        $sql = str_replace("'", '"', $sql);
        if (!isset($_SESSION)) session_start();
        $this->audit($_SESSION["colab"], "school_control_ykt.consultas", "$update", 0, "close_consulta $sql");
        return $this;
    }
    public function audit($no_colaborador, $table, $column, $id_column, $action)
    {
        $sql = "INSERT INTO 
        audits.enfermeria(`no_colaborador`, `table_`, `column_`, `id_column`, `action_`) 
        VALUES 
        ($no_colaborador,'$table','$column',$id_column,'$action')";
        $this->dbConn()->query($sql);
    }
    public function addConsulta()
    {
        if (isset($_POST['consulta']['seguimiento'])) {
            $_POST['consulta']['seguimiento'] = 1;
        }
        if (!empty($_FILES['file_input']["name"][0])) {
            $_POST['adjuntos'] = sizeof($_FILES['file_input']["name"]);
        }
        if (isset($_POST['registered_by'])) {
            $_POST['no_colab'] = $_POST['registered_by'];
        }
        unset($_POST['mod'], $_POST['registered_by']);
        if (!intval($_POST["id_morbidity"])) $_POST["id_morbidity"] = 1;
        $data = $this->build_query_insert($_POST);
        $sql = "INSERT INTO school_control_ykt.consultas($data[columns]) VALUES($data[values])";
        $this->status = $this->dbConn()->query($sql) !== false;
        $sql = str_replace("'", '"', $sql);
        $this->audit($_POST["no_colab"], "school_control_ykt.consultas", "all", 0, "addConsulta $sql");
        if ($this->status && !empty($_FILES['file_input']['name'][0])) {
            $this->verify_directory();
            // if (!file_exists("../../documentos_consultas/$curp")) {
            //     mkdir("../../documentos_consultas/$curp", 0777, true);
            // }
            // if (!file_exists("../../documentos_consultas/$curp/$_POST[fecha]")) {
            //     mkdir("../../documentos_consultas/$curp/$_POST[fecha]", 0777, true);
            // }
            $files_name = $_FILES['file_input']["name"];
            $files_tmp = $_FILES['file_input']["tmp_name"];
            $id_consulta = $this->get_last_consulta();
            $files_array = [];
            foreach ($files_name as $i => $name) {
                $temp = explode(".", $name);
                $ext = end($temp);
                $id = $i + 1;
                $files_array[] = ['id_consulta' => $id_consulta, 'name_file' => "evidencia$id-consulta_$id_consulta.$ext"];
                move_uploaded_file($files_tmp[$i], "../../documentos_consultas/evidencia$id-consulta_$id_consulta.$ext");
            }
            foreach ($files_array as $f) {
                $sql = "INSERT INTO school_control_ykt.medic_files(`id_consulta`, `name_file`) VALUES ('$f[id_consulta]','$f[name_file]')";
                $this->dbConn()->query($sql);
            }
        }
        return $this;
    }
    public function verify_directory()
    {
        if (!file_exists('../../documentos_consultas')) {
            mkdir('../../documentos_consultas', 0777);
        }
    }
    public function add_new_file()
    {
        $this->verify_directory();
        $files_name = $_FILES['new_file']["name"];
        $files_tmp = $_FILES['new_file']["tmp_name"];
        $files_array = [];
        $id = $_POST['start'] + 1;
        $id_consulta = $_POST['id_consulta'];
        foreach ($files_name as $i => $name) {
            $temp = explode(".", $name);
            $ext = end($temp);
            $files_array[] = ['id_consulta' => $id_consulta, 'name_file' => "evidencia$id-consulta_$id_consulta.$ext"];
            move_uploaded_file($files_tmp[$i], "../../documentos_consultas/evidencia$id-consulta_$id_consulta.$ext");
            $id++;
        }
        foreach ($files_array as $f) {
            $sql = "INSERT INTO school_control_ykt.medic_files(`id_consulta`, `name_file`) VALUES ('$f[id_consulta]','$f[name_file]')";
            $res = $this->dbConn()->query($sql);
            $sql = str_replace("'", '"', $sql);
            if (!isset($_SESSION)) session_start();
            $this->audit($_SESSION["colab"], "school_control_ykt.medic_files", "all", 0, "add_new_file $sql");
        }
        $this->status = $res !== false;
        $this->id_consulta = $id_consulta;
        return $this;
    }
    public function update_consulta()
    {
        $update = $this->build_query_update($_POST['form']);
        $sql = "UPDATE school_control_ykt.consultas SET $update WHERE id_consulta = $_POST[id_consulta]";
        $this->id_consulta = $_POST['id_consulta'];
        $this->status = $this->dbConn()->query($sql) !== false;
        $sql = str_replace("'", '"', $sql);
        if (!isset($_SESSION)) session_start();
        $this->audit($_SESSION["colab"], "school_control_ykt.consultas", "$update", 0, "update_consulta $sql");
        return $this;
    }
    public function update_file()
    {
        $name = explode(".", $_POST['old_name']);
        $temp = explode(".", $_FILES['update_file']['name']);
        $ext = end($temp);
        unlink("../../documentos_consultas/$_POST[old_name]");
        move_uploaded_file($_FILES['update_file']['tmp_name'], "../../documentos_consultas/$name[0].$ext");
        $sql = "UPDATE school_control_ykt.medic_files SET `name_file`= '$name[0].$ext' WHERE id_file =$_POST[id_file]";
        $this->status = $this->dbConn()->query($sql) !== false;
        $sql = str_replace("'", '"', $sql);
        if (!isset($_SESSION)) session_start();
        $this->audit($_SESSION["colab"], "school_control_ykt.medic_files", "name_file", 0, "update_file $sql");
        $this->id_consulta = $_POST['id_consulta'];
        return $this;
    }
    public function delete_file()
    {
        unlink("../../documentos_consultas/$_POST[old_name]");
        $sql = "DELETE FROM school_control_ykt.medic_files WHERE id_file =$_POST[id_file]";
        $this->status = $this->dbConn()->query($sql) !== false;
        $sql = str_replace("'", '"', $sql);
        if (!isset($_SESSION)) session_start();
        $this->audit($_SESSION["colab"], "school_control_ykt.medic_files", "name_file", 0, "delete_file $sql");
        $this->id_consulta = $_POST['id_consulta'];
        return $this;
    }
    public function get_last_consulta()
    {
        $sql = "SELECT id_consulta FROM school_control_ykt.consultas ORDER BY id_consulta DESC LIMIT 1 ";
        $res = $this->dbConn()->query($sql);
        $data = "";
        if ($res) {
            while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                $data = $row["id_consulta"];
            }
        }
        return $data;
    }
    public function get_details()
    {
        $data = $_POST['obj'];
        $files = $this->get_files_of_consulta($data['id_consulta']);
        $consulta = $this->get_consulta_info($data['id_consulta']);
        $morbidities = $this->get_morbidities();
        if (!empty($consulta)) {
            $this->status = true;
        } else {
            $this->status = false;
        }
        $this->files = $files;
        $this->consulta = $consulta;
        $this->morbidities = $morbidities;
        return $this;
    }
    public function get_morbidities()
    {
        $sql = "SELECT * FROM school_control_ykt.morbidities;";
        $res = $this->dbConn()->query($sql);
        $data = [];
        if ($res) {
            while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
        }
        return $data;
    }
    public function get_consulta_info($id_consulta)
    {
        $sql = "SELECT * FROM school_control_ykt.consultas as t1
        INNER JOIN 
        school_control_ykt.morbidities as t2 ON 
        t1.id_morbidity = t2.id_morbidity 
        WHERE id_consulta = $id_consulta";
        $res = $this->dbConn()->query($sql);
        $data = [];
        if ($res) {
            while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                $data = $row;
            }
        }
        return $data;
    }
    public function get_files_of_consulta($id_consulta)
    {
        $sql = "SELECT * FROM school_control_ykt.medic_files WHERE id_consulta = $id_consulta";
        $res = $this->dbConn()->query($sql);
        $data = [];
        if ($res) {
            while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
        }
        return $data;
    }
    public function get_consultas_pendientes_alumnos()
    {
        $sql = "SELECT t1.*, CONCAT(t2.name,' ',t2.lastname) as nombre,t3.group_code 
        FROM 
        school_control_ykt.consultas as t1 
        INNER JOIN 
        school_control_ykt.students as t2 
        ON 
        t1.id_student = t2.id_student 
        INNER JOIN 
        school_control_ykt.groups as t3 
        ON 
        t2.group_id = t3.id_group 
        WHERE 
        t1.seguimiento = 1;";
        $res = $this->dbConn()->query($sql);
        $data = [];
        if ($res) {
            while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
        }
        return $data;
    }
    public function build_query_update($array)
    {
        $columns = "";
        $i = true;
        foreach ($array as $key => $value) {
            if ($i) {
                $columns .= $key . '=' . '"' . $value . '"';
                $i = false;
            } else {
                $columns .= ',' . $key . '=' . '"' . $value . '"';
            }
        }
        return $columns;
    }
    public function build_query_insert($array)
    {
        $i = false;
        $columns = "";
        $values = "";
        foreach ($array as $key => $value) {
            if (!$i) {
                $columns = $key;
                $values  = "'" . $value . "'";
                $i        = true;
            } else {
                $columns .= ", " . $key;
                $values .= ", '" . $value . "'";
            }
        }
        return array('columns' => $columns, 'values' => $values);
    }
    public function get_consultas_alumnos()
    {
        $sql = "SELECT t1.*,t2.student_code,
        CONCAT(t2.name,' ',t2.lastname) as nombre,t3.group_code,t4.morbidity,t5.campus_name 
        FROM 
        school_control_ykt.consultas as t1 
        INNER JOIN 
        school_control_ykt.students as t2 
        ON t1.id_student = t2.id_student 
        INNER JOIN 
        school_control_ykt.groups as t3 
        ON t2.group_id = t3.id_group 
        INNER JOIN 
        school_control_ykt.morbidities as t4 
        ON t1.id_morbidity = t4.id_morbidity 
        INNER JOIN 
        school_control_ykt.campus as t5 
        ON t3.id_campus = t5.id_campus 
        ORDER BY fecha DESC;";
        $res = $this->dbConn()->query($sql);
        $consultas = [];
        if ($res) {
            while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                $consultas[] = $row;
            }
        }
        return array(
            "consultas" => $consultas,
            "consultas_by_grade" => $this->consultas_by_sections(" 1 "),
            "consultas_by_gender" => $this->consultas_by_gender(),
            "consultas_by_morbidity" => $this->consultas_by_morbidity()
        );
    }
    public function consultas_by_gender()
    {
        $consultas_varones = [];
        $sql = "SELECT t1.*,t2.name FROM school_control_ykt.consultas as t1 
        INNER JOIN school_control_ykt.students as t2 ON t1.id_student = t2.id_student 
        WHERE t2.gender = 0 GROUP BY `t1`.`id_student`;";
        $res = $this->dbConn()->query($sql);
        if ($res) {
            while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                $consultas_varones[] = $row;
            }
        }
        $consultas_mujeres = [];
        $sql = "SELECT t1.*,t2.name FROM school_control_ykt.consultas as t1 
        INNER JOIN school_control_ykt.students as t2 ON t1.id_student = t2.id_student 
        WHERE t2.gender = 1 GROUP BY `t1`.`id_student`;";
        $res = $this->dbConn()->query($sql);
        if ($res) {
            while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                $consultas_mujeres[] = $row;
            }
        }
        return array(
            "varones" => $consultas_varones,
            "mujeres" => $consultas_mujeres,
        );
    }
    public function consultas_by_morbidity()
    {
        $sql = "SELECT t1.id_morbidity,COUNT(DISTINCT id_student) as n, t2.color_hex,t2.morbidity  
        FROM 
        school_control_ykt.consultas as t1 
        INNER JOIN 
        school_control_ykt.morbidities as t2 
        ON t1.id_morbidity = t2.id_morbidity GROUP BY t1.id_morbidity
        ORDER BY n ASC;";
        $res = $this->dbConn()->query($sql);
        $consultas_by_morbidity = [];
        if ($res) {
            while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                $consultas_by_morbidity[] = $row;
            }
        }
        return $consultas_by_morbidity;
    }
    public function get_consultas_by_grade2()
    {
        $sql = "SELECT 
        IF(
            t2.gender = 1, 
                CONCAT(t4.degree,' | MUJERES'),
                CONCAT(t4.degree,' | HOMBRES')) AS degree,
                COUNT(DISTINCT t2.id_student) as n 
        FROM 
        school_control_ykt.consultas as t1 
        INNER JOIN 
        school_control_ykt.students as t2 ON t1.id_student = t2.id_student 
        INNER JOIN 
        school_control_ykt.groups as t3 ON t2.group_id = t3.id_group 
        INNER JOIN 
        school_control_ykt.academic_levels_grade as t4 ON t3.id_level_grade = t4.id_level_grade 
        GROUP BY t4.id_level_grade,t2.gender 
        ORDER BY `t4`.`id_level_grade` ASC;";
        $res = $this->dbConn()->query($sql);
        $data = [];
        if ($res) {
            while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
        }
        return $data;
    }
    public function get_consultas_by_grade($grade)
    {
        $sql = "SELECT t1.id_student,t4.degree FROM school_control_ykt.consultas as t1 
        INNER JOIN school_control_ykt.students as t2 ON t1.id_student = t2.id_student 
        INNER JOIN school_control_ykt.groups as t3 ON t2.group_id = t3.id_group
        INNER JOIN school_control_ykt.academic_levels_grade as t4 ON t3.id_level_grade = t4.id_level_grade
        WHERE t2.gender = 1 
        AND t4.id_level_grade = $grade 
        GROUP BY `t1`.`id_student`
        ;";
        $res = $this->dbConn()->query($sql);
        $mujeres = [];
        if ($res) {
            while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                $mujeres[] = $row;
            }
        }
        $sql = "SELECT t1.id_student,t4.degree FROM school_control_ykt.consultas as t1 
        INNER JOIN school_control_ykt.students as t2 ON t1.id_student = t2.id_student 
        INNER JOIN school_control_ykt.groups as t3 ON t2.group_id = t3.id_group
        INNER JOIN school_control_ykt.academic_levels_grade as t4 ON t3.id_level_grade = t4.id_level_grade
        WHERE t2.gender = 0 
        AND t4.id_level_grade = $grade 
        GROUP BY `t1`.`id_student`
        ;";
        $res = $this->dbConn()->query($sql);
        $varones = [];
        if ($res) {
            while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                $varones[] = $row;
            }
        }

        return array("mujeres" => $mujeres, "varones" => $varones);
    }
    public function getStudentWithoutGroup($no_teacher)
    {
        $results = array();

        $query = $this->conn->query("
            SELECT DISTINCT std.id_student, std.student_code, CONCAT(std.lastname, ' ', std.name) AS student_name, std.group_id, groups.*, aclg.degree,aclg.id_level_grade, groups.id_campus
            FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
            INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
            INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
            INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON lvl_com.id_academic_level = aclg.id_academic_level AND aclg.id_level_grade = groups.id_level_grade
            INNER JOIN school_control_ykt.students AS std ON groups.id_group = std.group_id
            WHERE rel_coord_aca.no_teacher = $no_teacher AND groups.group_type_id = 4 AND std.status = 1
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function getGroupsByALG($id_level_grade, $id_campus)
    {
        $results = array();

        $query = $this->conn->query("
            SELECT groups.id_group, groups.group_code, CONCAT(col.apellido_paterno_colaborador, ' ', col.apellido_materno_colaborador, ' ', col.nombres_colaborador) AS collaborator_name
            FROM school_control_ykt.groups AS groups
            INNER JOIN colaboradores_ykt.colaboradores AS col ON groups.no_tutor = col.no_colaborador
            WHERE id_level_grade = $id_level_grade AND groups.id_campus = $id_campus AND is_active = 1 AND group_type_id = 1
            ");

        while ($row = $query->fetch(PDO::FETCH_OBJ)) {
            $results[] = $row;
        }

        return $results;
    }

    public function updateGroupStudent($id_student, $id_group)
    {
        $results = null;

        $sql = "UPDATE school_control_ykt.students SET group_id = ? WHERE id_student = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id_group, $id_student]);

        $sql = "INSERT INTO school_control_ykt.inscriptions (id_student, id_group) VALUES (?,?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id_student, $id_group]);

        return $results;
    }
    public function get_all_medic_data()
    {
        return $this->data_in_ytk_table();
    }
    public function data_in_prospect_table()
    {
        $sql = "SELECT t1.id_student,t1.gender,t1.birthday,t1.age,t1.student_code,t1.id_degree,t1.id_campus,t1.id_group_code,t1.id_in_students_ykt,
        t2.id_medical,t2.student_height,
        t2.student_weight,t2.student_padeciment,t2.student_padeciment_text,t2.student_vacuns,t2.student_tratamient,t2.student_tratamient_text,t2.student_sport,t2.student_sport_text,t2.student_fall,t2.student_fall_text,t2.student_hospitalice,t2.student_hospitalice_text,t2.student_sick,t2.student_sick_text,t2.student_condition,t2.student_condition_text,t2.student_heart,t2.student_respiratory_disease,t2.student_respiratory_disease_text,t2.student_vision_problems,t2.student_seizures,t2.student_diabetes,t2.student_medicine_text,t2.student_magraine,t2.student_hepatitis,t2.student_epilepsy,
        t3.id_pediatry,t3.full_name,t3.cell_phone,t3.phone_office,t3.main_street_office,t3.between_streets,t3.outdoor_numer,
        t4.id_psychopedagogical,t4.recourse_year,t4.social_performance,t4.schoolar_performance,t4.behavior_performance,t4.have_had_previous_treatments
        FROM 
        prospects.students AS t1 LEFT JOIN 
        prospects.medical_info AS t2 ON
        t1.id_student = t2.id_student 
        LEFT JOIN 
        prospects.pediatry AS t3 ON
        t1.id_student = t3.id_student
        LEFT JOIN 
        prospects.psychopedagogical AS t4 ON
        t1.id_student = t4.id_student
        WHERE t1.id_in_students_ykt = $_GET[id_student];";
        $res = $this->dbConn()->query($sql);
        $data = [];
        if ($res) {
            while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                $data = $row;
            }
        }
        return $data;
    }
    public function data_in_ytk_table()
    {
        $sql = "SELECT * 
        FROM 
        school_control_ykt.health_data_students 
        WHERE 
        id_student = $_GET[id_student]";
        $res = $this->dbConn()->query($sql);
        $data = [];
        if ($res) {
            while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                $data = $row;
            }
        }
        return $data;
    }
    public function addMedicina()
    {
        $medicina = $_POST['medicina'];
        $data = $this->search_medicines($medicina['id_student']);
        $data = trim($data);
        if (empty($data)) $data = "";
        else $data = "$data,";
        $data = "$data $medicina[medicina]";

        $sql = "UPDATE 
        prospects.medical_info as t1 
        INNER JOIN 
        prospects.students as t2 ON 
        t1.id_student = t2.id_student 
        SET 
        t1.student_medicine_text = '$data  '
        WHERE t2.id_in_students_ykt = $medicina[id_student];";
        $this->status = $this->dbConn()->query($sql);

        $sql = "UPDATE 
        school_control_ykt.health_data_students  
        SET 
        student_medicine_text = '$data  '
        WHERE id_student = $medicina[id_student];";
        $this->status = $this->dbConn()->query($sql);
        $sql = str_replace("'", '"', $sql);
        if (!isset($_SESSION)) session_start();
        $this->audit($_SESSION["colab"], "school_control_ykt.consultas", "student_medicine_text", 0, "addMedicina $sql");
        return $this;
    }
    public function search_medicines($id)
    {
        $sql = "SELECT 
        t1.student_medicine_text 
        FROM 
        prospects.medical_info as t1 
        INNER JOIN 
        prospects.students as t2 ON 
        t1.id_student = t2.id_student
        WHERE t2.id_in_students_ykt = $id;";
        $res = $this->dbConn()->query($sql);
        $data = "";
        if ($res) {
            while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                $data = $row['student_medicine_text'];
            }
        }
        $sql = "SELECT 
        student_medicine_text 
        FROM 
        school_control_ykt.health_data_students 
        WHERE id_student = $id;";
        $res = $this->dbConn()->query($sql);
        if ($res) {
            while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                $data .= $row['student_medicine_text'];
            }
        }
        return $data;
    }
    public function get_all_grades()
    {
    }

    public function update_data_from_enfermeria()
    {
        $sql = "UPDATE school_control_ykt.health_data_students 
        SET $_POST[col] = '$_POST[txt]' 
        WHERE id_student = $_POST[id_student];";
        $this->status = $this->dbConn()->query($sql) !== false;
        $sql = str_replace("'", '"', $sql);
        if (!isset($_SESSION)) session_start();
        $this->audit($_SESSION["colab"], "school_control_ykt.health_data_students", "$_POST[col] = $_POST[txt] ", 0, "update_data_from_enfermeria $sql");
        return $this;
    }
    public function get_plantels()
    {
        $sql = "SELECT * FROM school_control_ykt.campus;";
        $res = $this->dbConn()->query($sql);
        $this->status = $res !== false;
        $data = [];
        if ($res) {
            while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
        }
        $this->plantels = $data;
        return $this;
    }
    public function get_grades()
    {
        $sql = "SELECT * FROM school_control_ykt.academic_levels_grade;";
        $res = $this->dbConn()->query($sql);
        $this->status = $res !== false;
        $data = [];
        if ($res) {
            while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
        }
        $this->grades = $data;
        return $this;
    }
    public function get_custom_report()
    {
        extract($_POST);
        $rest = "";
        $dates = "";
        if (!empty($date_begin) && !empty($date_end)) {
            $dates = "fecha BETWEEN '$date_begin' AND '$date_end'";
        }
        if (empty($date_begin) && !empty($date_end)) {
            $dates = "fecha BETWEEN '2023-01-01' AND '$date_end'";
        }
        if (!empty($date_begin) && empty($date_end)) {
            $dates = "fecha = '$date_begin'";
        }
        if (!empty($date_begin) && empty($date_end)) {
            $dates = "fecha = '$date_begin'";
        }
        if (!empty($grade)) {
            $grade = "t4.id_level_grade = $grade";
        }
        if (empty($date_begin) && empty($date_end) && empty($campus) && empty($grade)) {
            $sql = "SELECT COUNT(id_student) as consultas FROM  school_control_ykt.consultas WHERE 1";
            $res = $this->dbConn()->query($sql);
            $data = [];
            if ($res) {
                while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                    $data = $row;
                }
            }
        } else {
            $sql = "SELECT COUNT(t1.id_student) as consultas FROM 
            school_control_ykt.consultas as t1 
            INNER JOIN 
            school_control_ykt.students as t2 ON 
            t1.id_student = t2.id_student 
            INNER JOIN 
            school_control_ykt.groups as t3 ON 
            t2.group_id = t3.id_group 
            INNER JOIN 
            school_control_ykt.academic_levels_grade as t4 ON 
            t4.id_level_grade = t3.id_level_grade 
            WHERE $grade";
            $res = $this->dbConn()->query($sql);

            $data = [];
            if ($res) {
                while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                    $data = $row;
                }
            }
        }

        $this->status = $res !== false;
        $this->data = $data;
        return $this;
    }
    public function get_details_varones_sections_consultas()
    {
        $sql = "SELECT 
        CONCAT(t4.degree,' | HOMBRES') AS degree,
        COUNT(DISTINCT t2.id_student) as n 
        FROM 
        school_control_ykt.consultas as t1 
        INNER JOIN 
        school_control_ykt.students as t2 ON t1.id_student = t2.id_student 
        INNER JOIN 
        school_control_ykt.groups as t3 ON t2.group_id = t3.id_group 
        INNER JOIN 
        school_control_ykt.academic_levels_grade as t4 ON t3.id_level_grade = t4.id_level_grade 
        WHERE t2.gender = 0 
        GROUP BY t4.id_level_grade,t2.gender 
        ORDER BY `t4`.`id_level_grade` ASC;";
        $res = $this->dbConn()->query($sql);
        $consultas = [];
        if ($res) {
            while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                $consultas[] = $row;
            }
        }
        return $consultas;
    }
    public function get_details_mujeres_sections_consultas()
    {
        $sql = "SELECT 
        CONCAT(t4.degree,' | MUJERES') AS degree,
        COUNT(DISTINCT t2.id_student) as n 
        FROM 
        school_control_ykt.consultas as t1 
        INNER JOIN 
        school_control_ykt.students as t2 ON t1.id_student = t2.id_student 
        INNER JOIN 
        school_control_ykt.groups as t3 ON t2.group_id = t3.id_group 
        INNER JOIN 
        school_control_ykt.academic_levels_grade as t4 ON t3.id_level_grade = t4.id_level_grade 
        WHERE t2.gender = 1 
        GROUP BY t4.id_level_grade,t2.gender 
        ORDER BY `t4`.`id_level_grade` ASC;";
        $res = $this->dbConn()->query($sql);
        $consultas = [];
        if ($res) {
            while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                $consultas[] = $row;
            }
        }
        return $consultas;
    }
    public function get_custom_primary_report()
    {
        extract($_POST);
        $rest = "";
        $dates = "";
        $conditions = "";
        if (!empty($date_begin) && !empty($date_end)) {
            $dates = "fecha BETWEEN '$date_begin' AND '$date_end'";
            $conditions = " $dates ";
            if (!empty($grade) || !empty($campus)) $conditions .= " AND ";
        }
        if (empty($date_begin) && !empty($date_end)) {
            $dates = "fecha BETWEEN '2023-01-01' AND '$date_end'";
            $conditions = " $dates AND ";
        }
        if (!empty($date_begin) && empty($date_end)) {
            $dates = "fecha = '$date_begin'";
            $conditions = " $dates ";
            if (!empty($grade) || !empty($campus)) $conditions .= " AND ";
        }
        if (empty($date_begin) && empty($date_end)) {
            $dates = "";
            $conditions = "";
        }
        if (!empty($grade)) {
            $grade = "t4.id_level_grade = $grade";
            $conditions .= " $grade AND ";
            if (!empty($dates)) $conditions = " $dates AND $grade ";
            if (!empty($campus)) $conditions .= " AND ";
        }
        if (!empty($campus)) {
            $campus = "t3.id_campus = $campus";
            $conditions .= " $campus ";
        }
        /******************/
        if (empty($date_begin) && empty($date_end) && empty($campus) && empty($grade)) {
            $conditions = " 1 ";
        }
        $sql = "SELECT COUNT(DISTINCT t1.id_student) as consultas FROM 
            school_control_ykt.consultas as t1 
            INNER JOIN 
            school_control_ykt.students as t2 ON 
            t1.id_student = t2.id_student 
            INNER JOIN 
            school_control_ykt.groups as t3 ON 
            t2.group_id = t3.id_group 
            INNER JOIN 
            school_control_ykt.academic_levels_grade as t4 ON 
            t4.id_level_grade = t3.id_level_grade 
            WHERE $conditions";
        $res = $this->dbConn()->query($sql);
        $consultas = 0;
        if ($res) {
            while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                $consultas = $row["consultas"];
            }
        }

        $this->status = $res !== false;
        $this->data = array(
            "consultas" => $consultas,
            "consultas_by_grade" => $this->consultas_by_sections($conditions),
            "consultas_by_gender" => array(
                "varones" => $this->get_consultas_varones_with_conditions($conditions),
                "mujeres" => $this->get_consultas_mujeres_with_conditions($conditions),
            ),
            "consultas_by_morbidity" => $this->consultas_by_morbidity_conditions($conditions),
            "students"=>$this->get_top_ten($conditions)
        );
        return $this;
    }
    public function get_top_ten($conditions)
    {
          $sql = "SELECT t1.id_student,
          CONCAT(t2.name,' ',t2.LASTname) AS name,
          t2.student_code, t3.group_code,
          COUNT(id_consulta) as times FROM 
          school_control_ykt.consultas as t1
          INNER JOIN 
          school_control_ykt.students as t2 ON 
          t1.id_student = t2.id_student
          INNER JOIN 
          school_control_ykt.groups as t3 ON 
          t2.group_id = t3.id_group 
          WHERE $conditions 
          GROUP BY id_student  
          ORDER BY `times` DESC LIMIT 10;";
        $data = [];
        $res = $this->conn->query($sql);
        while ($row = $res->fetch(PDO::FETCH_OBJ)) {
            $data[] = $row;
        }
        return $data;
    }
    public function consultas_by_morbidity_conditions($conditions)
    {
        $sql = "SELECT t1.id_morbidity,COUNT(DISTINCT t1.id_student) as n, t2.color_hex,t2.morbidity  
        FROM 
        school_control_ykt.consultas as t1 
        INNER JOIN 
        school_control_ykt.morbidities as t2 
        ON t1.id_morbidity = t2.id_morbidity 
        INNER JOIN 
        school_control_ykt.students as t7 ON t1.id_student = t7.id_student 
        INNER JOIN 
        school_control_ykt.groups as t3 ON t7.group_id = t3.id_group 
        INNER JOIN 
        school_control_ykt.academic_levels_grade as t4 ON t3.id_level_grade = t4.id_level_grade 
        WHERE $conditions 
        GROUP BY t1.id_morbidity
        ORDER BY n ASC;";
        $res = $this->dbConn()->query($sql);
        $consultas_by_morbidity = [];
        if ($res) {
            while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                $consultas_by_morbidity[] = $row;
            }
        }
        return $consultas_by_morbidity;
    }
    public function get_consultas_varones_with_conditions($conditions)
    {
        $consultas_varones = [];
        $sql = "SELECT t1.*,t2.name FROM school_control_ykt.consultas as t1 
        INNER JOIN school_control_ykt.students as t2 ON t1.id_student = t2.id_student 
        INNER JOIN 
        school_control_ykt.groups as t3 ON t2.group_id = t3.id_group 
        INNER JOIN 
        school_control_ykt.academic_levels_grade as t4 ON t3.id_level_grade = t4.id_level_grade 
        WHERE t2.gender = 0 AND $conditions GROUP BY `t1`.`id_student`;";
        $res = $this->dbConn()->query($sql);
        if ($res) {
            while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                $consultas_varones[] = $row;
            }
        }
        return $consultas_varones;
    }
    public function get_consultas_mujeres_with_conditions($conditions)
    {
        $consultas_mujeres = [];
        $sql = "SELECT t1.*,t2.name FROM school_control_ykt.consultas as t1 
        INNER JOIN school_control_ykt.students as t2 ON t1.id_student = t2.id_student 
        INNER JOIN 
        school_control_ykt.groups as t3 ON t2.group_id = t3.id_group 
        INNER JOIN 
        school_control_ykt.academic_levels_grade as t4 ON t3.id_level_grade = t4.id_level_grade 
        WHERE t2.gender = 1 AND $conditions GROUP BY `t1`.`id_student`;";
        $res = $this->dbConn()->query($sql);
        if ($res) {
            while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                $consultas_mujeres[] = $row;
            }
        }
        return $consultas_mujeres;
    }
    public function consultas_by_sections($conditions)
    {
        $sql = "SELECT 
        IF(
            t2.gender = 1, 
                CONCAT(t5.academic_level,' | MUJERES'),
                CONCAT(t5.academic_level,' | HOMBRES')) AS degree,
                COUNT(DISTINCT t2.id_student) as n 
        FROM 
        school_control_ykt.consultas as t1 
        INNER JOIN 
        school_control_ykt.students as t2 ON t1.id_student = t2.id_student 
        INNER JOIN 
        school_control_ykt.groups as t3 ON t2.group_id = t3.id_group 
        INNER JOIN 
        school_control_ykt.academic_levels_grade as t4 ON t3.id_level_grade = t4.id_level_grade 
		INNER JOIN 
        school_control_ykt.academic_levels as t5 ON t4.id_academic_level = t5.id_academic_level 
        WHERE $conditions 
        GROUP BY t5.id_academic_level,t2.gender 
        ORDER BY `t4`.`id_level_grade` ASC;";
        $res = $this->dbConn()->query($sql);
        $consultas_by_grade = [];
        if ($res) {
            while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
                $consultas_by_grade[] = $row;
            }
        }
        return $consultas_by_grade;
    }
    public function get_students_interview($centers)
    {
        $filter = "";
        if (!empty($centers)) {
            $filter = " AND (";
            foreach ($centers as $area) {
                $cc = strtolower($area->cost_center);
                $all = $cc === 'todos' || $cc = "dirac";
                if ($all) {
                    $filter = "";
                    break;
                }
                if (!$area->id_centro_costos) {
                    next($centers);
                    continue;
                }
                $filter .= " t5.id_centro_costos ='" . $area->id_centro_costos . "'";
                if (next($centers)) {
                    $filter .= " OR";
                }
            }
        }
        if (!empty($filter)) $filter .= ")";

        $sql = "SELECT DISTINCT 
        CONCAT(t1.fathers_surname,' ',t1.mothers_surname,' ',t1.name) as name,
        CONCAT(t7.fathers_surname,' ',t7.mothers_surname,' ',t7.name) as fname,
        CONCAT(t8.fathers_surname,' ',t8.mothers_surname,' ',t8.name) as mname,
        t7.cell_phone as fcell_phone,t8.cell_phone as mcell_phone,t7.mail as fmail,
        t8.mail as mmail,t1.student_code,t5.group_code,t2.campus_name,t1.gender,
        t9.*,t1.age,t3.degree,t4.school_year,t6.status,t6.class,t1.curp,t1.id_student,
        tA.family_name,t1.interview    
        FROM prospects.students as t1 
        INNER JOIN 
        school_control_ykt.campus as t2 ON 
        t1.id_campus = t2.id_campus 
        INNER JOIN 
        school_control_ykt.academic_levels_grade as t3 ON 
        t1.id_degree = t3.id_level_grade 
        INNER JOIN 
        school_control_ykt.current_school_year as t4 ON 
        t1.id_current_cycle = t4.id_school_year 
        INNER JOIN 
        school_control_ykt.groups as t5 ON 
        t1.id_group_code = t5.id_group 
        INNER JOIN 
        prospects.status as t6 ON 
        t1.id_status = t6.id_status 
        INNER JOIN 
        prospects.fathers as t7 ON 
        t1.id_family_prospects = t7.id_family_prospects
        INNER JOIN 
        prospects.mothers as t8 ON 
        t1.id_family_prospects = t8.id_family_prospects 
        INNER JOIN 
        prospects.address as t9 ON 
        t1.id_family_prospects = t9.id_family_prospects 
        INNER JOIN 
        prospects.family_prospects as tA ON 
        t1.id_family_prospects = tA.id_family_prospects 
        WHERE  
        (t1.interview = 1 
        OR 
        t1.interview = 2)  
        $filter
        ";
        $data = [];
        $res = $this->conn->query($sql);
        while ($row = $res->fetch(PDO::FETCH_OBJ)) {
            $data[] = $row;
        }
        return $data;
    }
    public function get_th_form_names()
    {
        $sql = "SELECT id_row,value
        FROM prospects.prospects_interview 
        WHERE  
        context = 'dashboard_table'";
        $data = [];
        $res = $this->conn->query($sql);
        while ($row = $res->fetch(PDO::FETCH_OBJ)) {
            $data[] = $row;
        }
        return $data;
    }
    public function get_area_col()
    {
        $sql = "SELECT t1.* 
        FROM 
        events_calendar.costs_center as t1 
        INNER JOIN 
        events_calendar.assc_cost_center_no_collaborator as t2 ON 
        t1.cost_center_id = t2.cost_center_id
        WHERE 
        t2.no_collaborator = $_SESSION[colab]";
        $data = [];
        $res = $this->conn->query($sql);
        while ($row = $res->fetch(PDO::FETCH_OBJ)) {
            $data[] = $row;
        }
        return $data;
    }
    public function get_top_ten_kids()
    {
        $sql = "SELECT t1.id_student,
        CONCAT(t2.name,' ',t2.LASTname) AS name,
        t2.student_code, t3.group_code,
        COUNT(id_consulta) as times FROM 
        school_control_ykt.consultas as t1
        INNER JOIN 
        school_control_ykt.students as t2 ON 
        t1.id_student = t2.id_student
        INNER JOIN 
        school_control_ykt.groups as t3 ON 
        t2.group_id = t3.id_group
        GROUP BY id_student  
        ORDER BY `times` DESC LIMIT 10;";
        $data = [];
        $res = $this->conn->query($sql);
        while ($row = $res->fetch(PDO::FETCH_OBJ)) {
            $data[] = $row;
        }
        return $data;
    }

}
