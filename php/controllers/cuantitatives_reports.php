<?php
/*include '../../../general/php/models/Connection.php';
include '../../../general/php/models/GeneralModel.php';
include '../models/cualitatives_reports.php';
include '../models/students.php';*/
set_time_limit(0);

date_default_timezone_set('America/Mexico_City');
if (isset($_POST['mod'])) {
	$function = $_POST['mod'];
	$function();
}

function getAveragesByAssignmentPeriods($id_student, $assgs, $periods, $calculateFinalAverages)
{
	$reports = new GroupsReports;
	$obj_assg = array();

	$final_all_grade = 0;
	$final_all_grade_rounded = 0;
	$final_count_assgs = 0;

	$final_all_grade_dynamic = 0;
	$final_all_grade_rounded_dynamic = 0;
	$final_count_assgs_dynamic = 0;

	foreach ($assgs as $assg) {
		$obj_periods = array();
		$final_grade = array();
		$final_grade_periods = 0;
		$final_grade_periods_rounded = 0;
		$count_period_active = 0;

		$final_grade_periods_dynamic = 0;
		$final_grade_periods_rounded_dynamic = 0;
		$count_period_active_dynamic = 0;

		foreach ($periods as $period) {

			$average = 0;
			$query_average = $reports->getStudentGradeByAssignment($id_student, $period->id_period_calendar, $assg->id_assignment);

			if (!empty($query_average)) {
				if ($query_average->average != null && $query_average->average != '') {
					//echo $query_average->average;
					$average = $query_average->average;
					$id_grade_period = $query_average->id_grade_period;
					$final_grade_periods += floatval($query_average->average);
					$count_period_active++;
				} else {
					$average = '-';
					$id_grade_period = '-';
				}

				if ($query_average->grade_period_calc != null && $query_average->grade_period_calc != '') {
					$grade_period_calc = $query_average->grade_period_calc;
					$final_grade_periods_dynamic += floatval($query_average->grade_period_calc);
					$count_period_active_dynamic++;
				} else {
					$grade_period_calc = '-';
				}
			} else {
				$average = '-';
				$grade_period_calc = '-';
				$id_grade_period = '-';
			}

			// ENVIAMOS DATOS AL OBJETO CALCULAR PROMEDIOS FINALES //
			if ($average != '-' && $average >= 0) {
				$calculateFinalAverages->setAverages($assg->id_assignment, $period->id_period_calendar, $average);
			}

			$obj_periods[] = (object)[
				'id_period_calendar' => $period->id_period_calendar,
				'id_grade_period' => $id_grade_period,
				'no_period' => $period->no_period,
				'average' => $average,
				'grade_period_calc' => $grade_period_calc
			];
		}

		if ($count_period_active > 0) {
			$final_grade_periods = $final_grade_periods / $count_period_active;
			$final_grade_periods = round($final_grade_periods, 1);
			$final_all_grade += $final_grade_periods;

			$calculateFinalAverages->setAverageAssgFinal($assg->id_assignment, $final_grade_periods);
			$final_grade_periods_rounded = roundAverage6($final_grade_periods);
			$final_count_assgs++;
		} else {
			$final_grade_periods_rounded = '-';
			$final_grade_periods = '-';
		}

		/// BEGINS DYNAMIC AVERAGES ///
		if ($count_period_active_dynamic > 0) {
			$final_grade_periods_dynamic = $final_grade_periods_dynamic / $count_period_active_dynamic;
			$final_grade_periods_dynamic = round($final_grade_periods_dynamic, 1);
			$final_all_grade_dynamic += $final_grade_periods_dynamic;

			$calculateFinalAverages->setAverageAssgFinal($assg->id_assignment, $final_grade_periods_dynamic);
			$final_grade_periods_rounded_dynamic = roundAverage6($final_grade_periods_dynamic);
			$final_count_assgs_dynamic++;
		} else {

			$final_grade_periods_rounded_dynamic = '-';
			$final_grade_periods_dynamic = '-';
		}
		/// ENDS DYNAMIC AVERAGES ///

		$obj_assg[] = (object)[
			'id_assignment' => $assg->id_assignment,
			'name_subject' => $assg->name_subject,
			'final_grade_periods_rounded' => $final_grade_periods_rounded,
			'final_grade_periods' => $final_grade_periods,
			'final_grade_periods_rounded_dynamic' => $final_grade_periods_rounded_dynamic,
			'final_grade_periods_dynamic' => $final_grade_periods_dynamic,
			'periods' => $obj_periods
		];
	}

	if ($final_count_assgs > 0) {
		$final_all_grade = $final_all_grade / $final_count_assgs;
		$final_all_grade = round($final_all_grade, 1);

		$calculateFinalAverages->setAverageFinal($final_all_grade);

		$final_all_grade_rounded = roundAverage6($final_all_grade);
	} else {
		$final_count_assgs = '-';
		$final_all_grade_rounded = '-';
	}

	/// BEGINS DYNAMIC AVERAGES ///
	if ($final_count_assgs_dynamic > 0) {
		$final_all_grade_dynamic = $final_all_grade_dynamic / $final_count_assgs_dynamic;
		$final_all_grade_dynamic = round($final_all_grade_dynamic, 1);

		$calculateFinalAverages->setAverageFinal($final_all_grade_dynamic);

		$final_all_grade_rounded_dynamic= roundAverage6($final_all_grade_dynamic);
	} else {
		$final_count_assgs_dynamic = '-';
		$final_all_grade_rounded_dynamic = '-';
	}
	/// ENDS DYNAMIC AVERAGES ///

	$results = new stdClass();
	$results->final_all_grade_rounded = $final_all_grade_rounded;
	$results->final_all_grade = $final_all_grade;
	$results->final_all_grade_rounded_dynamic = $final_all_grade_rounded_dynamic;
	$results->final_all_grade_dynamic = $final_all_grade_dynamic;
	$results->assgs = $obj_assg;

	return $results;
}

function roundAverage6($average)
{

	$average_new = $average;
	$arr_promedio = explode('.', $average_new);

	if (count($arr_promedio) > 1) {
		$entero_promedio = $arr_promedio[0];
		$decimal_promedio = $arr_promedio[1];
		if ($decimal_promedio >= 6) {
			$average_new = ($arr_promedio[0] + 1);
		} else {
			$average_new = ($arr_promedio[0]);
		}
	}

	return $average_new;
}

function getBCGCByAverage($average)
{
	$background_color = '';

	if (is_numeric($average)) {
		$average = floatval($average);
		if ($average >= 9.5) {
			$background_color = '#BDE8C2';
		} else if ($average <= 5 && $average >= 0) {
			$background_color = '#FEC7CB';
		}
	} else {
		$background_color = '#CBCBCB';
	}

	return $background_color;
}

class CalculateFinalAverages
{

	protected $averages;
	protected $average_final;
	protected $average_assgs;

	public function __construct()
	{
		$this->averages = new stdClass();
		$this->average_final = array();
		$this->average_assgs = new stdClass();
	}


	public function setAverages($key, $id_period_calendar, $average)
	{
		$nameAssg = "idA{$key}";
		$namePC = "period{$id_period_calendar}";

		if (isset($this->averages->$nameAssg)) {
			if (isset($this->averages->$nameAssg->$namePC)) {
				array_push($this->averages->$nameAssg->$namePC, $average);
			} else {
				$this->averages->$nameAssg->$namePC = [$average];
			}
		} else {
			$periods = new stdClass();
			$periods->$namePC = [$average];

			$this->averages->$nameAssg = $periods;
			//print_r($this->averages);
		}
	}

	/*public function getAveragesAllAssgAllPeriod($assgs, $periods){
		$arr_pg = array();
		$average = '-';

		foreach($assgs AS $assg){
			if($this->getAveragesByAssgAllPeriod($assg->id_assignment, $periods) != '-'){
				array_push($arr_pg, $this->getAveragesByAssgAllPeriod($assg->id_assignment, $periods));
			}
		}

		if(!empty($arr_pg)){
			$average = array_sum($arr_pg) / count($arr_pg);
			$average = round($average, 1);
		}

		return $average;
	}*/

	/*public function getAveragesByAssgAllPeriod($id_assignment, $periods){
		$arr_pm = array();
		$average = '-';

		$nameAssg = "idA{$id_assignment}";
		foreach($periods AS $period){
			$namePC = "period{$period->id_period_calendar}";
			if(isset($this->averages->$nameAssg)){
				if(isset($this->averages->$nameAssg->$namePC)){
					$average = array_sum($this->averages->$nameAssg->$namePC) / count($this->averages->$nameAssg->$namePC);
					array_push($arr_pm, round($average, 1));
				} 
			}
		}

		if(!empty($arr_pm)){
			$average = array_sum($arr_pm) / count($arr_pm);
			$average = round($average, 1);
		}

		return $average;
	}*/

	public function getAveragesByAssgPeriod($key, $id_period_calendar)
	{
		$arr_response = '-';

		$nameAssg = "idA{$key}";
		$namePC = "period{$id_period_calendar}";

		if (isset($this->averages->$nameAssg)) {
			if (isset($this->averages->$nameAssg->$namePC)) {
				$average = array_sum($this->averages->$nameAssg->$namePC) / count($this->averages->$nameAssg->$namePC);

				$arr_response = round($average, 1);
			}
		}

		return $arr_response;
	}

	public function setAverageFinal($average)
	{
		array_push($this->average_final, $average);
	}

	public function getAverageFinal()
	{
		$average = '-';

		if (!empty($this->average_final)) {
			$average = array_sum($this->average_final) / count($this->average_final);
			$average = round($average, 1);
		}

		return $average;
	}

	public function setAverageAssgFinal($id_assignment, $average)
	{
		$nameAssg = "idA{$id_assignment}";

		if (isset($this->average_assgs->$nameAssg)) {
			array_push($this->average_assgs->$nameAssg, $average);
		} else {
			$this->average_assgs->$nameAssg = [$average];
		}
	}

	public function getAverageassgFinal($id_assignment)
	{
		$average = '-';
		$nameAssg = "idA{$id_assignment}";

		if (!empty($this->average_assgs->$nameAssg)) {
			$average = array_sum($this->average_assgs->$nameAssg) / count($this->average_assgs->$nameAssg);
			$average = round($average, 1);
		}

		return $average;
	}
}
