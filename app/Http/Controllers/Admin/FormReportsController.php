<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Livewire\Tracer\Answer;
use App\Models\Alumni;
use App\Models\Courses;
use App\Models\Forms\Eif\EifAnswers;
use App\Models\Forms\Eif\EifCategories;
use App\Models\Forms\Eif\EifQuestions;
use App\Models\Forms\Pds\PdsAnswers;
use App\Models\Forms\Sas\SasAnswers;
use App\Models\Forms\Sas\SasCategories;
use App\Models\Forms\Sas\SasQuestions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use TCPDF;
use DB;

class MYPDF extends TCPDF {
    //Page header
    public function Header() {
        // Logo
        // $image_file = public_path('/img/pupLogo.png');
        $image_file = asset('img/pupLogo.png');
        $this->Image($image_file, 15, 10, 20, '', 'PNG', '', 'C', false, 300, '', false, false, 0, false, false, false);
        // Title
        $this->Ln(3);
        $this->SetFont('times', '', 12);
        $this->Cell(95, 0,
                    'Republic of the Philippines',
                    0, 1, 'C', 0, '', 0, false, 'T', 'M');
        $this->SetFont('times', 'B', 15);
        $this->Cell(179, 0,
                    'POLYTECHNIC UNIVERSITY OF THE PHILIPPINES',
                    0, 1, 'C', 0, '', 0, false, 'T', 'M');
        $this->SetFont('times', '', 12);
        $this->Cell(158, 0,
                    'Office of the Vice President for Branches and Satelite Campuses',
                    0, 1, 'C', 0, '', 0, false, 'T', 'M');
        $this->SetFont('times', 'B', 12);
        $this->Cell(85, 0,
                    'TAGUIG BRANCH',
                    0, 1, 'C', 0, '', 0, false, 'T', 'M');

        $style3 = array('width' => .5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));

        // Line
        $this->Line(10, 33, 200, 33, $style3);
    }

    // Page footer
    public function Footer() {
        // Position at 10 mm from bottom
        $this->SetY(-10);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $style3 = array('width' => .5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));

        // Line

        if ($this->title == 'PDS_DETAILED_REPORTS') {
            $this->Line(10, 320, 200, 320, $style3);
        }
        elseif ($this->title == 'EIF_DETAILED_REPORTS') {
            $this->Line(10, 202, 288, 202, $style3);
        }
        else {
            $this->Line(10, 288, 200, 288, $style3);
        }
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

class FormReportsController extends Controller
{
    function numberToRomanRepresentation($number) {
        $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
        $returnValue = '';
        while ($number > 0) {
            foreach ($map as $roman => $int) {
                if($number >= $int) {
                    $number -= $int;
                    $returnValue .= $roman;
                    break;
                }
            }
        }
        return $returnValue;
    }

    public function generateFormReport(Request $request) {
        $request->validate(
            [
                "form"  => "required",
                "type"  => "required",
            ],

            [
                "*.required" => "This is required.",
            ]
        );

        switch($request->form) {
            case 1:
                switch($request->type) {
                    case 1:
                        $this->PdsDetailedReport($request);
                        break;
                    case 2:
                        return back()->with('fail', 'Personal Data Sheet doesn\'t have a Summary Report.');
                        break;
                    case 3:
                        $this->PdsStatusReport($request);
                        break;
                    default:
                        return back()->with('fail', 'Error! Please try again.');
                }
                break;

            case 2:
                switch($request->type) {
                    case 1:
                        $this->EifDetailedReport($request);
                        break;
                    case 2:
                        $this->EifSummaryReport($request);
                        break;
                    case 3:
                        $this->EifStatusReport($request);
                        break;
                }
                break;

            case 3:
                switch($request->type) {
                    case 1:
                        $this->SasDetailedReport($request);
                        break;
                    case 2:
                        $this->SasSummaryReport($request);
                        break;
                    case 3:
                        $this->SasStatusReport($request);
                        break;
                    default:
                        return back()->with('fail', 'Error! Please try again.');
                }
                break;

            default:
                return back()->with('fail', 'Error! Please try again.');
        }
    }

    public function PdsDetailedReport($request) {

        if($request->course_id == 'All') {
            if ($request->sex == null || empty($request->sex)) {
                $allAlumni = Alumni::whereBetween('batch', [$request->batch_from, $request->batch_to])
                    ->orderBy('last_name', 'asc')
                    ->get();

                $checkCount = Alumni::whereBetween('batch', [$request->batch_from, $request->batch_to])
                    ->join('form_pds_answers', 'tbl_alumni.alumni_id', '=', 'form_pds_answers.alumni_id')
                    ->select('tbl_alumni.alumni_id')
                    ->get();
            }
            else {
                $allAlumni = Alumni::where('sex', '=', $request->sex)
                    ->whereBetween('batch', [$request->batch_from, $request->batch_to])
                    ->orderBy('last_name', 'asc')
                    ->get();

                $checkCount = Alumni::where('sex', '=', $request->sex)
                    ->whereBetween('batch', [$request->batch_from, $request->batch_to])
                    ->join('form_pds_answers', 'tbl_alumni.alumni_id', '=', 'form_pds_answers.alumni_id')
                    ->select('tbl_alumni.alumni_id')
                    ->get();
            }

            $courses = Courses::get();
        } else {
            if ($request->sex == null || empty($request->sex)) {
                $allAlumni = Alumni::where('course_id', 'like', '%' . $request->course_id . '%')
                    ->whereBetween('batch', [$request->batch_from, $request->batch_to])
                    ->orderBy('last_name', 'asc')
                    ->get();

                $checkCount = Alumni::where('course_id', 'like', '%' . $request->course_id . '%')
                    ->whereBetween('batch', [$request->batch_from, $request->batch_to])
                    ->join('form_pds_answers', 'tbl_alumni.alumni_id', '=', 'form_pds_answers.alumni_id')
                    ->select('tbl_alumni.alumni_id')
                    ->get();
            }
            else {
                $allAlumni = Alumni::where('course_id', 'like', '%' . $request->course_id . '%')
                    ->where('sex', '=', $request->sex)
                    ->whereBetween('batch', [$request->batch_from, $request->batch_to])
                    ->orderBy('last_name', 'asc')
                    ->get();

                $checkCount = Alumni::where('course_id', 'like', '%' . $request->course_id . '%')
                    ->where('sex', '=', $request->sex)
                    ->whereBetween('batch', [$request->batch_from, $request->batch_to])
                    ->join('form_pds_answers', 'tbl_alumni.alumni_id', '=', 'form_pds_answers.alumni_id')
                    ->select('tbl_alumni.alumni_id')
                    ->get();
            }

            $courses = Courses::where('course_id', 'like', '%' . $request->course_id . '%')->get();
        }

        $pdf = new MYPDF('P', 'mm', array(215.9, 330.2), true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetTitle('PDS_DETAILED_REPORTS');
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        foreach ($courses as $checkCourse) {
            foreach ($allAlumni as $alumni) {
                $psdAnswers = PdsAnswers::where('alumni_id', '=', $alumni->alumni_id)->get();
                if ($checkCourse->course_id == $alumni->course_id) {
                    if (count($psdAnswers) > 0) {
                        $pdf->SetPrintHeader(true);
                        $pdf->AddPage();
                        $pdf->SetPrintHeader(false);
                        $pdf->SetFont('times', 'B', 13);
                        $pdf->ln(15);
                        $pdf->Cell(0, 0, 'PERSONAL DATA SHEET', 0, 1, 'C', 0, '', 0);

                        $pdf->ln(8);
                        $pdf->SetFont('times', '', 12);
                        $html = <<<EOF
                            <table style="width:100%">
                                <tr>
                                    <th colspan="1" style="width: 8%; font-weight: bold;">Name: </th>
                                    <td colspan="1" style="text-align: center; width: 30%">$alumni->last_name </td>
                                    <td colspan="1" style="text-align: center; width: 32%">$alumni->first_name $alumni->suffix </td>
                                    <td colspan="1" style="text-align: center; width: 30%">$alumni->middle_name </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td style="text-align: center; font-size:12px; border-top: 1px solid black;"><i>Surname</i></td>
                                    <td style="text-align: center; font-size:12px; border-top: 1px solid black;"><i>First Name</i></td>
                                    <td style="text-align: center; font-size:12px; border-top: 1px solid black;"><i>Middle Name</i></td>
                                    <td></td>
                                    <td></td>
                                    <td style="border-top: 1px solid black;"></td>
                                </tr>
                            </table>
                        EOF;
                        $pdf->writeHTML($html, true, 0, true, 0);

                        $pdf->Ln(-5);
                        $bday = date('F d, Y', strtotime($alumni->birthday));
                        $html = <<<EOF
                            <table style="width:100%; margin-top: 300px;">
                                <tr>
                                    <th colspan="1" style="width: 16%; font-weight: bold;">Date of Birth: </th>
                                    <td colspan="1" style="border-bottom: 1px solid black; width: 25%;"> $bday </td>
                                    <td colspan="1" style="width: 10%;"></td>
                                    <th colspan="1" style="width: 6%; font-weight: bold;">Age: </th>
                                    <td colspan="1" style="border-bottom: 1px solid black; width: 17%;"> $alumni->age years old</td>
                                </tr>
                            </table>
                        EOF;
                        $pdf->writeHTML($html, true, 0, true, 0);

                        $pdf->Ln(-3);
                        $bday = date('F d, Y', strtotime($alumni->birthday));
                        $html = <<<EOF
                            <table style="width:100%; margin-top: 300px;">
                                <tr>
                                    <th colspan="1" style="width: 11%; font-weight: bold;">Religion: </th>
                                    <td colspan="1" style="border-bottom: 1px solid black; width: 30%;"> $alumni->religion </td>
                                    <td colspan="1" style="width: 10%;"></td>
                                    <th colspan="1" style="width: 6%; font-weight: bold;">Sex: </th>
                                    <td colspan="1" style="border-bottom: 1px solid black; width: 17%;"> $alumni->sex </td>
                                </tr>
                            </table>
                        EOF;
                        $pdf->writeHTML($html, true, 0, true, 0);

                        $pdf->Ln(-3);
                        $course = Courses::where('course_id', '=', $alumni->course_id)->value('course_desc');
                        $html = <<<EOF
                            <table style="width:100%; margin-top: 10px;">
                                <tr>
                                    <th colspan="1" style="width: 17%; font-weight: bold;">Degree/Course: </th>
                                    <td colspan="1" style="border-bottom: 1px solid black; width: 83%;"> $course </td>
                                </tr>
                            </table>
                        EOF;
                        $pdf->writeHTML($html, true, 0, true, 0);

                        $pdf->Ln(-3);
                        $html = <<<EOF
                            <table style="width:100%; margin-top: 10px;">
                                <tr>
                                    <th colspan="1" style="width: 19%; font-weight: bold;">Year Graduated: </th>
                                    <td colspan="1" style="border-bottom: 1px solid black; width: 10%;"> $alumni->batch </td>
                                </tr>
                            </table>
                        EOF;
                        $pdf->writeHTML($html, true, 0, true, 0);

                        $pdf->Ln(-3);
                        $html = <<<EOF
                            <table style="width:100%; margin-top: 10px;">
                            <tr>
                                <th colspan="1" style="width: 16%; font-weight: bold;">City Address: </th>
                                <td colspan="1" style="border-bottom: 1px solid black; width: 84%;"> $alumni->city_address </td>
                            </tr>
                            </table>
                        EOF;
                        $pdf->writeHTML($html, true, 0, true, 0);

                        $pdf->Ln(-3);
                        $html = <<<EOF
                            <table style="width:100%; margin-top: 10px;">
                            <tr>
                                <th colspan="1" style="width: 22%; font-weight: bold;">Provincial Address: </th>
                                <td colspan="1" style="border-bottom: 1px solid black; width: 78%;"> $alumni->provincial_address </td>
                            </tr>
                            </table>
                        EOF;
                        $pdf->writeHTML($html, true, 0, true, 0);

                        $pdf->Ln(-3);
                        $html = '<table style="width:100%; margin-top: 10px;">
                                <tr>
                                    <th colspan="1" style="width: 17%; font-weight: bold;">Father\'s Name: </th>
                                    <td colspan="1" style="border-bottom: 1px solid black; width: 39%;">'. $psdAnswers[1]['answer'] . '</td>
                                    <td colspan="1" style="width: 6%;"></td>
                                    <th colspan="1" style="width: 15%; font-weight: bold;">Father\'s No.: </th>
                                    <td colspan="1" style="border-bottom: 1px solid black; width: 23%;">'. $psdAnswers[2]['answer'] . '</td>
                                </tr>
                            </table>';
                        $pdf->writeHTML($html, true, 0, true, 0);

                        $pdf->Ln(-3);
                        $html = '<table style="width:100%; margin-top: 10px;">
                                <tr>
                                    <th colspan="1" style="width: 18%; font-weight: bold;">Mother\'s Name: </th>
                                    <td colspan="1" style="border-bottom: 1px solid black; width: 38%;">'. $psdAnswers[3]['answer'] . '</td>
                                    <td colspan="1" style="width: 6%;"></td>
                                    <th colspan="1" style="width: 16%; font-weight: bold;">Mother\'s No.: </th>
                                    <td colspan="1" style="border-bottom: 1px solid black; width: 22%;">'. $psdAnswers[4]['answer'] . '</td>
                                </tr>
                            </table>';
                        $pdf->writeHTML($html, true, 0, true, 0);

                        $pdf->Ln(3);
                        $html = '<style>
                                table, th, td {
                                    text-align: left;
                                    border-collapse: collapse;
                                }
                                .table-work, .th-work, .td-work {
                                    border: 1px solid black;
                                    border-collapse: collapse;
                                    padding: 5px;
                                }
                            </style>
                            <h4 style="text-align: center;">Internship and Work Experience/s:</h4>
                            <table class="table-work" style="width:100%;">
                                <tr>
                                    <th class="th-work" colspan="1" style="font-weight: bold; text-align: center; width:50%">Department / Agency / Office</th>
                                    <th class="th-work" colspan="1" style="font-weight: bold; text-align: center; width: 25%">Position</th>
                                    <th class="th-work" colspan="1" style="font-weight: bold; text-align: center; width: 25%">Inclusive Dates</th>
                                </tr>
                                <tr>
                                    <td class="td-work">'. $psdAnswers[5]['answer'] . '</td>
                                    <td class="td-work">'. $psdAnswers[6]['answer'] . '</td>
                                    <td class="td-work">'. $psdAnswers[7]['answer'] . '</td>
                                </tr>
                            </table>';
                        $pdf->writeHTML($html, true, 0, true, 0);

                        $pdf->Ln(0);
                        $html = '<style>
                                table, th, td {
                                    text-align: left;
                                    border-collapse: collapse;
                                }
                                .table-work, .th-work, .td-work {
                                    border: 1px solid black;
                                    border-collapse: collapse;
                                    padding: 5px;
                                }
                            </style>
                            <h4 style="text-align: center;">Trainings / Seminars Attended:</h4>
                            <table class="table-work" style="width:100%;">
                                <tr>
                                    <th class="th-work" colspan="1" style="width: 70%; text-align: center; font-weight: bold;">Title of Seminar / Conference / Workshop</th>
                                    <th class="th-work" colspan="1" style="width: 30%; text-align: center; font-weight: bold;">Inclusive Dates</th>
                                </tr>
                                <tr>
                                    <td class="td-work">'. $psdAnswers[8]['answer'] . '</td>
                                    <td class="td-work">'. $psdAnswers[9]['answer'] . '</td>
                                </tr>';


                            if ($psdAnswers[10]['answer'] != 'N/A' && $psdAnswers[11]['answer'] != 'N/A') {
                                $html .= '<tr>
                                        <td class="td-work">'. $psdAnswers[10]['answer'] . '</td>
                                        <td class="td-work">'. $psdAnswers[11]['answer'] . '</td>
                                    </tr>';
                            }
                            if ($psdAnswers[12]['answer'] != 'N/A' && $psdAnswers[13]['answer'] != 'N/A') {
                                $html .= '<tr>
                                        <td class="td-work">'. $psdAnswers[10]['answer'] . '</td>
                                        <td class="td-work">'. $psdAnswers[11]['answer'] . '</td>
                                    </tr>';
                            }
                            $html .= '</table>';
                        $pdf->writeHTML($html, true, 0, true, 0);

                        $pdf->Ln(-15);
                        $html = '<div>
                                <p style="text-align: justify; text-justify: inter-word; line-height: 1.5;">I hereby certify that all information provided are true and correct to the best of my knowledge.
                                </p>
                                <table style="width:100%;">
                                    <tr>
                                        <td colspan="1" style="width: 25%; text-align: center; text-transform: uppercase;">' . date('F d, Y', strtotime($psdAnswers[14]['answer'])) . '</td>
                                        <td style="width: 20%"></td>
                                        <td colspan="1" style="width: 55%; text-align: center; text-transform: uppercase;">' . strtoupper($psdAnswers[15]['answer']) . '</td>
                                    </tr>

                                    <tr>
                                        <td colspan="1" style="font-size: 14px; width: 25%; text-align: center; border-top: 1px solid black;">
                                            <i>Date</i>
                                        </td>
                                        <td style="width: 20%"></td>
                                        <td colspan="1" style="font-size: 14px; width: 55%; text-align: center; border-top: 1px solid black;">
                                            <i>Signature</i>
                                        </td>
                                    </tr>
                                </table>
                            </div>';
                        $pdf->writeHTML($html, true, 0, true, 0);

                        $pdf->Ln(-8);
                        $html = '<div>
                                <h4 style="text-align: center; text-decoration: underline;">WAIVER</h4>
                                <p style="text-align: justify; text-justify: inter-word; line-height: 1.5;">This is to signify that I am willing to be subjected to company calls for placement or employment purposes. This shall also authorize the Polytechnic University of The Philippines – Career Development and Placement Office (PUP-CDPO) to include my name and contact details in the directory of graduates.
                                </p>
                                <table style="width:100%;">
                                    <tr>
                                        <td colspan="1" style="width: 25%; text-align: center; text-transform: uppercase;">' . date('F d, Y', strtotime($psdAnswers[14]['answer'])) . '</td>
                                        <td style="width: 20%"></td>
                                        <td colspan="1" style="width: 55%; text-align: center; text-transform: uppercase;">' . strtoupper($psdAnswers[15]['answer']) . '</td>
                                    </tr>
                                    <tr>
                                        <td colspan="1" style="font-size: 14px; width: 25%; text-align: center; border-top: 1px solid black;">
                                            <i>Date</i>
                                        </td>
                                        <td style="width: 20%"></td>
                                        <td colspan="1" style="font-size: 14px; width: 55%; text-align: center; border-top: 1px solid black;">
                                            <i>Signature</i>
                                        </td>
                                    </tr>
                                </table>
                            </div>';
                        $pdf->writeHTML($html, true, 0, true, 0);

                        $pdf->lastPage();
                    }
                }
            }
        }

        $user = auth()->user();
        Log::channel('admin')->info("UserId:".$user->user_id." | AdminId: ".$user->admin_id." | Username: ".$user->name." - Generated an PDS Detailed Report");
        $pdf->Output('PDS_Detailed_Report_' . date('m-d-y') . '.pdf', 'I');
    }

    public function EifSummaryReport($request) {
        $totalPending = 0;
        if ($request->sex == null || empty($request->sex)) {
            $allAlumni = Alumni::where('course_id', 'like', '%' . $request->course_id . '%')
                ->whereBetween('batch', [$request->batch_from, $request->batch_to])
                ->orderBy('last_name', 'asc')
                ->get();
        }
        else {
            $allAlumni = Alumni::where('course_id', 'like', '%' . $request->course_id . '%')
                ->where('sex', '=', $request->sex)
                ->whereBetween('batch', [$request->batch_from, $request->batch_to])
                ->orderBy('last_name', 'asc')
                ->get();
        }

        $courses = Courses::where('course_id', 'like', '%' . $request->course_id . '%')->get();

        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetTitle('EIF_SUMMARY_REPORTS');
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        $pdf->SetPrintHeader(true);
        $pdf->AddPage();
        $pdf->ln(10);
        $pdf->SetFont('times', 'B', 13);
        $pdf->Cell(0, 0, 'EXIT INTERVIEW FORM - SUMMARY REPORT', 0, 1, 'C', 0, '', 0);
        $pdf->SetFont('times', '', 12);
        $pdf->Cell(0, 0, 'ALUMNI BATCH FROM ' . $request->batch_from . ' TO ' . $request->batch_to, 0, 1, 'C', 0, '', 0);
        $pdf->SetFont('times', '', 11);
        $pdf->Cell(0, 0, 'Date Generated: ' . date('F d, Y'), 0, 1, 'C', 0, '', 0);

        $pdf->ln(5);
        $pdf->SetFont('times', 'B', 11);
        $pdf->Cell(0, 0, 'TABLE 1. COURSES', 0, 1, 'L', 0, '', 0);
        $pdf->SetFont('times', '', 11);
        $html = '<style>
                .table-EI, .th-EI, .td-EI {
                    border: 1px solid black;
                    border-collapse: collapse;
                    padding: 5px;
                }
                .theading {
                    background-color: #78281F;
                    color: #ffffff;
                }
                th {
                    font-weight: bold;
                }
                td {
                    text-align: center;
                }
            </style>
            <table class="table-EI" style="width:100%;">
                <tr class="theading" style="text-align: center; font-weight: bold;">
                    <th class="" colspan="1" style="width: 70%;">Course</th>
                    <td class="" colspan="1" style="width: 15%;">Complete</td>
                    <td class="" colspan="1" style="width: 15%;">Pending</td>
                </tr>';
                foreach ($courses as $course) {
                    if ($request->sex == null || empty($request->sex)) {
                        $checkCount = Alumni::join('form_eif_answers', 'tbl_alumni.alumni_id', '=', 'form_eif_answers.alumni_id')
                            ->where('tbl_alumni.course_id', '=', $course->course_id)
                            ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                            ->select('tbl_alumni.alumni_id')
                            ->distinct()
                            ->get();
                        $perCourse = Alumni::where('tbl_alumni.course_id', '=', $course->course_id)
                            ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                            ->select('tbl_alumni.alumni_id')
                            ->distinct()
                            ->get();
                    }
                    else {
                        $checkCount = Alumni::join('form_eif_answers', 'tbl_alumni.alumni_id', '=', 'form_eif_answers.alumni_id')
                            ->where('tbl_alumni.course_id', '=', $course->course_id)
                            ->where('tbl_alumni.sex', '=', $request->sex)
                            ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                            ->select('tbl_alumni.alumni_id')
                            ->distinct()
                            ->get();
                        $perCourse = Alumni::where('tbl_alumni.course_id', '=', $course->course_id)
                            ->where('tbl_alumni.sex', '=', $request->sex)
                            ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                            ->select('tbl_alumni.alumni_id')
                            ->distinct()
                            ->get();
                    }
                    $html .= '<tr>
                            <th class="th-EI" colspan="1" style="width: 70%;">' . $course->course_desc . '</th>
                            <td class="th-EI" colspan="2" style="width: 15%;">' . count($checkCount) . '</td>
                            <td class="th-EI" colspan="1" style="width: 15%;">' . count($perCourse)-count($checkCount) . '</td>
                        </tr>';
                }
            $total = Alumni::join('form_eif_answers', 'tbl_alumni.alumni_id', '=', 'form_eif_answers.alumni_id')
                            ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                            ->select('tbl_alumni.alumni_id')
                            ->distinct()
                            ->get();
            $totalStudents = Alumni::whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])->get();
            $totalPending = (count($totalStudents)-count($total));
            $html .= '<tr>
                    <th class="th-EI" colspan="1" style="width: 70%;">TOTAL</th>
                    <td class="th-EI" colspan="2" style="width: 15%; font-weight: bold;">' . count($total) . '</td>
                    <td class="th-EI" colspan="1" style="width: 15%; font-weight: bold;">' . $totalPending . '</td>
                </tr>';
            $html .= '</table>';
        $pdf->writeHTML($html, true, 0, true, 0);

        $totalPending = 0;
        $pdf->ln(2);
        $pdf->SetFont('times', 'B', 11);
        $pdf->MultiCell(88, 5, 'TABLE 2. AGE ', 0, 'L', 0, 0, '', '', true);
        $pdf->MultiCell(30, 5, 'TABLE 3. SEX', 0, 'R', 0, 0, '', '', true);
        $pdf->ln(5);
        $pdf->SetFont('times', '', 11);
        $html = '
                <table style="padding: 0px;">
                    <tr style="padding: 0px;">
                        <td style="padding: 0px;">';
        $html .= '<style>
                .table-EI, .th-EI, .td-EI {
                    border: 1px solid black;
                    border-collapse: collapse;
                    padding: 3px 5px;
                }
                .theading {
                    background-color: #78281F;
                    color: #ffffff;
                }
                th {
                    font-weight: bold;
                }
                td {
                    text-align: center;
                }
            </style>
            <table class="table-EI">
                <tr class="" style="text-align: center; font-weight: bold;">
                    <th class="theading" colspan="1" style="width: 35%;"></th>
                    <td class="theading" colspan="1" style="width: 18%;">Count</td>
                    <td class="theading" colspan="1" style="width: 25%;">Percentage</td>
                </tr>';
            $total = Alumni::join('form_eif_answers', 'tbl_alumni.alumni_id', '=', 'form_eif_answers.alumni_id')
                ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                ->select('tbl_alumni.alumni_id')
                ->distinct()
                ->get();
            $perAge = Alumni::join('form_eif_answers', 'tbl_alumni.alumni_id', '=', 'form_eif_answers.alumni_id')
                ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                ->where('course_id', 'like', '%' . $request->course_id . '%')
                ->select('tbl_alumni.age')
                ->orderBy('tbl_alumni.age', 'asc')
                ->distinct()
                ->get();


            foreach ($perAge as $age) {
                $perSpecificAge = Alumni::join('form_eif_answers', 'tbl_alumni.alumni_id', '=', 'form_eif_answers.alumni_id')
                    ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                    ->where('tbl_alumni.age', '=', $age->age)
                    ->where('tbl_alumni.sex', 'like', $request->sex . '%')
                    ->where('course_id', 'like', '%' . $request->course_id . '%')
                    ->select('tbl_alumni.alumni_id')
                    ->distinct()
                    ->get();
                $html .= '<tr>
                    <th class="th-EI" colspan="1" style="width: 35%;">' . $age->age . ' years old </th>
                    <td class="th-EI" colspan="2" style="width: 18%;">' . count($perSpecificAge) . '</td>
                    <td class="th-EI" colspan="1" style="width: 25%;">' . number_format(count($perSpecificAge) / count($total) * 100, 2) . '% </td>
                </tr>';
            }
        $html .= '</table>
            </td>
            <td style="padding: 0px;">';
        $html .= '
            <table class="table-EI">
                <tr class="" style="text-align: center; font-weight: bold;">
                    <th class="theading" colspan="1" style="width: 30%;"></th>
                    <td class="theading" colspan="1" style="width: 18%;">Count</td>
                    <td class="theading" colspan="1" style="width: 25%;">Percentage</td>
                </tr>';
            $total = Alumni::join('form_eif_answers', 'tbl_alumni.alumni_id', '=', 'form_eif_answers.alumni_id')
                ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                ->select('tbl_alumni.alumni_id')
                ->distinct()
                ->get();
            if ($request->sex == null || empty($request->sex)) {
                $perMale = Alumni::join('form_eif_answers', 'tbl_alumni.alumni_id', '=', 'form_eif_answers.alumni_id')
                    ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                    ->where('course_id', 'like', '%' . $request->course_id . '%')
                    ->where('tbl_alumni.sex', '=', 'Male')
                    ->select('tbl_alumni.alumni_id')
                    ->distinct()
                    ->get();
                $perFemale = Alumni::join('form_eif_answers', 'tbl_alumni.alumni_id', '=', 'form_eif_answers.alumni_id')
                    ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                    ->where('course_id', 'like', '%' . $request->course_id . '%')
                    ->where('tbl_alumni.sex', '=', 'Female')
                    ->select('tbl_alumni.alumni_id')
                    ->distinct()
                    ->get();
                $html .= '<tr>
                    <th class="th-EI" colspan="1" style="width: 30%;">MALE</th>
                    <td class="th-EI" colspan="2" style="width: 18%;">' . count($perMale) . '</td>
                    <td class="th-EI" colspan="1" style="width: 25%;">' . number_format(count($perMale) / count($total) * 100, 2) . '% </td>
                </tr>';
                $html .= '<tr>
                    <th class="th-EI" colspan="1" style="width: 30%;">FEMALE</th>
                    <td class="th-EI" colspan="2" style="width: 18%;">' . count($perFemale) .  '</td>
                    <td class="th-EI" colspan="1" style="width: 25%;">' . number_format(count($perFemale) / count($total) * 100, 2) . '% </td>
                </tr>';
            }
            else {
                $perSex = Alumni::join('form_eif_answers', 'tbl_alumni.alumni_id', '=', 'form_eif_answers.alumni_id')
                    ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                    ->where('tbl_alumni.sex', '=', $request->sex)
                    ->where('course_id', 'like', '%' . $request->course_id . '%')
                    ->select('tbl_alumni.alumni_id')
                    ->distinct()
                    ->get();
                $html .= '<tr>
                    <th class="th-EI" colspan="1" style="width: 30%;">' . strtoupper($request->sex) . '</th>
                    <td class="th-EI" colspan="2" style="width: 20%;">' . count($perSex) .  '</td>
                    <td class="th-EI" colspan="1" style="width: 20%;">' . number_format(count($perSex) / count($total) * 100, 2) . '% </td>
                </tr>';
            }

        $html .= '</table>';
        $html .= '</td>
                </tr>
            </table>';
        $pdf->writeHTML($html, true, 0, true, 0);

        $pdf->SetPrintHeader(true);
        $pdf->AddPage();
        $pdf->ln(15);
        $pdf->SetFont('times', 'B', 11);
        $pdf->Cell(0, 0, 'TABLE 4. REASON FOR LEAVING PUP TAGUIG', 0, 1, 'L', 0, '', 0);
        $pdf->SetFont('times', '', 11);
        $html = '<style>
                .table-EI, .th-EI, .td-EI {
                    border: 1px solid black;
                    border-collapse: collapse;
                    padding: 5px;
                }
                .theading {
                    background-color: #78281F;
                    color: #ffffff;
                }
                th {
                    font-weight: bold;
                }
                td {
                    text-align: center;
                }
            </style>
            <table class="table-EI" style="width:100%;">
                <tr class="" style="text-align: center; font-weight: bold;">
                    <th class="theading" colspan="1" style="width: 30%;"></th>
                    <td class="theading" colspan="1" style="width: 20%;">No. of Respondents</td>
                    <td class="theading" colspan="1" style="width: 20%;">Percentage</td>
                </tr>';
            $total = Alumni::join('form_eif_answers', 'tbl_alumni.alumni_id', '=', 'form_eif_answers.alumni_id')
                ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                ->select('tbl_alumni.alumni_id')
                ->distinct()
                ->get();
            $perAge = Alumni::join('form_eif_answers', 'tbl_alumni.alumni_id', '=', 'form_eif_answers.alumni_id')
                ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                ->where('course_id', 'like', '%' . $request->course_id . '%')
                ->where('tbl_alumni.sex', 'like', $request->sex . '%')
                ->select('tbl_alumni.age')
                ->distinct()
                ->get();

            $reasons = ['Graduation', 'Personal', 'Family', 'Academic', 'Financial', 'Work-related', 'Others'];

            $answerOthers = count($total);
            foreach ($reasons as $reason) {
                $perReasons = Alumni::join('form_eif_answers', 'tbl_alumni.alumni_id', '=', 'form_eif_answers.alumni_id')
                    ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                    ->where('form_eif_answers.answer', '=', $reason)
                    ->where('course_id', 'like', '%' . $request->course_id . '%')
                    ->where('tbl_alumni.sex', 'like', $request->sex . '%')
                    ->select('tbl_alumni.alumni_id')
                    ->distinct()
                    ->get();
                $answerOthers = $answerOthers - count($perReasons);
                if ($reason == 'Others') {
                    $perReasons = $answerOthers;
                }
                else {
                    $perReasons = count($perReasons);
                }
                $html .= '<tr>
                    <th class="th-EI" colspan="1" style="width: 30%;">' . $reason . '</th>
                    <td class="th-EI" colspan="2" style="width: 20%;">' . $perReasons . '</td>
                    <td class="th-EI" colspan="1" style="width: 20%;">' . number_format($perReasons / count($total) * 100, 2) . '% </td>
                </tr>';
            }
            $html .= '</table>';
        $pdf->writeHTML($html, true, 0, true, 0);

        $pdf->ln(3);
        $pdf->SetFont('times', 'B', 11);
        $pdf->Cell(0, 0, 'TABLE 5. STUDENT\'S UNIVERSITY EXPERIENCE', 0, 1, 'L', 0, '', 0);
        $pdf->SetFont('times', '', 11);
        $html = '<style>
                .table-EI, .th-EI, .td-EI {
                    border: 1px solid black;
                    border-collapse: collapse;
                    padding: 5px;
                }
                .theading {
                    background-color: #78281F;
                    color: #ffffff;
                }
                th {
                    font-weight: bold;
                }
                td {
                    text-align: center;
                }
            </style>
            <table class="table-EI" style="width:100%;">
                <tr class="theading" style="text-align: center; font-weight: bold;">
                    <th class="th-EI" colspan="1" style="width: 60%;"></th>
                    <td class="td-EI" colspan="1" style="width: 15%; height: 10px;line-height:5px;"><div style="vertical-align: middle;"><p>MEAN</p></div></td>
                    <td class="td-EI" colspan="1" style="width: 25%; height: 30px;line-height:15px;">VERBAL INTERPRETATION</td>
                </tr>';
                $eifQuestions = EifQuestions::where('category_id', '=', 3)->get();
                $totalMean = 0;
                $total = Alumni::join('form_eif_answers', 'tbl_alumni.alumni_id', '=', 'form_eif_answers.alumni_id')
                    ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                    ->select('tbl_alumni.alumni_id')
                    ->distinct()
                    ->get();
                foreach ($eifQuestions as $questions) {
                    $perAnswer = Alumni::join('form_eif_answers', 'tbl_alumni.alumni_id', '=', 'form_eif_answers.alumni_id')
                        ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                        ->where('course_id', 'like', '%' . $request->course_id . '%')
                        ->where('tbl_alumni.sex', 'like', $request->sex . '%')
                        ->where('form_eif_answers.question_id', '=', $questions->question_id)
                        ->select('form_eif_answers.answer')
                        ->sum('form_eif_answers.answer');
                    $mean = number_format($perAnswer / count($total), 2);
                    if ($mean == 5) {
                        $verbal = "Outstanding";
                    }
                    elseif ($mean >= 4 && $mean <= 4.99) {
                        $verbal = "Very Satisfactory";
                    }
                    elseif ($mean >= 3 && $mean <= 3.99) {
                        $verbal = "Satisfactory";
                    }
                    elseif ($mean >= 2 && $mean <= 2.99) {
                        $verbal = "Fair";
                    }
                    elseif ($mean >= 1 && $mean <= 1.99) {
                        $verbal = "Poor";
                    }
                    else {
                        $verbal = "Invalid Output";
                    }
                    $html .= '<tr>
                            <th class="th-EI" colspan="1" style="width: 60%;">' . $questions->question_text . '</th>
                            <td class="th-EI" colspan="2" style="width: 15%;">' . $mean . '</td>
                            <td class="th-EI" colspan="1" style="width: 25%;">' . $verbal . '</td>
                        </tr>';

                        $totalMean = $totalMean + $mean;
                    }
            $overallMean = number_format(($totalMean/7), 2);
            if ($overallMean == 5) {
                $overallVerbal = "Outstanding";
            }
            elseif ($overallMean >= 4 && $overallMean <= 4.99) {
                $overallVerbal = "Very Satisfactory";
            }
            elseif ($overallMean >= 3 && $overallMean <= 3.99) {
                $overallVerbal = "Satisfactory";
            }
            elseif ($overallMean >= 2 && $overallMean <= 2.99) {
                $overallVerbal = "Fair";
            }
            elseif ($overallMean >= 1 && $overallMean <= 1.99) {
                $overallVerbal = "Poor";
            }
            else {
                $overallVerbal = "Invalid Output";
            }
            $html .= '<tr>
                    <th class="th-EI" colspan="1" style="width: 60%;">TOTAL GRADE</th>
                    <td class="th-EI" colspan="2" style="width: 15%; font-weight: bold;">' . $overallMean . '</td>
                    <td class="th-EI" colspan="1" style="width: 25%; font-weight: bold;">' . $overallVerbal . '</td>
                </tr>';
            $html .= '</table>';
        $pdf->writeHTML($html, true, 0, true, 0);

        $numeral = 6;
        $eifCategories = EifCategories::whereIn('category_id', [4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15])->get();
        foreach ($eifCategories as $category) {
            if ($numeral == 7 || $numeral == 11 || $numeral == 15) {
                $pdf->AddPage();
                $pdf->ln(15);
            }
            $pdf->ln(3);
            $pdf->SetFont('times', 'B', 11);
            $pdf->Cell(0, 0, 'TABLE ' . $numeral . '. ' . strtoupper($category->category_name), 0, 1, 'L', 0, '', 0);
            $pdf->SetFont('times', '', 11);
            $html = '<style>
                    .table-EI, .th-EI, .td-EI {
                        border: 1px solid black;
                        border-collapse: collapse;
                        padding: 5px;
                    }
                    .theading {
                        background-color: #78281F;
                        color: #ffffff;
                    }
                    th {
                        font-weight: bold;
                    }
                    td {
                        text-align: center;
                    }
                </style>
                <table class="table-EI" style="width:100%;">
                    <tr class="theading" style="text-align: center; font-weight: bold;">
                        <th class="th-EI" colspan="1" style="width: 60%;"></th>
                        <td class="td-EI" colspan="1" style="width: 15%; height: 10px;line-height:5px;"><div style="vertical-align: middle;"><p>MEAN</p></div></td>
                        <td class="td-EI" colspan="1" style="width: 25%; height: 30px;line-height:15px;">VERBAL INTERPRETATION</td>
                    </tr>';
                    $eifQuestions = EifQuestions::where('category_id', '=', $category->category_id)->get();
                    $totalMean = 0;
                    $total = Alumni::join('form_eif_answers', 'tbl_alumni.alumni_id', '=', 'form_eif_answers.alumni_id')
                        ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                        ->select('tbl_alumni.alumni_id')
                        ->distinct()
                        ->get();
                    foreach ($eifQuestions as $questions) {
                        $perAnswer = Alumni::join('form_eif_answers', 'tbl_alumni.alumni_id', '=', 'form_eif_answers.alumni_id')
                            ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                            ->where('course_id', 'like', '%' . $request->course_id . '%')
                            ->where('tbl_alumni.sex', 'like', $request->sex . '%')
                            ->where('form_eif_answers.question_id', '=', $questions->question_id)
                            ->select('form_eif_answers.answer')
                            ->sum('form_eif_answers.answer');
                        $mean = number_format($perAnswer / count($total), 2);
                        if ($mean == 5) {
                            $verbal = "Outstanding";
                        }
                        elseif ($mean >= 4 && $mean <= 4.99) {
                            $verbal = "Very Satisfactory";
                        }
                        elseif ($mean >= 3 && $mean <= 3.99) {
                            $verbal = "Satisfactory";
                        }
                        elseif ($mean >= 2 && $mean <= 2.99) {
                            $verbal = "Fair";
                        }
                        elseif ($mean >= 1 && $mean <= 1.99) {
                            $verbal = "Poor";
                        }
                        else {
                            $verbal = "Invalid Output";
                        }
                        $html .= '<tr>
                                <td class="th-EI" colspan="1" style="width: 60%; text-align: left;">' . $questions->question_text . '</td>
                                <td class="th-EI" colspan="2" style="width: 15%;">' . $mean . '</td>
                                <td class="th-EI" colspan="1" style="width: 25%;">' . $verbal . '</td>
                            </tr>';

                            $totalMean = $totalMean + $mean;
                        }
                $overallMean = number_format(($totalMean/3), 2);
                if ($overallMean == 5) {
                    $overallVerbal = "Outstanding";
                }
                elseif ($overallMean >= 4 && $overallMean <= 4.99) {
                    $overallVerbal = "Very Satisfactory";
                }
                elseif ($overallMean >= 3 && $overallMean <= 3.99) {
                    $overallVerbal = "Satisfactory";
                }
                elseif ($overallMean >= 2 && $overallMean <= 2.99) {
                    $overallVerbal = "Fair";
                }
                elseif ($overallMean >= 1 && $overallMean <= 1.99) {
                    $overallVerbal = "Poor";
                }
                else {
                    $overallVerbal = "Invalid Output";
                }
                $html .= '<tr>
                        <th class="th-EI" colspan="1" style="width: 60%;">TOTAL GRADE</th>
                        <td class="th-EI" colspan="2" style="width: 15%; font-weight: bold;">' . $overallMean . '</td>
                        <td class="th-EI" colspan="1" style="width: 25%; font-weight: bold;">' . $overallVerbal . '</td>
                    </tr>';
                $html .= '</table>';
            $pdf->writeHTML($html, true, 0, true, 0);

            $numeral++;
        }

        $pdf->lastPage();
        $user = auth()->user();
        Log::channel('admin')->info("UserId:".$user->user_id." | AdminId: ".$user->admin_id." | Username: ".$user->name." - Generated an EIF Summary Report");
        $pdf->Output('EIF_Summary_Report_' . date('m-d-y') . '.pdf', 'I');
    }

    public function SasSummaryReport($request) {
        $totalPending = 0;
        if ($request->sex == null || empty($request->sex)) {
            $allAlumni = Alumni::where('course_id', 'like', '%' . $request->course_id . '%')
                ->whereBetween('batch', [$request->batch_from, $request->batch_to])
                ->orderBy('last_name', 'asc')
                ->get();
        }
        else {
            $allAlumni = Alumni::where('course_id', 'like', '%' . $request->course_id . '%')
                ->where('sex', '=', $request->sex)
                ->whereBetween('batch', [$request->batch_from, $request->batch_to])
                ->orderBy('last_name', 'asc')
                ->get();
        }

        $courses = Courses::where('course_id', 'like', '%' . $request->course_id . '%')->get();

        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetTitle('SAS_SUMMARY_REPORTS');
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        $pdf->SetPrintHeader(true);
        $pdf->AddPage();
        $pdf->SetFont('times', 'B', 13);
        $pdf->ln(10);
        $pdf->Cell(0, 0, 'STUDENT AFFAIRS AND SERVICESS FORM - SUMMARY REPORT', 0, 1, 'C', 0, '', 0);
        $pdf->SetFont('times', '', 12);
        $pdf->Cell(0, 0, 'ALUMNI BATCH FROM ' . $request->batch_from . ' TO ' . $request->batch_to, 0, 1, 'C', 0, '', 0);
        $pdf->SetFont('times', '', 11);
        $pdf->Cell(0, 0, 'Date Generated: ' . date('F d, Y'), 0, 1, 'C', 0, '', 0);

        $pdf->ln(5);
        $pdf->SetFont('times', 'B', 11);
        $pdf->Cell(0, 0, 'TABLE 1. COURSES', 0, 1, 'L', 0, '', 0);
        $pdf->SetFont('times', '', 11);
        $html = '<style>
                .table-EI, .th-EI, .td-EI {
                    border: 1px solid black;
                    border-collapse: collapse;
                    padding: 5px;
                }
                .theading {
                    background-color: #78281F;
                    color: #ffffff;
                }
                th {
                    font-weight: bold;
                }
                td {
                    text-align: center;
                }
            </style>
            <table class="table-EI" style="width:100%;">
                <tr class="theading" style="text-align: center; font-weight: bold;">
                    <th class="" colspan="1" style="width: 70%;">Course</th>
                    <td class="" colspan="1" style="width: 15%;">Complete</td>
                    <td class="" colspan="1" style="width: 15%;">Pending</td>
                </tr>';
                foreach ($courses as $course) {
                    if ($request->sex == null || empty($request->sex)) {
                        $checkCount = Alumni::join('form_sas_answers', 'tbl_alumni.alumni_id', '=', 'form_sas_answers.alumni_id')
                            ->where('tbl_alumni.course_id', '=', $course->course_id)
                            ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                            ->select('tbl_alumni.alumni_id')
                            ->distinct()
                            ->get();
                        $perCourse = Alumni::where('tbl_alumni.course_id', '=', $course->course_id)
                            ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                            ->select('tbl_alumni.alumni_id')
                            ->distinct()
                            ->get();
                    }
                    else {
                        $checkCount = Alumni::join('form_sas_answers', 'tbl_alumni.alumni_id', '=', 'form_sas_answers.alumni_id')
                            ->where('tbl_alumni.course_id', '=', $course->course_id)
                            ->where('tbl_alumni.sex', '=', $request->sex)
                            ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                            ->select('tbl_alumni.alumni_id')
                            ->distinct()
                            ->get();
                        $perCourse = Alumni::where('tbl_alumni.course_id', '=', $course->course_id)
                            ->where('tbl_alumni.sex', '=', $request->sex)
                            ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                            ->select('tbl_alumni.alumni_id')
                            ->distinct()
                            ->get();
                    }
                    $html .= '<tr>
                            <th class="th-EI" colspan="1" style="width: 70%;">' . $course->course_desc . '</th>
                            <td class="th-EI" colspan="2" style="width: 15%;">' . count($checkCount) . '</td>
                            <td class="th-EI" colspan="1" style="width: 15%;">' . count($perCourse)-count($checkCount) . '</td>
                        </tr>';
                }
            $total = Alumni::join('form_sas_answers', 'tbl_alumni.alumni_id', '=', 'form_sas_answers.alumni_id')
                        ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                        ->select('tbl_alumni.alumni_id')
                        ->distinct()
                        ->get();
            $totalStudents = Alumni::whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])->get();
            $totalPending = (count($totalStudents)-count($total));
            $html .= '<tr>
                    <th class="th-EI" colspan="1" style="width: 70%;">TOTAL</th>
                    <td class="th-EI" colspan="2" style="width: 15%; font-weight: bold;">' . count($total) . '</td>
                    <td class="th-EI" colspan="1" style="width: 15%; font-weight: bold;">' . $totalPending . '</td>
                </tr>';
            $html .= '</table>';
        $pdf->writeHTML($html, true, 0, true, 0);

        $totalPending = 0;
        $pdf->ln(2);
        $pdf->SetFont('times', 'B', 11);
        $pdf->MultiCell(88, 5, 'TABLE 2. AGE ', 0, 'L', 0, 0, '', '', true);
        $pdf->MultiCell(30, 5, 'TABLE 3. SEX', 0, 'R', 0, 0, '', '', true);
        $pdf->ln(5);
        $pdf->SetFont('times', '', 11);
        $html = '
                <table style="padding: 0px;">
                    <tr style="padding: 0px;">
                        <td style="padding: 0px;">';
        $html .= '<style>
                .table-EI, .th-EI, .td-EI {
                    border: 1px solid black;
                    border-collapse: collapse;
                    padding: 3px 5px;
                }
                .theading {
                    background-color: #78281F;
                    color: #ffffff;
                }
                th {
                    font-weight: bold;
                }
                td {
                    text-align: center;
                }
            </style>
            <table class="table-EI">
                <tr class="" style="text-align: center; font-weight: bold;">
                    <th class="theading" colspan="1" style="width: 35%;"></th>
                    <td class="theading" colspan="1" style="width: 18%;">Count</td>
                    <td class="theading" colspan="1" style="width: 25%;">Percentage</td>
                </tr>';
            $total = Alumni::join('form_sas_answers', 'tbl_alumni.alumni_id', '=', 'form_sas_answers.alumni_id')
                ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                ->select('tbl_alumni.alumni_id')
                ->distinct()
                ->get();
            $perAge = Alumni::join('form_sas_answers', 'tbl_alumni.alumni_id', '=', 'form_sas_answers.alumni_id')
                ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                ->where('course_id', 'like', '%' . $request->course_id . '%')
                ->where('tbl_alumni.age', '!=', null)
                ->select('tbl_alumni.age')
                ->orderBy('tbl_alumni.age', 'asc')
                ->distinct()
                ->get();


            foreach ($perAge as $age) {
                $perSpecificAge = Alumni::join('form_sas_answers', 'tbl_alumni.alumni_id', '=', 'form_sas_answers.alumni_id')
                    ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                    ->where('tbl_alumni.age', '=', $age->age)
                    ->where('tbl_alumni.sex', 'like', $request->sex . '%')
                    ->where('course_id', 'like', '%' . $request->course_id . '%')
                    ->select('tbl_alumni.alumni_id')
                    ->distinct()
                    ->get();
                $html .= '<tr>
                    <th class="th-EI" colspan="1" style="width: 35%;">' . $age->age . ' years old </th>
                    <td class="th-EI" colspan="2" style="width: 18%;">' . count($perSpecificAge) . '</td>
                    <td class="th-EI" colspan="1" style="width: 25%;">' . number_format(count($perSpecificAge) / count($total) * 100, 2) . '% </td>
                </tr>';
            }
        $html .= '</table>
            </td>
            <td style="padding: 0px;">';
        $html .= '
            <table class="table-EI">
                <tr class="" style="text-align: center; font-weight: bold;">
                    <th class="theading" colspan="1" style="width: 30%;"></th>
                    <td class="theading" colspan="1" style="width: 18%;">Count</td>
                    <td class="theading" colspan="1" style="width: 25%;">Percentage</td>
                </tr>';
            $total = Alumni::join('form_sas_answers', 'tbl_alumni.alumni_id', '=', 'form_sas_answers.alumni_id')
                ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                ->select('tbl_alumni.alumni_id')
                ->distinct()
                ->get();
            if ($request->sex == null || empty($request->sex)) {
                $perMale = Alumni::join('form_sas_answers', 'tbl_alumni.alumni_id', '=', 'form_sas_answers.alumni_id')
                    ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                    ->where('course_id', 'like', '%' . $request->course_id . '%')
                    ->where('tbl_alumni.sex', '=', 'Male')
                    ->select('tbl_alumni.alumni_id')
                    ->distinct()
                    ->get();
                $perFemale = Alumni::join('form_sas_answers', 'tbl_alumni.alumni_id', '=', 'form_sas_answers.alumni_id')
                    ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                    ->where('course_id', 'like', '%' . $request->course_id . '%')
                    ->where('tbl_alumni.sex', '=', 'Female')
                    ->select('tbl_alumni.alumni_id')
                    ->distinct()
                    ->get();
                $html .= '<tr>
                    <th class="th-EI" colspan="1" style="width: 30%;">MALE</th>
                    <td class="th-EI" colspan="2" style="width: 18%;">' . count($perMale) . '</td>
                    <td class="th-EI" colspan="1" style="width: 25%;">' . number_format(count($perMale) / count($total) * 100, 2) . '% </td>
                </tr>';
                $html .= '<tr>
                    <th class="th-EI" colspan="1" style="width: 30%;">FEMALE</th>
                    <td class="th-EI" colspan="2" style="width: 18%;">' . count($perFemale) .  '</td>
                    <td class="th-EI" colspan="1" style="width: 25%;">' . number_format(count($perFemale) / count($total) * 100, 2) . '% </td>
                </tr>';
            }
            else {
                $perSex = Alumni::join('form_sas_answers', 'tbl_alumni.alumni_id', '=', 'form_sas_answers.alumni_id')
                    ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                    ->where('tbl_alumni.sex', '=', $request->sex)
                    ->where('course_id', 'like', '%' . $request->course_id . '%')
                    ->select('tbl_alumni.alumni_id')
                    ->distinct()
                    ->get();
                $html .= '<tr>
                    <th class="th-EI" colspan="1" style="width: 30%;">' . strtoupper($request->sex) . '</th>
                    <td class="th-EI" colspan="2" style="width: 20%;">' . count($perSex) .  '</td>
                    <td class="th-EI" colspan="1" style="width: 20%;">' . number_format(count($perSex) / count($total) * 100, 2) . '% </td>
                </tr>';
            }

        $html .= '</table>';
        $html .= '</td>
                </tr>
            </table>';
        $pdf->writeHTML($html, true, 0, true, 0);

        $sasCategories = SasCategories::whereIn('category_id', [3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14])->get();
        $numeral = 4;
        foreach ($sasCategories as $categories) {
            if ($numeral == 4 || $numeral == 5 || $numeral == 7 || $numeral == 9 || $numeral == 11 || $numeral == 14) {
                $pdf->SetPrintHeader(true);
                $pdf->AddPage();
                $pdf->SetPrintHeader(false);
                $pdf->ln(20);
            }
            else {
                $pdf->ln(10);
            }
            $pdf->SetFont('times', 'B', 11);
            $pdf->Cell(0, 0, 'TABLE ' . $numeral . '. ' . strtoupper($categories->category_name), 0, 1, 'L', 0, '', 0);
            $pdf->SetFont('times', '', 11);
            $totalMean = 0;
            $total = Alumni::join('form_sas_answers', 'tbl_alumni.alumni_id', '=', 'form_sas_answers.alumni_id')
                ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                ->select('tbl_alumni.alumni_id')
                ->distinct()
                ->get();
            $sasQuestions = SasQuestions::where('category_id', '=', $categories->category_id)->get();
            $html = '<style>
                    .table-EI, .th-EI, .td-EI {
                        border: 1px solid black;
                        border-collapse: collapse;
                        padding: 5px;
                    }
                    .theading {
                        background-color: #78281F;
                        color: #ffffff;
                    }
                    th {
                        font-weight: bold;
                    }
                    td {
                        text-align: center;
                    }
                </style>
                <table class="table-EI" style="width:100%;">
                    <tr class="theading" style="text-align: center; font-weight: bold;">
                        <th class="th-EI" colspan="1" style="width: 60%;"></th>
                        <td class="td-EI" colspan="1" style="width: 15%; height: 10px;line-height:5px;"><div style="vertical-align: middle;"><p>MEAN</p></div></td>
                        <td class="td-EI" colspan="1" style="width: 25%; height: 30px;line-height:15px;">VERBAL INTERPRETATION</td>
                    </tr>';
            foreach ($sasQuestions as $questions) {
                $perAnswer = Alumni::join('form_sas_answers', 'tbl_alumni.alumni_id', '=', 'form_sas_answers.alumni_id')
                        ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                        ->where('course_id', 'like', '%' . $request->course_id . '%')
                        ->where('tbl_alumni.sex', 'like', $request->sex . '%')
                        ->where('form_sas_answers.question_id', '=', $questions->question_id)
                        ->select('form_sas_answers.answer')
                        ->sum('form_sas_answers.answer');
                $mean = number_format($perAnswer / count($total), 2);
                if ($mean >= 1 && $mean <= 1.99) {
                    $verbal = "Very Satisfactory";
                }
                elseif ($mean >= 2 && $mean <= 2.99) {
                    $verbal = "Satisfactory";
                }
                elseif ($mean >= 3 && $mean <= 3.99) {
                    $verbal = "Unsatisfactory";
                }
                elseif ($mean == 4) {
                    $verbal = "Very Unsatisfactory";
                }
                else {
                    $verbal = "Invalid Output";
                }

                $html .= '<tr>
                    <td class="th-EI" colspan="1" style="width: 60%; text-align: left;">' . $questions->question_text . '</td>
                    <td class="th-EI" colspan="2" style="width: 15%;">' . $mean . '</td>
                    <td class="th-EI" colspan="1" style="width: 25%;">' . $verbal . '</td>
                    </tr>';
                $totalMean = $totalMean + $mean;
            }

            $numOfQuestions = SasQuestions::where('category_id', '=', $categories->category_id)->get();
            $overallMean = number_format($totalMean / count($numOfQuestions), 2);
            if ($mean >= 1 && $mean <= 1.99) {
                $overallVerbal = "Very Satisfactory";
            }
            elseif ($mean >= 2 && $mean <= 2.99) {
                $overallVerbal = "Satisfactory";
            }
            elseif ($mean >= 3 && $mean <= 3.99) {
                $overallVerbal = "Unsatisfactory";
            }
            elseif ($mean == 4) {
                $overallVerbal = "Very Unsatisfactory";
            }
            else {
                $overallVerbal = "Invalid Output";
            }
            $html .= '<tr style="background-color: #D6EAF8;">
                    <th class="th-EI" colspan="1" style="width: 60%;">TOTAL GRADE</th>
                    <td class="th-EI" colspan="2" style="width: 15%; font-weight: bold;">' . $overallMean . '</td>
                    <td class="th-EI" colspan="1" style="width: 25%; font-weight: bold;">' . $overallVerbal . '</td>
                </tr>';
            $html .= '</table>';
            $pdf->writeHTML($html, true, 0, true, 0);
            $numeral++;
        }
        $pdf->lastPage();

        $user = auth()->user();
        Log::channel('admin')->info("UserId:".$user->user_id." | AdminId: ".$user->admin_id." | Username: ".$user->name." - Generated an SAS Summary Report");
        $pdf->Output('SAS_Summary_Report_' . date('m-d-y') . '.pdf', 'I');
    }

    public function PdsStatusReport($request) {
        $ctr = 0;
        $totalPending = 0;
        if ($request->sex == null || empty($request->sex)) {
            $allAlumni = Alumni::where('course_id', 'like', '%' . $request->course_id . '%')
                ->whereBetween('batch', [$request->batch_from, $request->batch_to])
                ->orderBy('last_name', 'asc')
                ->get();
        }
        else {
            $allAlumni = Alumni::where('course_id', 'like', '%' . $request->course_id . '%')
                ->where('sex', '=', $request->sex)
                ->whereBetween('batch', [$request->batch_from, $request->batch_to])
                ->orderBy('last_name', 'asc')
                ->get();
        }

        $courses = Courses::where('course_id', 'like', '%' . $request->course_id . '%')->get();

        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetTitle('PDS_STATUS_REPORTS');
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        $pdf->SetPrintHeader(true);
        $pdf->AddPage();
        $pdf->SetPrintHeader(false);
        $pdf->SetFont('times', 'B', 13);
        $pdf->ln(20);
        $pdf->Cell(0, 0, 'PERSONAL DATA SHEET - STATUS REPORT (SUMMARY)', 0, 1, 'C', 0, '', 0);
        $pdf->SetFont('times', '', 12);
        $pdf->Cell(0, 0, 'ALUMNI BATCH FROM ' . $request->batch_from . ' TO ' . $request->batch_to, 0, 1, 'C', 0, '', 0);

        $pdf->ln(10);
        $pdf->SetFont('times', '', 11);
        $pdf->Cell(0, 0, 'Date Generated: ' . date('F d, Y'), 0, 1, 'R', 0, '', 0);

        $html = '<style>
                .table-EI, .th-EI, .td-EI {
                    border: 1px solid black;
                    border-collapse: collapse;
                    padding: 8px;
                }
                .theading {
                    background-color: #78281F;
                    color: #ffffff;
                }
                th {
                    font-weight: bold;
                }
                td {
                    text-align: center;
                }
            </style>
            <table class="table-EI" style="width:100%;">
                <tr class="theading" style="text-align: center; font-weight: bold;">
                    <th class="" colspan="1" style="width: 70%;">Course</th>
                    <td class="" colspan="1" style="width: 15%;">Complete</td>
                    <td class="" colspan="1" style="width: 15%;">Pending</td>
                </tr>';
                foreach ($courses as $course) {
                    if ($request->sex == null || empty($request->sex)) {
                        $checkCount = Alumni::join('form_pds_answers', 'tbl_alumni.alumni_id', '=', 'form_pds_answers.alumni_id')
                            ->where('tbl_alumni.course_id', '=', $course->course_id)
                            ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                            ->select('tbl_alumni.alumni_id')
                            ->distinct()
                            ->get();
                        $perCourse = Alumni::where('tbl_alumni.course_id', '=', $course->course_id)
                            ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                            ->select('tbl_alumni.alumni_id')
                            ->distinct()
                            ->get();
                        // $total = Alumni::join('form_pds_answers', 'tbl_alumni.alumni_id', '=', 'form_pds_answers.alumni_id')
                        //     ->where('course_id', 'like', '%' . $request->course_id . '%')
                        //     ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                        //     ->select('tbl_alumni.alumni_id')
                        //     ->distinct()
                        //     ->get();
                    }
                    else {
                        $checkCount = Alumni::join('form_pds_answers', 'tbl_alumni.alumni_id', '=', 'form_pds_answers.alumni_id')
                            ->where('tbl_alumni.course_id', '=', $course->course_id)
                            ->where('tbl_alumni.sex', '=', $request->sex)
                            ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                            ->select('tbl_alumni.alumni_id')
                            ->distinct()
                            ->get();
                        $perCourse = Alumni::where('tbl_alumni.course_id', '=', $course->course_id)
                            ->where('tbl_alumni.sex', '=', $request->sex)
                            ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                            ->select('tbl_alumni.alumni_id')
                            ->distinct()
                            ->get();
                        // $total = Alumni::join('form_pds_answers', 'tbl_alumni.alumni_id', '=', 'form_pds_answers.alumni_id')
                        //     ->where('tbl_alumni.sex', '=', $request->sex)
                        //     ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                        //     ->select('tbl_alumni.alumni_id')
                        //     ->distinct()
                        //     ->get();
                    }
                    $html .= '<tr>
                            <th class="th-EI" colspan="1" style="width: 70%;">' . $course->course_desc . '</th>
                            <td class="th-EI" colspan="2" style="width: 15%;">' . count($checkCount) . '</td>
                            <td class="th-EI" colspan="1" style="width: 15%;">' . count($perCourse)-count($checkCount) . '</td>
                        </tr>';
                }
            $total = Alumni::join('form_pds_answers', 'tbl_alumni.alumni_id', '=', 'form_pds_answers.alumni_id')
                            ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                            ->select('tbl_alumni.alumni_id')
                            ->distinct()
                            ->get();
            $totalStudents = Alumni::whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])->get();
            $totalPending = (count($totalStudents)-count($total));
            $html .= '<tr>
                    <th class="th-EI" colspan="1" style="width: 70%;">TOTAL</th>
                    <td class="th-EI" colspan="2" style="width: 15%; font-weight: bold;">' . count($total) . '</td>
                    <td class="th-EI" colspan="1" style="width: 15%; font-weight: bold;">' . $totalPending . '</td>
                </tr>';
            $html .= '</table>';
        $pdf->writeHTML($html, true, 0, true, 0);

        $pdf->lastPage();

        foreach ($courses as $course) {
            if ($request->sex == null || empty($request->sex)) {
                $checkCount = Alumni::join('form_pds_answers', 'tbl_alumni.alumni_id', '=', 'form_pds_answers.alumni_id')
                    ->where('tbl_alumni.course_id', '=', $course->course_id)
                    ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                    ->select('tbl_alumni.alumni_id')
                    ->get();
            }
            else {
                $checkCount = Alumni::join('form_pds_answers', 'tbl_alumni.alumni_id', '=', 'form_pds_answers.alumni_id')
                    ->where('tbl_alumni.course_id', '=', $course->course_id)
                    ->where('tbl_alumni.sex', '=', $request->sex)
                    ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                    ->select('tbl_alumni.alumni_id')
                    ->get();
            }

            if (count($checkCount) > 0) {
                $pdf->SetPrintHeader(true);
                $pdf->AddPage();
                $pdf->SetPrintHeader(false);
                $pdf->SetFont('times', 'B', 13);
                $pdf->ln(18);
                $pdf->Cell(0, 0, 'PERSONAL DATA SHEET - STATUS REPORT (COMPLETE)', 0, 1, 'C', 0, '', 0);
                $pdf->SetFont('times', '', 12);
                $pdf->MultiCell(180, 5, strtoupper($course->course_desc), 0, 'C', 0, 1, '', '', true);
                $pdf->Cell(0, 0, 'ALUMNI BATCH FROM ' . $request->batch_from . ' TO ' . $request->batch_to, 0, 1, 'C', 0, '', 0);

                $pdf->ln(10);
                $pdf->SetFont('times', '', 11);
                $pdf->Cell(0, 0, 'Date Generated: ' . date('F d, Y'), 0, 1, 'R', 0, '', 0);

                $html = '<style>
                        .table-EI, .th-EI, .td-EI {
                            border: 1px solid black;
                            border-collapse: collapse;
                            padding: 5px;
                        }
                        .theading {
                            background-color: #78281F;
                            color: #ffffff;
                        }
                        th {
                            font-weight: bold;
                        }
                    </style>
                    <table class="table-EI" style="width:100%;">
                        <tr class="theading" style="text-align: center; font-weight: bold;">
                            <th class="" colspan="1" style="width: 25%;">Student Number</th>
                            <td class="" colspan="1" style="width: 65%;">Full Name</td>
                            <td class="" colspan="1" style="width: 10%;">Batch</td>
                        </tr>';
                    foreach ($allAlumni as $alumni) {
                        if ($alumni->course_id == $course->course_id) {
                            $checkPDS = PdsAnswers::where('alumni_id', '=', $alumni->alumni_id)->get();
                            if (count($checkPDS) > 0) {
                                $html .= '<tr>
                                        <th class="th-EI" colspan="1" style="width: 25%; text-align: center;">' . $alumni->stud_number . '</th>
                                        <td class="th-EI" colspan="2" style="width: 65%;">' . strtoupper($alumni->last_name) . ', ' . strtoupper($alumni->first_name) . ' ' . strtoupper($alumni->suffix) . ' ' . strtoupper($alumni->middle_name) . '</td>
                                        <th class="th-EI" colspan="1" style="width: 10%; text-align: center;">' . $alumni->batch . '</th>
                                    </tr>';
                            }
                        }
                    }
                    $html .= '</table>';
                $pdf->writeHTML($html, true, 0, true, 0);

                $pdf->lastPage();
            }
        }

        foreach ($courses as $course) {
            $counter = 0;
            foreach ($allAlumni as $alumni) {
                if ($alumni->course_id == $course->course_id) {
                    $checkPDS = PdsAnswers::where('alumni_id', '=', $alumni->alumni_id)->get();
                    if (count($checkPDS) == 0) {
                        $counter++;
                    }
                }
            }

            if ($counter > 0) {
                $pdf->SetPrintHeader(true);
                $pdf->AddPage();
                $pdf->SetPrintHeader(false);
                $pdf->SetFont('times', 'B', 13);
                $pdf->ln(20);
                $pdf->Cell(0, 0, 'PERSONAL DATA SHEET - STATUS REPORT (PENDING)', 0, 1, 'C', 0, '', 0);
                $pdf->SetFont('times', '', 12);
                $pdf->MultiCell(180, 5, strtoupper($course->course_desc), 0, 'C', 0, 1, '', '', true);
                $pdf->Cell(0, 0, 'ALUMNI BATCH FROM ' . $request->batch_from . ' TO ' . $request->batch_to, 0, 1, 'C', 0, '', 0);

                $pdf->ln(10);
                $pdf->SetFont('times', '', 11);
                $pdf->Cell(0, 0, 'Date Generated: ' . date('F d, Y'), 0, 1, 'R', 0, '', 0);

                $html = '<style>
                        .table-EI, .th-EI, .td-EI {
                            border: 1px solid black;
                            border-collapse: collapse;
                            padding: 5px;
                        }
                        .theading {
                            background-color: #78281F;
                            color: #ffffff;
                        }
                        th {
                            font-weight: bold;
                        }
                    </style>
                    <table class="table-EI" style="width:100%;">
                        <tr class="theading" style="text-align: center; font-weight: bold;">
                            <th class="" colspan="1" style="width: 25%;">Student Number</th>
                            <td class="" colspan="1" style="width: 65%;">Full Name</td>
                            <td class="" colspan="1" style="width: 10%;">Batch</td>
                        </tr>';
                    foreach ($allAlumni as $alumni) {
                        if ($alumni->course_id == $course->course_id) {
                            $checkPDS = PdsAnswers::where('alumni_id', '=', $alumni->alumni_id)->get();
                            if (count($checkPDS) == 0) {
                                $html .=
                                    '<tr>
                                        <th class="th-EI" colspan="1" style="width: 25%; text-align: center;">' . $alumni->stud_number . '</th>
                                        <td class="th-EI" colspan="2" style="width: 65%;">' . strtoupper($alumni->last_name) . ', ' . strtoupper($alumni->first_name) . ' ' . strtoupper($alumni->suffix) . ' ' . strtoupper($alumni->middle_name) . '</td>
                                        <th class="th-EI" colspan="1" style="width: 10%; text-align: center;">' . $alumni->batch . '</th>
                                    </tr>';
                            }
                        }
                    }
                    $html .= '</table>';
                $pdf->writeHTML($html, true, 0, true, 0);

                $pdf->lastPage();
            }
        }

        $user = auth()->user();
        Log::channel('admin')->info("UserId:".$user->user_id." | AdminId: ".$user->admin_id." | Username: ".$user->name." - Generated an PDS Status Report");
        $pdf->Output('PDS_Status_Report_' . date('m-d-y') . '.pdf', 'I');
    }

    public function EifStatusReport($request) {
        $ctr = 0;
        $totalPending = 0;
        if ($request->sex == null || empty($request->sex)) {
            $allAlumni = Alumni::where('course_id', 'like', '%' . $request->course_id . '%')
                ->whereBetween('batch', [$request->batch_from, $request->batch_to])
                ->orderBy('last_name', 'asc')
                ->get();
        }
        else {
            $allAlumni = Alumni::where('course_id', 'like', '%' . $request->course_id . '%')
                ->where('sex', '=', $request->sex)
                ->whereBetween('batch', [$request->batch_from, $request->batch_to])
                ->orderBy('last_name', 'asc')
                ->get();
        }

        $courses = Courses::where('course_id', 'like', '%' . $request->course_id . '%')->get();

        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetTitle('EIF_STATUS_REPORTS');
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        $pdf->SetPrintHeader(true);
        $pdf->AddPage();
        $pdf->SetPrintHeader(false);
        $pdf->SetFont('times', 'B', 13);
        $pdf->ln(10);
        $pdf->Cell(0, 0, 'EXIT INTERVIEW FORM - STATUS REPORT (SUMMARY)', 0, 1, 'C', 0, '', 0);
        $pdf->SetFont('times', '', 12);
        $pdf->Cell(0, 0, 'ALUMNI BATCH FROM ' . $request->batch_from . ' TO ' . $request->batch_to, 0, 1, 'C', 0, '', 0);

        $pdf->ln(5);
        $pdf->SetFont('times', '', 11);
        $pdf->Cell(0, 0, 'Date Generated: ' . date('F d, Y'), 0, 1, 'R', 0, '', 0);

        $html = '<style>
                .table-EI, .th-EI, .td-EI {
                    border: 1px solid black;
                    border-collapse: collapse;
                    padding: 5px;
                }
                .theading {
                    background-color: #78281F;
                    color: #ffffff;
                }
                th {
                    font-weight: bold;
                }
                td {
                    text-align: center;
                }
            </style>
            <table class="table-EI" style="width:100%;">
                <tr class="theading" style="text-align: center; font-weight: bold;">
                    <th class="" colspan="1" style="width: 70%;">Course</th>
                    <td class="" colspan="1" style="width: 15%;">Complete</td>
                    <td class="" colspan="1" style="width: 15%;">Pending</td>
                </tr>';
                foreach ($courses as $course) {
                    if ($request->sex == null || empty($request->sex)) {
                        $checkCount = Alumni::join('form_eif_answers', 'tbl_alumni.alumni_id', '=', 'form_eif_answers.alumni_id')
                            ->where('tbl_alumni.course_id', '=', $course->course_id)
                            ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                            ->select('tbl_alumni.alumni_id')
                            ->distinct()
                            ->get();
                        $perCourse = Alumni::where('tbl_alumni.course_id', '=', $course->course_id)
                            ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                            ->select('tbl_alumni.alumni_id')
                            ->distinct()
                            ->get();
                    }
                    else {
                        $checkCount = Alumni::join('form_eif_answers', 'tbl_alumni.alumni_id', '=', 'form_eif_answers.alumni_id')
                            ->where('tbl_alumni.course_id', '=', $course->course_id)
                            ->where('tbl_alumni.sex', '=', $request->sex)
                            ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                            ->select('tbl_alumni.alumni_id')
                            ->distinct()
                            ->get();
                        $perCourse = Alumni::where('tbl_alumni.course_id', '=', $course->course_id)
                            ->where('tbl_alumni.sex', '=', $request->sex)
                            ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                            ->select('tbl_alumni.alumni_id')
                            ->distinct()
                            ->get();
                    }
                    $html .= '<tr>
                            <th class="th-EI" colspan="1" style="width: 70%;">' . $course->course_desc . '</th>
                            <td class="th-EI" colspan="2" style="width: 15%;">' . count($checkCount) . '</td>
                            <td class="th-EI" colspan="1" style="width: 15%;">' . count($perCourse)-count($checkCount) . '</td>
                        </tr>';
                }

            $total = Alumni::join('form_eif_answers', 'tbl_alumni.alumni_id', '=', 'form_eif_answers.alumni_id')
                            ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                            ->select('tbl_alumni.alumni_id')
                            ->distinct()
                            ->get();
            $totalStudents = Alumni::whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])->get();
            $totalPending = (count($totalStudents)-count($total));
            $html .= '<tr>
                    <th class="th-EI" colspan="1" style="width: 70%;">TOTAL</th>
                    <td class="th-EI" colspan="2" style="width: 15%;">' . count($total) . '</td>
                    <td class="th-EI" colspan="1" style="width: 15%;">' . $totalPending . '</td>
                </tr>';
            $html .= '</table>';
        $pdf->writeHTML($html, true, 0, true, 0);

        $totalPending = 0;
        $pdf->ln(2);
        $pdf->SetFont('times', 'B', 11);
        $pdf->MultiCell(88, 5, 'TABLE 2. AGE ', 0, 'L', 0, 0, '', '', true);
        $pdf->MultiCell(30, 5, 'TABLE 3. SEX', 0, 'R', 0, 0, '', '', true);
        $pdf->ln(5);
        $pdf->SetFont('times', '', 11);
        $html = '
                <table style="padding: 0px;">
                    <tr style="padding: 0px;">
                        <td style="padding: 0px;">';
        $html .= '<style>
                .table-EI, .th-EI, .td-EI {
                    border: 1px solid black;
                    border-collapse: collapse;
                    padding: 3px 5px;
                }
                .theading {
                    background-color: #78281F;
                    color: #ffffff;
                }
                th {
                    font-weight: bold;
                }
                td {
                    text-align: center;
                }
            </style>
            <table class="table-EI">
                <tr class="" style="text-align: center; font-weight: bold;">
                    <th class="theading" colspan="1" style="width: 35%;"></th>
                    <td class="theading" colspan="1" style="width: 18%;">Count</td>
                    <td class="theading" colspan="1" style="width: 25%;">Percentage</td>
                </tr>';
            $total = Alumni::join('form_eif_answers', 'tbl_alumni.alumni_id', '=', 'form_eif_answers.alumni_id')
                ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                ->select('tbl_alumni.alumni_id')
                ->distinct()
                ->get();
            $perAge = Alumni::join('form_eif_answers', 'tbl_alumni.alumni_id', '=', 'form_eif_answers.alumni_id')
                ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                ->select('tbl_alumni.age')
                ->orderBy('tbl_alumni.age', 'asc')
                ->distinct()
                ->get();


            foreach ($perAge as $age) {
                    $perSpecificAge = Alumni::join('form_eif_answers', 'tbl_alumni.alumni_id', '=', 'form_eif_answers.alumni_id')
                    ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                    ->where('tbl_alumni.age', '=', $age->age)
                    ->where('tbl_alumni.sex', 'like', $request->sex . '%')
                    ->where('course_id', 'like', '%' . $request->course_id . '%')
                    ->select('tbl_alumni.alumni_id')
                    ->distinct()
                    ->get();
                $html .= '<tr>
                    <th class="th-EI" colspan="1" style="width: 35%;">' . $age->age . ' years old </th>
                    <td class="th-EI" colspan="2" style="width: 18%;">' . count($perSpecificAge) . '</td>

                    <td class="th-EI" colspan="1" style="width: 25%;">' . number_format(count($perSpecificAge) / count($total) * 100, 2) . '% </td>
                </tr>';
            }
        $html .= '</table>
            </td>
            <td style="padding: 0px;">';
        $html .= '
            <table class="table-EI">
                <tr class="" style="text-align: center; font-weight: bold;">
                    <th class="theading" colspan="1" style="width: 30%;"></th>
                    <td class="theading" colspan="1" style="width: 18%;">Count</td>
                    <td class="theading" colspan="1" style="width: 25%;">Percentage</td>
                </tr>';
            $total = Alumni::join('form_eif_answers', 'tbl_alumni.alumni_id', '=', 'form_eif_answers.alumni_id')
                ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                ->select('tbl_alumni.alumni_id')
                ->distinct()
                ->get();
            if ($request->sex == null || empty($request->sex)) {
                $perMale = Alumni::join('form_eif_answers', 'tbl_alumni.alumni_id', '=', 'form_eif_answers.alumni_id')
                    ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                    ->where('course_id', 'like', '%' . $request->course_id . '%')
                    ->where('tbl_alumni.sex', '=', 'Male')
                    ->select('tbl_alumni.alumni_id')
                    ->distinct()
                    ->get();
                $perFemale = Alumni::join('form_eif_answers', 'tbl_alumni.alumni_id', '=', 'form_eif_answers.alumni_id')
                    ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                    ->where('course_id', 'like', '%' . $request->course_id . '%')
                    ->where('tbl_alumni.sex', '=', 'Female')
                    ->select('tbl_alumni.alumni_id')
                    ->distinct()
                    ->get();
                $html .= '<tr>
                    <th class="th-EI" colspan="1" style="width: 30%;">MALE</th>
                    <td class="th-EI" colspan="2" style="width: 18%;">' . count($perMale) . '</td>
                    <td class="th-EI" colspan="1" style="width: 25%;">' . number_format(count($perMale) / count($total) * 100, 2) . '% </td>
                </tr>';
                $html .= '<tr>
                    <th class="th-EI" colspan="1" style="width: 30%;">FEMALE</th>
                    <td class="th-EI" colspan="2" style="width: 18%;">' . count($perFemale) .  '</td>
                    <td class="th-EI" colspan="1" style="width: 25%;">' . number_format(count($perFemale) / count($total) * 100, 2) . '% </td>
                </tr>';
            }
            else {
                $perSex = Alumni::join('form_eif_answers', 'tbl_alumni.alumni_id', '=', 'form_eif_answers.alumni_id')
                    ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                    ->where('tbl_alumni.sex', '=', $request->sex)
                    ->where('course_id', 'like', '%' . $request->course_id . '%')
                    ->select('tbl_alumni.alumni_id')
                    ->distinct()
                    ->get();
                $html .= '<tr>
                    <th class="th-EI" colspan="1" style="width: 30%;">' . strtoupper($request->sex) . '</th>
                    <td class="th-EI" colspan="2" style="width: 20%;">' . count($perSex) .  '</td>
                    <td class="th-EI" colspan="1" style="width: 20%;">' . number_format(count($perSex) / count($total) * 100, 2) . '% </td>
                </tr>';
            }

        $html .= '</table>';
        $html .= '</td>
                </tr>
            </table>';
        $pdf->writeHTML($html, true, 0, true, 0);

        $pdf->lastPage();

        foreach ($courses as $course) {
            if ($request->sex == null || empty($request->sex)) {
                $checkCount = Alumni::join('form_eif_answers', 'tbl_alumni.alumni_id', '=', 'form_eif_answers.alumni_id')
                    ->where('tbl_alumni.course_id', '=', $course->course_id)
                    ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                    ->select('tbl_alumni.alumni_id')
                    ->get();
            }
            else {
                $checkCount = Alumni::join('form_eif_answers', 'tbl_alumni.alumni_id', '=', 'form_eif_answers.alumni_id')
                    ->where('tbl_alumni.course_id', '=', $course->course_id)
                    ->where('tbl_alumni.sex', '=', $request->sex)
                    ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                    ->select('tbl_alumni.alumni_id')
                    ->get();
            }

            if (count($checkCount) > 0) {
                $pdf->SetPrintHeader(true);
                $pdf->AddPage();
                $pdf->SetPrintHeader(false);
                $pdf->SetFont('times', 'B', 13);
                $pdf->ln(20);
                $pdf->Cell(0, 0, 'EXIT INTERVIEW FORM - STATUS REPORT (COMPLETE)', 0, 1, 'C', 0, '', 0);
                $pdf->SetFont('times', '', 12);
                $pdf->MultiCell(180, 5, strtoupper($course->course_desc), 0, 'C', 0, 1, '', '', true);
                $pdf->Cell(0, 0, 'ALUMNI BATCH FROM ' . $request->batch_from . ' TO ' . $request->batch_to, 0, 1, 'C', 0, '', 0);

                $pdf->ln(10);
                $pdf->SetFont('times', '', 11);
                $pdf->Cell(0, 0, 'Date Generated: ' . date('F d, Y'), 0, 1, 'R', 0, '', 0);

                $html = '<style>
                        .table-EI, .th-EI, .td-EI {
                            border: 1px solid black;
                            border-collapse: collapse;
                            padding: 5px;
                        }
                        .theading {
                            background-color: #78281F;
                            color: #ffffff;
                        }
                        th {
                            font-weight: bold;
                        }
                    </style>
                    <table class="table-EI" style="width:100%;">
                        <tr class="theading" style="text-align: center; font-weight: bold;">
                            <th class="" colspan="1" style="width: 25%;">Student Number</th>
                            <td class="" colspan="1" style="width: 65%;">Full Name</td>
                            <td class="" colspan="1" style="width: 10%;">Batch</td>
                        </tr>';
                    foreach ($allAlumni as $alumni) {
                        if ($alumni->course_id == $course->course_id) {
                            $checkEIF = EifAnswers::where('alumni_id', '=', $alumni->alumni_id)->get();
                            if (count($checkEIF) > 0) {
                                $html .= '<tr>
                                        <th class="th-EI" colspan="1" style="width: 25%; text-align: center;">' . $alumni->stud_number . '</th>
                                        <td class="th-EI" colspan="2" style="width: 65%;">' . strtoupper($alumni->last_name) . ', ' . strtoupper($alumni->first_name) . ' ' . strtoupper($alumni->suffix) . ' ' . strtoupper($alumni->middle_name) . '</td>
                                        <th class="th-EI" colspan="1" style="width: 10%; text-align: center;">' . $alumni->batch . '</th>
                                    </tr>';
                            }
                        }
                    }
                    $html .= '</table>';
                $pdf->writeHTML($html, true, 0, true, 0);

                $pdf->lastPage();
            }
        }

        foreach ($courses as $course) {
            $counter = 0;
            foreach ($allAlumni as $alumni) {
                if ($alumni->course_id == $course->course_id) {
                    $checkEIF = EifAnswers::where('alumni_id', '=', $alumni->alumni_id)->get();
                    if (count($checkEIF) == 0) {
                        $counter++;
                    }
                }
            }

            if ($counter > 0) {
                $pdf->SetPrintHeader(true);
                $pdf->AddPage();
                $pdf->SetPrintHeader(false);
                $pdf->SetFont('times', 'B', 13);
                $pdf->ln(20);
                $pdf->Cell(0, 0, 'EXIT INTERVIEW FORM - STATUS REPORT (PENDING)', 0, 1, 'C', 0, '', 0);
                $pdf->SetFont('times', '', 12);
                $pdf->MultiCell(180, 5, strtoupper($course->course_desc), 0, 'C', 0, 1, '', '', true);
                $pdf->Cell(0, 0, 'ALUMNI BATCH FROM ' . $request->batch_from . ' TO ' . $request->batch_to, 0, 1, 'C', 0, '', 0);

                $pdf->ln(10);
                $pdf->SetFont('times', '', 11);
                $pdf->Cell(0, 0, 'Date Generated: ' . date('F d, Y'), 0, 1, 'R', 0, '', 0);

                $html = '<style>
                        .table-EI, .th-EI, .td-EI {
                            border: 1px solid black;
                            border-collapse: collapse;
                            padding: 5px;
                        }
                        .theading {
                            background-color: #78281F;
                            color: #ffffff;
                        }
                        th {
                            font-weight: bold;
                        }
                    </style>
                    <table class="table-EI" style="width:100%;">
                        <tr class="theading" style="text-align: center; font-weight: bold;">
                            <th class="" colspan="1" style="width: 25%;">Student Number</th>
                            <td class="" colspan="1" style="width: 65%;">Full Name</td>
                            <td class="" colspan="1" style="width: 10%;">Batch</td>
                        </tr>';
                    foreach ($allAlumni as $alumni) {
                        if ($alumni->course_id == $course->course_id) {
                            $checkEIF = EifAnswers::where('alumni_id', '=', $alumni->alumni_id)->get();
                            if (count($checkEIF) == 0) {
                                $html .=
                                    '<tr>
                                        <th class="th-EI" colspan="1" style="width: 25%; text-align: center;">' . $alumni->stud_number . '</th>
                                        <td class="th-EI" colspan="2" style="width: 65%;">' . strtoupper($alumni->last_name) . ', ' . strtoupper($alumni->first_name) . ' ' . strtoupper($alumni->suffix) . ' ' . strtoupper($alumni->middle_name) . '</td>
                                        <th class="th-EI" colspan="1" style="width: 10%; text-align: center;">' . $alumni->batch . '</th>
                                    </tr>';
                            }
                        }
                    }
                    $html .= '</table>';
                $pdf->writeHTML($html, true, 0, true, 0);

                $pdf->lastPage();
            }
        }

        $user = auth()->user();
        Log::channel('admin')->info("UserId:".$user->user_id." | AdminId: ".$user->admin_id." | Username: ".$user->name." - Generated an EIF Status Report");
        $pdf->Output('EIF_Status_Report_' . date('m-d-y') . '.pdf', 'I');
    }

    public function SasStatusReport($request) {
        $ctr = 0;
        $totalPending = 0;
        if ($request->sex == null || empty($request->sex)) {
            $allAlumni = Alumni::where('course_id', 'like', '%' . $request->course_id . '%')
                ->whereBetween('batch', [$request->batch_from, $request->batch_to])
                ->orderBy('last_name', 'asc')
                ->get();
        }
        else {
            $allAlumni = Alumni::where('course_id', 'like', '%' . $request->course_id . '%')
                ->where('sex', '=', $request->sex)
                ->whereBetween('batch', [$request->batch_from, $request->batch_to])
                ->orderBy('last_name', 'asc')
                ->get();
        }

        $courses = Courses::where('course_id', 'like', '%' . $request->course_id . '%')->get();

        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetTitle('SAS_STATUS_REPORTS');
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        $pdf->SetPrintHeader(true);
        $pdf->AddPage();
        $pdf->SetPrintHeader(false);
        $pdf->SetFont('times', 'B', 13);
        $pdf->ln(20);
        $pdf->Cell(0, 0, 'SAS FORM - STATUS REPORT (SUMMARY)', 0, 1, 'C', 0, '', 0);
        $pdf->SetFont('times', '', 12);
        $pdf->Cell(0, 0, 'ALUMNI BATCH FROM ' . $request->batch_from . ' TO ' . $request->batch_to, 0, 1, 'C', 0, '', 0);

        $pdf->ln(10);
        $pdf->SetFont('times', '', 11);
        $pdf->Cell(0, 0, 'Date Generated: ' . date('F d, Y'), 0, 1, 'R', 0, '', 0);

        $html = '<style>
                .table-EI, .th-EI, .td-EI {
                    border: 1px solid black;
                    border-collapse: collapse;
                    padding: 8px;
                }
                .theading {
                    background-color: #78281F;
                    color: #ffffff;
                }
                th {
                    font-weight: bold;
                }
                td {
                    text-align: center;
                }
            </style>
            <table class="table-EI" style="width:100%;">
                <tr class="theading" style="text-align: center; font-weight: bold;">
                    <th class="" colspan="1" style="width: 70%;">Course</th>
                    <td class="" colspan="1" style="width: 15%;">Complete</td>
                    <td class="" colspan="1" style="width: 15%;">Pending</td>
                </tr>';
                foreach ($courses as $course) {
                    if ($request->sex == null || empty($request->sex)) {
                        $checkCount = Alumni::join('form_sas_answers', 'tbl_alumni.alumni_id', '=', 'form_sas_answers.alumni_id')
                            ->where('tbl_alumni.course_id', '=', $course->course_id)
                            ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                            ->select('tbl_alumni.alumni_id')
                            ->distinct()
                            ->get();
                        $perCourse = Alumni::where('tbl_alumni.course_id', '=', $course->course_id)
                            ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                            ->select('tbl_alumni.alumni_id')
                            ->distinct()
                            ->get();
                    }
                    else {
                        $checkCount = Alumni::join('form_sas_answers', 'tbl_alumni.alumni_id', '=', 'form_sas_answers.alumni_id')
                            ->where('tbl_alumni.course_id', '=', $course->course_id)
                            ->where('tbl_alumni.sex', '=', $request->sex)
                            ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                            ->select('tbl_alumni.alumni_id')
                            ->distinct()
                            ->get();
                        $perCourse = Alumni::where('tbl_alumni.course_id', '=', $course->course_id)
                            ->where('tbl_alumni.sex', '=', $request->sex)
                            ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                            ->select('tbl_alumni.alumni_id')
                            ->distinct()
                            ->get();
                    }
                    $html .= '<tr>
                            <th class="th-EI" colspan="1" style="width: 70%;">' . $course->course_desc . '</th>
                            <td class="th-EI" colspan="2" style="width: 15%;">' . count($checkCount) . '</td>
                            <td class="th-EI" colspan="1" style="width: 15%;">' . count($perCourse)-count($checkCount) . '</td>
                        </tr>';
                }
            $total = Alumni::join('form_sas_answers', 'tbl_alumni.alumni_id', '=', 'form_sas_answers.alumni_id')
                            ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                            ->select('tbl_alumni.alumni_id')
                            ->distinct()
                            ->get();
            $totalStudents = Alumni::whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])->get();
            $totalPending = (count($totalStudents)-count($total));
            $html .= '<tr>
                    <th class="th-EI" colspan="1" style="width: 70%;">TOTAL</th>
                    <td class="th-EI" colspan="2" style="width: 15%;">' . count($total) . '</td>
                    <td class="th-EI" colspan="1" style="width: 15%;">' . $totalPending . '</td>
                </tr>';
            $html .= '</table>';
        $pdf->writeHTML($html, true, 0, true, 0);

        $pdf->lastPage();

        foreach ($courses as $course) {
            if ($request->sex == null || empty($request->sex)) {
                $checkCount = Alumni::join('form_sas_answers', 'tbl_alumni.alumni_id', '=', 'form_sas_answers.alumni_id')
                    ->where('tbl_alumni.course_id', '=', $course->course_id)
                    ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                    ->select('tbl_alumni.alumni_id')
                    ->get();
            }
            else {
                $checkCount = Alumni::join('form_sas_answers', 'tbl_alumni.alumni_id', '=', 'form_sas_answers.alumni_id')
                    ->where('tbl_alumni.course_id', '=', $course->course_id)
                    ->where('tbl_alumni.sex', '=', $request->sex)
                    ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                    ->select('tbl_alumni.alumni_id')
                    ->get();
            }

            if (count($checkCount) > 0) {
                $pdf->SetPrintHeader(true);
                $pdf->AddPage();
                $pdf->SetPrintHeader(false);
                $pdf->SetFont('times', 'B', 13);
                $pdf->ln(20);
                $pdf->Cell(0, 0, 'SAS FORM - STATUS REPORT (COMPLETE)', 0, 1, 'C', 0, '', 0);
                $pdf->SetFont('times', '', 12);
                $pdf->MultiCell(180, 5, strtoupper($course->course_desc), 0, 'C', 0, 1, '', '', true);
                $pdf->Cell(0, 0, 'ALUMNI BATCH FROM ' . $request->batch_from . ' TO ' . $request->batch_to, 0, 1, 'C', 0, '', 0);

                $pdf->ln(10);
                $pdf->SetFont('times', '', 11);
                $pdf->Cell(0, 0, 'Date Generated: ' . date('F d, Y'), 0, 1, 'R', 0, '', 0);

                $html = '<style>
                        .table-EI, .th-EI, .td-EI {
                            border: 1px solid black;
                            border-collapse: collapse;
                            padding: 5px;
                        }
                        .theading {
                            background-color: #78281F;
                            color: #ffffff;
                        }
                        th {
                            font-weight: bold;
                        }
                    </style>
                    <table class="table-EI" style="width:100%;">
                        <tr class="theading" style="text-align: center; font-weight: bold;">
                            <th class="" colspan="1" style="width: 25%;">Student Number</th>
                            <td class="" colspan="1" style="width: 65%;">Full Name</td>
                            <td class="" colspan="1" style="width: 10%;">Batch</td>
                        </tr>';
                    foreach ($allAlumni as $alumni) {
                        if ($alumni->course_id == $course->course_id) {
                            $checkEIF = SasAnswers::where('alumni_id', '=', $alumni->alumni_id)->get();
                            if (count($checkEIF) > 0) {
                                $html .= '<tr>
                                        <th class="th-EI" colspan="1" style="width: 25%; text-align: center;">' . $alumni->stud_number . '</th>
                                        <td class="th-EI" colspan="2" style="width: 65%;">' . strtoupper($alumni->last_name) . ', ' . strtoupper($alumni->first_name) . ' ' . strtoupper($alumni->suffix) . ' ' . strtoupper($alumni->middle_name) . '</td>
                                        <th class="th-EI" colspan="1" style="width: 10%; text-align: center;">' . $alumni->batch . '</th>
                                    </tr>';
                            }
                        }
                    }
                    $html .= '</table>';
                $pdf->writeHTML($html, true, 0, true, 0);

                $pdf->lastPage();
            }
        }

        foreach ($courses as $course) {
            $counter = 0;
            foreach ($allAlumni as $alumni) {
                if ($alumni->course_id == $course->course_id) {
                    $checkEIF = SasAnswers::where('alumni_id', '=', $alumni->alumni_id)->get();
                    if (count($checkEIF) == 0) {
                        $counter++;
                    }
                }
            }

            if ($counter > 0) {
                $pdf->SetPrintHeader(true);
                $pdf->AddPage();
                $pdf->SetPrintHeader(false);
                $pdf->SetFont('times', 'B', 13);
                $pdf->ln(20);
                $pdf->Cell(0, 0, 'SAS FORM - STATUS REPORT (PENDING)', 0, 1, 'C', 0, '', 0);
                $pdf->SetFont('times', '', 12);
                $pdf->MultiCell(180, 5, strtoupper($course->course_desc), 0, 'C', 0, 1, '', '', true);
                $pdf->Cell(0, 0, 'ALUMNI BATCH FROM ' . $request->batch_from . ' TO ' . $request->batch_to, 0, 1, 'C', 0, '', 0);

                $pdf->ln(10);
                $pdf->SetFont('times', '', 11);
                $pdf->Cell(0, 0, 'Date Generated: ' . date('F d, Y'), 0, 1, 'R', 0, '', 0);

                $html = '<style>
                        .table-EI, .th-EI, .td-EI {
                            border: 1px solid black;
                            border-collapse: collapse;
                            padding: 5px;
                        }
                        .theading {
                            background-color: #78281F;
                            color: #ffffff;
                        }
                        th {
                            font-weight: bold;
                        }
                    </style>
                    <table class="table-EI" style="width:100%;">
                        <tr class="theading" style="text-align: center; font-weight: bold;">
                            <th class="" colspan="1" style="width: 25%;">Student Number</th>
                            <td class="" colspan="1" style="width: 65%;">Full Name</td>
                            <td class="" colspan="1" style="width: 10%;">Batch</td>
                        </tr>';
                    foreach ($allAlumni as $alumni) {
                        if ($alumni->course_id == $course->course_id) {
                            $checkEIF = SasAnswers::where('alumni_id', '=', $alumni->alumni_id)->get();
                            if (count($checkEIF) == 0) {
                                $html .=
                                    '<tr>
                                        <th class="th-EI" colspan="1" style="width: 25%; text-align: center;">' . $alumni->stud_number . '</th>
                                        <td class="th-EI" colspan="2" style="width: 65%;">' . strtoupper($alumni->last_name) . ', ' . strtoupper($alumni->first_name) . ' ' . strtoupper($alumni->suffix) . ' ' . strtoupper($alumni->middle_name) . '</td>
                                        <th class="th-EI" colspan="1" style="width: 10%; text-align: center;">' . $alumni->batch . '</th>
                                    </tr>';
                            }
                        }
                    }
                    $html .= '</table>';
                $pdf->writeHTML($html, true, 0, true, 0);

                $pdf->lastPage();
            }
        }
        $user = auth()->user();
        Log::channel('admin')->info("UserId:".$user->user_id." | AdminId: ".$user->admin_id." | Username: ".$user->name." - Generated an SAS Status Report");
        $pdf->Output('SAS_Status_Report_' . date('m-d-y') . '.pdf', 'I');
    }

    public function EifDetailedReport($request) {
        $totalPending = 0;
        if ($request->sex == null || empty($request->sex)) {
            $allAlumni = Alumni::where('course_id', 'like', '%' . $request->course_id . '%')
                ->whereBetween('batch', [$request->batch_from, $request->batch_to])
                ->orderBy('last_name', 'asc')
                ->get();
        }
        else {
            $allAlumni = Alumni::where('course_id', 'like', '%' . $request->course_id . '%')
                ->where('sex', '=', $request->sex)
                ->whereBetween('batch', [$request->batch_from, $request->batch_to])
                ->orderBy('last_name', 'asc')
                ->get();
        }

        $courses = Courses::where('course_id', 'like', '%' . $request->course_id . '%')->get();

        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetTitle('EIF_DETAILED_REPORTS');
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        $pdf->SetPrintHeader(true);
        $pdf->AddPage('L');
        $style3 = array('width' => .5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
        $pdf->Line(10, 33, 287, 33, $style3);
        $pdf->ln(15);
        $pdf->SetFont('times', 'B', 13);
        $pdf->Cell(0, 0, 'EXIT INTERVIEW FORM - DETAILED REPORT', 0, 1, 'C', 0, '', 0);
        $pdf->SetFont('times', '', 12);
        $pdf->Cell(0, 0, 'ALUMNI BATCH FROM ' . $request->batch_from . ' TO ' . $request->batch_to, 0, 1, 'C', 0, '', 0);
        $pdf->SetFont('times', '', 11);
        $pdf->Cell(0, 0, 'Date Generated: ' . date('F d, Y'), 0, 1, 'C', 0, '', 0);

        $pdf->ln(5);
        $pdf->SetFont('times', 'B', 11);
        $pdf->Cell(0, 0, 'TABLE 1. COURSES', 0, 1, 'L', 0, '', 0);
        $pdf->SetFont('times', '', 11);
        $html = '<style>
                .table-EI, .th-EI, .td-EI {
                    border: 1px solid black;
                    border-collapse: collapse;
                    padding: 5px;
                }
                .theading {
                    background-color: #78281F;
                    color: #ffffff;
                }
                th {
                    font-weight: bold;
                }
                td {
                    text-align: center;
                }
            </style>
            <table class="table-EI" style="width:100%;">
                <tr class="theading" style="text-align: center; font-weight: bold;">
                    <th class="" colspan="1" style="width: 70%;">Course</th>
                    <td class="" colspan="1" style="width: 15%;">Complete</td>
                    <td class="" colspan="1" style="width: 15%;">Pending</td>
                </tr>';
                foreach ($courses as $course) {
                    if ($request->sex == null || empty($request->sex)) {
                        $checkCount = Alumni::join('form_eif_answers', 'tbl_alumni.alumni_id', '=', 'form_eif_answers.alumni_id')
                            ->where('tbl_alumni.course_id', '=', $course->course_id)
                            ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                            ->select('tbl_alumni.alumni_id')
                            ->distinct()
                            ->get();
                        $perCourse = Alumni::where('tbl_alumni.course_id', '=', $course->course_id)
                            ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                            ->select('tbl_alumni.alumni_id')
                            ->distinct()
                            ->get();
                        $total = Alumni::join('form_eif_answers', 'tbl_alumni.alumni_id', '=', 'form_eif_answers.alumni_id')
                            ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                            ->where('course_id', 'like', '%' . $request->course_id . '%')
                            ->select('tbl_alumni.alumni_id')
                            ->distinct()
                            ->get();
                    }
                    else {
                        $checkCount = Alumni::join('form_eif_answers', 'tbl_alumni.alumni_id', '=', 'form_eif_answers.alumni_id')
                            ->where('tbl_alumni.course_id', '=', $course->course_id)
                            ->where('tbl_alumni.sex', '=', $request->sex)
                            ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                            ->select('tbl_alumni.alumni_id')
                            ->distinct()
                            ->get();
                        $perCourse = Alumni::where('tbl_alumni.course_id', '=', $course->course_id)
                            ->where('tbl_alumni.sex', '=', $request->sex)
                            ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                            ->select('tbl_alumni.alumni_id')
                            ->distinct()
                            ->get();
                        $total = Alumni::join('form_eif_answers', 'tbl_alumni.alumni_id', '=', 'form_eif_answers.alumni_id')
                            ->where('tbl_alumni.sex', '=', $request->sex)
                            ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                            ->where('course_id', 'like', '%' . $request->course_id . '%')
                            ->select('tbl_alumni.alumni_id')
                            ->distinct()
                            ->get();
                    }
                    $html .= '<tr>
                            <th class="th-EI" colspan="1" style="width: 70%;">' . $course->course_desc . '</th>
                            <td class="th-EI" colspan="2" style="width: 15%;">' . count($checkCount) . '</td>
                            <td class="th-EI" colspan="1" style="width: 15%;">' . count($perCourse)-count($checkCount) . '</td>
                        </tr>';

                    $totalPending = $totalPending + (count($perCourse)-count($checkCount));
                }
            $html .= '<tr>
                    <th class="th-EI" colspan="1" style="width: 70%;">TOTAL</th>
                    <td class="th-EI" colspan="2" style="width: 15%; font-weight: bold;">' . count($total) . '</td>
                    <td class="th-EI" colspan="1" style="width: 15%; font-weight: bold;">' . $totalPending . '</td>
                </tr>';
            $html .= '</table>';
        $pdf->writeHTML($html, true, 0, true, 0);

        $totalPending = 0;
        $pdf->SetPrintHeader(true);
        $pdf->AddPage('L');
        $style3 = array('width' => .5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
        $pdf->Line(10, 33, 287, 33, $style3);
        $pdf->ln(10);
        $pdf->SetFont('times', 'B', 11);
        $pdf->MultiCell(88, 5, 'TABLE 2. AGE ', 0, 'L', 0, 0, '', '', true);
        $pdf->MultiCell(74, 5, 'TABLE 3. SEX', 0, 'R', 0, 0, '', '', true);
        $pdf->ln(5);
        $pdf->SetFont('times', '', 11);
        $html = '
                <table style="padding: 0px;">
                    <tr style="padding: 0px;">
                        <td style="padding: 0px;">';
        $html .= '<style>
                .table-EI, .th-EI, .td-EI {
                    border: 1px solid black;
                    border-collapse: collapse;
                    padding: 3px 5px;
                }
                .theading {
                    background-color: #78281F;
                    color: #ffffff;
                }
                th {
                    font-weight: bold;
                }
                td {
                    text-align: center;
                }
            </style>
            <table class="table-EI">
                <tr class="" style="text-align: center; font-weight: bold;">
                    <th class="theading" colspan="1" style="width: 35%;"></th>
                    <td class="theading" colspan="1" style="width: 18%;">Count</td>
                    <td class="theading" colspan="1" style="width: 25%;">Percentage</td>
                </tr>';
            $total = Alumni::join('form_eif_answers', 'tbl_alumni.alumni_id', '=', 'form_eif_answers.alumni_id')
                ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                ->select('tbl_alumni.alumni_id')
                ->distinct()
                ->get();
            $perAge = Alumni::join('form_eif_answers', 'tbl_alumni.alumni_id', '=', 'form_eif_answers.alumni_id')
                ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                ->where('course_id', 'like', '%' . $request->course_id . '%')
                ->select('tbl_alumni.age')
                ->orderBy('tbl_alumni.age', 'asc')
                ->distinct()
                ->get();


            foreach ($perAge as $age) {
                $perSpecificAge = Alumni::join('form_eif_answers', 'tbl_alumni.alumni_id', '=', 'form_eif_answers.alumni_id')
                    ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                    ->where('tbl_alumni.age', '=', $age->age)
                    ->where('tbl_alumni.sex', 'like', $request->sex . '%')
                    ->where('course_id', 'like', '%' . $request->course_id . '%')
                    ->select('tbl_alumni.alumni_id')
                    ->distinct()
                    ->get();
                $html .= '<tr>
                    <th class="th-EI" colspan="1" style="width: 35%;">' . $age->age . ' years old </th>
                    <td class="th-EI" colspan="2" style="width: 18%;">' . count($perSpecificAge) . '</td>
                    <td class="th-EI" colspan="1" style="width: 25%;">' . number_format(count($perSpecificAge) / count($total) * 100, 2) . '% </td>
                </tr>';
            }
        $html .= '</table>
            </td>
            <td style="padding: 0px;">';
        $html .= '
            <table class="table-EI">
                <tr class="" style="text-align: center; font-weight: bold;">
                    <th class="theading" colspan="1" style="width: 30%;"></th>
                    <td class="theading" colspan="1" style="width: 18%;">Count</td>
                    <td class="theading" colspan="1" style="width: 25%;">Percentage</td>
                </tr>';
            $total = Alumni::join('form_eif_answers', 'tbl_alumni.alumni_id', '=', 'form_eif_answers.alumni_id')
                ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                ->select('tbl_alumni.alumni_id')
                ->distinct()
                ->get();
            if ($request->sex == null || empty($request->sex)) {
                $perMale = Alumni::join('form_eif_answers', 'tbl_alumni.alumni_id', '=', 'form_eif_answers.alumni_id')
                    ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                    ->where('course_id', 'like', '%' . $request->course_id . '%')
                    ->where('tbl_alumni.sex', '=', 'Male')
                    ->select('tbl_alumni.alumni_id')
                    ->distinct()
                    ->get();
                $perFemale = Alumni::join('form_eif_answers', 'tbl_alumni.alumni_id', '=', 'form_eif_answers.alumni_id')
                    ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                    ->where('course_id', 'like', '%' . $request->course_id . '%')
                    ->where('tbl_alumni.sex', '=', 'Female')
                    ->select('tbl_alumni.alumni_id')
                    ->distinct()
                    ->get();
                $html .= '<tr>
                    <th class="th-EI" colspan="1" style="width: 30%;">MALE</th>
                    <td class="th-EI" colspan="2" style="width: 18%;">' . count($perMale) . '</td>
                    <td class="th-EI" colspan="1" style="width: 25%;">' . number_format(count($perMale) / count($total) * 100, 2) . '% </td>
                </tr>';
                $html .= '<tr>
                    <th class="th-EI" colspan="1" style="width: 30%;">FEMALE</th>
                    <td class="th-EI" colspan="2" style="width: 18%;">' . count($perFemale) .  '</td>
                    <td class="th-EI" colspan="1" style="width: 25%;">' . number_format(count($perFemale) / count($total) * 100, 2) . '% </td>
                </tr>';
            }
            else {
                $perSex = Alumni::join('form_eif_answers', 'tbl_alumni.alumni_id', '=', 'form_eif_answers.alumni_id')
                    ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                    ->where('tbl_alumni.sex', '=', $request->sex)
                    ->where('course_id', 'like', '%' . $request->course_id . '%')
                    ->select('tbl_alumni.alumni_id')
                    ->distinct()
                    ->get();
                $html .= '<tr>
                    <th class="th-EI" colspan="1" style="width: 30%;">' . strtoupper($request->sex) . '</th>
                    <td class="th-EI" colspan="2" style="width: 20%;">' . count($perSex) .  '</td>
                    <td class="th-EI" colspan="1" style="width: 20%;">' . number_format(count($perSex) / count($total) * 100, 2) . '% </td>
                </tr>';
            }

        $html .= '</table>';
        $html .= '</td>
                </tr>
            </table>';
        $pdf->writeHTML($html, true, 0, true, 0);

        $pdf->ln(5);
        $pdf->SetFont('times', 'B', 11);
        $pdf->Cell(0, 0, 'TABLE 4. REASON FOR LEAVING PUP TAGUIG', 0, 1, 'L', 0, '', 0);
        $pdf->SetFont('times', '', 11);
        $html = '<style>
                .table-EI, .th-EI, .td-EI {
                    border: 1px solid black;
                    border-collapse: collapse;
                    padding: 5px;
                }
                .theading {
                    background-color: #78281F;
                    color: #ffffff;
                }
                th {
                    font-weight: bold;
                }
                td {
                    text-align: center;
                }
            </style>
            <table class="table-EI" style="width:100%;">
                <tr class="" style="text-align: center; font-weight: bold;">
                    <th class="theading" colspan="1" style="width: 30%;"></th>
                    <td class="theading" colspan="1" style="width: 20%;">No. of Respondents</td>
                    <td class="theading" colspan="1" style="width: 20%;">Percentage</td>
                </tr>';
            $total = Alumni::join('form_eif_answers', 'tbl_alumni.alumni_id', '=', 'form_eif_answers.alumni_id')
                ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                ->select('tbl_alumni.alumni_id')
                ->distinct()
                ->get();
            $perAge = Alumni::join('form_eif_answers', 'tbl_alumni.alumni_id', '=', 'form_eif_answers.alumni_id')
                ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                ->where('course_id', 'like', '%' . $request->course_id . '%')
                ->where('tbl_alumni.sex', 'like', $request->sex . '%')
                ->select('tbl_alumni.age')
                ->distinct()
                ->get();

            $reasons = ['Graduation', 'Personal', 'Family', 'Academic', 'Financial', 'Work-related', 'Others'];


            foreach ($reasons as $reason) {
                $perReasons = Alumni::join('form_eif_answers', 'tbl_alumni.alumni_id', '=', 'form_eif_answers.alumni_id')
                    ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                    ->where('form_eif_answers.answer', '=', $reason)
                    ->where('course_id', 'like', '%' . $request->course_id . '%')
                    ->where('tbl_alumni.sex', 'like', $request->sex . '%')
                    ->select('tbl_alumni.alumni_id')
                    ->distinct()
                    ->get();
                $html .= '<tr>
                    <th class="th-EI" colspan="1" style="width: 30%;">' . $reason . '</th>
                    <td class="th-EI" colspan="2" style="width: 20%;">' . count($perReasons) . '</td>
                    <td class="th-EI" colspan="1" style="width: 20%;">' . number_format(count($perReasons) / count($total) * 100, 2) . '% </td>
                </tr>';
            }
            $html .= '</table>';
        $pdf->writeHTML($html, true, 0, true, 0);

        $pdf->SetPrintHeader(true);
        $pdf->AddPage('L');
        $style3 = array('width' => .5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
        $pdf->Line(10, 33, 288, 33, $style3);
        $pdf->ln(15);
        $eifQuestions = EifQuestions::where('category_id', '=', 3)->get();
        $numeral = 5;
        foreach ($eifQuestions as $questions) {
            if ($numeral == 9) {
                $pdf->SetPrintHeader(true);
                $pdf->AddPage('L');
                $style3 = array('width' => .5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
                $pdf->Line(10, 33, 288, 33, $style3);
                $pdf->ln(15);
            }
            $pdf->ln(1);
            $pdf->SetFont('times', 'B', 11);
            $pdf->Cell(0, 0, 'TABLE ' . $numeral . '. ' . strtoupper($questions->question_text), 0, 1, 'L', 0, '', 0);
            $pdf->SetFont('times', '', 11);
            $html = '<style>
                    .table-EI, .th-EI, .td-EI {
                        border: 1px solid black;
                        border-collapse: collapse;
                        padding: 5px;
                    }
                    .theading {
                        background-color: #78281F;
                        color: #ffffff;
                        font-size: 13px;
                    }
                    th {
                        font-weight: bold;
                    }
                    td {
                        text-align: center;
                    }
                </style>
                <table class="table-EI" style="width:100%;">
                    <tr class="" style="text-align: center; font-weight: bold;">
                        <th class="theading" colspan="1" style="width: 20%;"></th>
                        <td class="theading" colspan="1" style="width: 16%;">5 - Outstanding</td>
                        <td class="theading" colspan="1" style="width: 16%;">4 - Very Satisfactory</td>
                        <td class="theading" colspan="1" style="width: 16%;">3 - Satisfactory</td>
                        <td class="theading" colspan="1" style="width: 16%;">2 - Fair</td>
                        <td class="theading" colspan="1" style="width: 16%;">1 - Poor</td>
                    </tr>';
                $total = Alumni::join('form_eif_answers', 'tbl_alumni.alumni_id', '=', 'form_eif_answers.alumni_id')
                    ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                    ->select('tbl_alumni.alumni_id')
                    ->distinct()
                    ->get();
                $perAnswer = Alumni::join('form_eif_answers', 'tbl_alumni.alumni_id', '=', 'form_eif_answers.alumni_id')
                    ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                    ->where('course_id', 'like', '%' . $request->course_id . '%')
                    ->where('tbl_alumni.sex', 'like', $request->sex . '%')
                    ->where('form_eif_answers.question_id', '=', $questions->question_id)
                    ->select('form_eif_answers.answer')
                    ->get();

                $legend1 = 0;
                $legend2 = 0;
                $legend3 = 0;
                $legend4 = 0;
                $legend5 = 0;

                foreach ($perAnswer as $answers) {
                    if ($answers->answer == 1) {
                        $legend1++;
                    }
                    elseif ($answers->answer == 2) {
                        $legend2++;
                    }
                    elseif ($answers->answer == 3) {
                        $legend3++;
                    }
                    elseif ($answers->answer == 4) {
                        $legend4++;
                    }
                    elseif ($answers->answer == 5) {
                        $legend5++;
                    }
                }

                    $html .= '<tr>
                        <th class="th-EI" colspan="1" style="width: 20%; font-size: 13px;">No. of Respondents</th>
                        <td class="th-EI" colspan="1" style="width: 16%;">' . $legend5 . '</td>
                        <td class="th-EI" colspan="1" style="width: 16%;">' . $legend4 . '</td>
                        <td class="th-EI" colspan="1" style="width: 16%;">' . $legend3 . '</td>
                        <td class="th-EI" colspan="1" style="width: 16%;">' . $legend2 . '</td>
                        <td class="th-EI" colspan="1" style="width: 16%;">' . $legend1 . '</td>
                    </tr>';
                    $html .= '<tr>
                        <th class="th-EI" colspan="1" style="width: 20%; font-size: 13px;">Percentage</th>
                        <td class="th-EI" colspan="1" style="width: 16%;">' . number_format(($legend5 / count($total)) *100, 2) . '% </td>
                        <td class="th-EI" colspan="1" style="width: 16%;">' . number_format(($legend4 / count($total)) *100, 2) . '% </td>
                        <td class="th-EI" colspan="1" style="width: 16%;">' . number_format(($legend3 / count($total)) *100, 2) . '% </td>
                        <td class="th-EI" colspan="1" style="width: 16%;">' . number_format(($legend2 / count($total)) *100, 2) . '% </td>
                        <td class="th-EI" colspan="1" style="width: 16%;">' . number_format(($legend1 / count($total)) *100, 2) . '% </td>
                    </tr>';
                $html .= '</table>';
            $pdf->writeHTML($html, true, 0, true, 0);
            $numeral++;
        }

        $eifCategories = EifCategories::whereIn('category_id', [4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15])->get();
        $numeral = 12;
        $q = 11;
        $t = 12;
        $c = 13;
        $pdf->SetPrintHeader(true);
        $pdf->AddPage('L');
        $style3 = array('width' => .5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
        $pdf->Line(10, 33, 288, 33, $style3);
        $pdf->ln(20);
        foreach ($eifCategories as $categories) {
            if ($numeral %2 == 0 && $numeral != 12) {
                $pdf->SetPrintHeader(true);
                $pdf->AddPage('L');
                $style3 = array('width' => .5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
                $pdf->Line(10, 33, 288, 33, $style3);
                $pdf->ln(20);
            }
            $pdf->ln(5);
            $pdf->SetFont('times', 'B', 11);
            $pdf->Cell(0, 0, 'TABLE ' . $numeral . '. ' . strtoupper($categories->category_name), 0, 1, 'L', 0, '', 0);
            $pdf->SetFont('times', '', 11);

            $total = Alumni::join('form_eif_answers', 'tbl_alumni.alumni_id', '=', 'form_eif_answers.alumni_id')
                ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                ->select('tbl_alumni.alumni_id')
                ->distinct()
                ->get();
            $quality = Alumni::join('form_eif_answers', 'tbl_alumni.alumni_id', '=', 'form_eif_answers.alumni_id')
                    ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                    ->where('course_id', 'like', '%' . $request->course_id . '%')
                    ->where('tbl_alumni.sex', 'like', $request->sex . '%')
                    ->where('form_eif_answers.question_id', '=', $q)
                    ->select('form_eif_answers.answer')
                    ->get();
            $timeliness = Alumni::join('form_eif_answers', 'tbl_alumni.alumni_id', '=', 'form_eif_answers.alumni_id')
                    ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                    ->where('course_id', 'like', '%' . $request->course_id . '%')
                    ->where('tbl_alumni.sex', 'like', $request->sex . '%')
                    ->where('form_eif_answers.question_id', '=', $t)
                    ->select('form_eif_answers.answer')
                    ->get();
            $courtesy = Alumni::join('form_eif_answers', 'tbl_alumni.alumni_id', '=', 'form_eif_answers.alumni_id')
                    ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                    ->where('course_id', 'like', '%' . $request->course_id . '%')
                    ->where('tbl_alumni.sex', 'like', $request->sex . '%')
                    ->where('form_eif_answers.question_id', '=', $c)
                    ->select('form_eif_answers.answer')
                    ->get();

            $q_legend1 = 0;
            $q_legend2 = 0;
            $q_legend3 = 0;
            $q_legend4 = 0;
            $q_legend5 = 0;
            foreach ($quality as $answers) {
                if ($answers->answer == 1) {
                    $q_legend1++;
                }
                elseif ($answers->answer == 2) {
                    $q_legend2++;
                }
                elseif ($answers->answer == 3) {
                    $q_legend3++;
                }
                elseif ($answers->answer == 4) {
                    $q_legend4++;
                }
                elseif ($answers->answer == 5) {
                    $q_legend5++;
                }
            }

            $t_legend1 = 0;
            $t_legend2 = 0;
            $t_legend3 = 0;
            $t_legend4 = 0;
            $t_legend5 = 0;
            foreach ($timeliness as $answers) {
                if ($answers->answer == 1) {
                    $t_legend1++;
                }
                elseif ($answers->answer == 2) {
                    $t_legend2++;
                }
                elseif ($answers->answer == 3) {
                    $t_legend3++;
                }
                elseif ($answers->answer == 4) {
                    $t_legend4++;
                }
                elseif ($answers->answer == 5) {
                    $t_legend5++;
                }
            }

            $c_legend1 = 0;
            $c_legend2 = 0;
            $c_legend3 = 0;
            $c_legend4 = 0;
            $c_legend5 = 0;
            foreach ($courtesy as $answers) {
                if ($answers->answer == 1) {
                    $c_legend1++;
                }
                elseif ($answers->answer == 2) {
                    $c_legend2++;
                }
                elseif ($answers->answer == 3) {
                    $c_legend3++;
                }
                elseif ($answers->answer == 4) {
                    $c_legend4++;
                }
                elseif ($answers->answer == 5) {
                    $c_legend5++;
                }
            }

            $html = '<style>
                    .table-EI, .th-EI, .td-EI {
                        border: 1px solid black;
                        border-collapse: collapse;
                        padding: 3px;
                    }
                    .theading {
                        background-color: #78281F;
                        color: #ffffff;
                        text-align: center;
                    }
                    th {
                        font-weight: bold;
                    }
                    td {
                        text-align: center;
                    }
                </style>
                <table class="table-EI">
                    <tr>
                        <th rowspan="2" class="theading"></th>
                        <th colspan="2" class="theading">Quality of Service</th>
                        <th colspan="2" class="theading">Timeliness of Service</th>
                        <th colspan="2" class="theading">Courtesy of Staff</th>
                    </tr>
                    <tr>
                        <th class="th-EI" colspan="1" style="text-align: center;">No. of Respondents</th>
                        <th class="th-EI" colspan="1" style="text-align: center;">Percentage</th>
                        <th class="th-EI" colspan="1" style="text-align: center;">No. of Respondents</th>
                        <th class="th-EI" colspan="1" style="text-align: center;">Percentage</th>
                        <th class="th-EI" colspan="1" style="text-align: center;">No. of Respondents</th>
                        <th class="th-EI" colspan="1" style="text-align: center;">Percentage</th>
                    </tr>';

                $html .= '<tr>
                        <th class="th-EI" style="text-align: left;">1 - Poor</th>
                        <td class="tdata td-EI">' . $q_legend1 . '</td>
                        <td class="tdata td-EI">' . number_format($q_legend1 / count($total) * 100, 2) . '%</td>
                        <td class="tdata td-EI">' . $t_legend1 . '</td>
                        <td class="tdata td-EI">' . number_format($t_legend1 / count($total) * 100, 2) . '%</td>
                        <td class="tdata td-EI">' . $c_legend1 . '</td>
                        <td class="tdata td-EI">' . number_format($c_legend1 / count($total) * 100, 2) . '%</td>
                    </tr>';

                $html .= '<tr>
                        <th class="th-EI" style="text-align: left;">2 - Fair</th>
                        <td class="tdata td-EI">' . $q_legend2 . '</td>
                        <td class="tdata td-EI">' . number_format($q_legend2 / count($total) * 100, 2) . '%</td>
                        <td class="tdata td-EI">' . $t_legend2 . '</td>
                        <td class="tdata td-EI">' . number_format($t_legend2 / count($total) * 100, 2) . '%</td>
                        <td class="tdata td-EI">' . $c_legend2 . '</td>
                        <td class="tdata td-EI">' . number_format($c_legend2 / count($total) * 100, 2) . '%</td>
                    </tr>
                    <tr>
                        <th class="th-EI" style="text-align: left;">3 - Satisfactory</th>
                        <td class="tdata td-EI">' . $q_legend3 . '</td>
                        <td class="tdata td-EI">' . number_format($q_legend3 / count($total) * 100, 2) . '%</td>
                        <td class="tdata td-EI">' . $t_legend3 . '</td>
                        <td class="tdata td-EI">' . number_format($t_legend3 / count($total) * 100, 2) . '%</td>
                        <td class="tdata td-EI">' . $c_legend3 . '</td>
                        <td class="tdata td-EI">' . number_format($c_legend3 / count($total) * 100, 2) . '%</td>
                    </tr>
                    <tr>
                        <th class="th-EI" style="text-align: left;">4 - Very Satisfactory</th>
                        <td class="tdata td-EI">' . $q_legend4 . '</td>
                        <td class="tdata td-EI">' . number_format($q_legend4 / count($total) * 100, 2) . '%</td>
                        <td class="tdata td-EI">' . $t_legend4 . '</td>
                        <td class="tdata td-EI">' . number_format($t_legend4 / count($total) * 100, 2) . '%</td>
                        <td class="tdata td-EI">' . $c_legend4 . '</td>
                        <td class="tdata td-EI">' . number_format($c_legend4 / count($total) * 100, 2) . '%</td>
                    </tr>
                    <tr>
                        <th class="th-EI" style="text-align: left;">5 - Outstanding</th>
                        <td class="tdata td-EI">' . $q_legend5 . '</td>
                        <td class="tdata td-EI">' . number_format($q_legend5 / count($total) * 100, 2) . '%</td>
                        <td class="tdata td-EI">' . $t_legend5 . '</td>
                        <td class="tdata td-EI">' . number_format($t_legend5 / count($total) * 100, 2) . '%</td>
                        <td class="tdata td-EI">' . $c_legend5 . '</td>
                        <td class="tdata td-EI">' . number_format($c_legend5 / count($total) * 100, 2) . '%</td>
                    </tr>';
            $html .= '</table>';
            $pdf->writeHTML($html, true, 0, true, 0);
            $q += 3;
            $t += 3;
            $c += 3;
            $numeral++;
        }

        $eifQuestions = EifQuestions::where('category_id', '=', 16)->get();
        $numeral = 24;
        $pdf->SetPrintHeader(true);
        $pdf->AddPage('L');
        $style3 = array('width' => .5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
        $pdf->Line(10, 33, 288, 33, $style3);
        $pdf->ln(15);
        foreach ($eifQuestions as $questions) {
            $pdf->ln(1);
            $pdf->SetFont('times', 'B', 11);
            $pdf->Cell(0, 0, 'TABLE ' . $numeral . '. ' . strtoupper($questions->question_text), 0, 1, 'L', 0, '', 0);
            $pdf->SetFont('times', '', 11);
            $html = '<style>
                    .table-EI, .th-EI, .td-EI {
                        border: 1px solid black;
                        border-collapse: collapse;
                        padding: 5px;
                    }
                    .theading {
                        background-color: #78281F;
                        color: #ffffff;
                        font-size: 13px;
                    }
                    th {
                        font-weight: bold;
                    }
                    td {
                        text-align: center;
                    }
                </style>
                <table class="table-EI" style="width:100%;">
                    <tr class="" style="text-align: center; font-weight: bold;">
                        <th class="theading" colspan="1" style="width: 20%;"></th>
                        <td class="theading" colspan="1" style="width: 16%;">5 - Outstanding</td>
                        <td class="theading" colspan="1" style="width: 16%;">4 - Very Satisfactory</td>
                        <td class="theading" colspan="1" style="width: 16%;">3 - Satisfactory</td>
                        <td class="theading" colspan="1" style="width: 16%;">2 - Fair</td>
                        <td class="theading" colspan="1" style="width: 16%;">1 - Poor</td>
                    </tr>';
                $total = Alumni::join('form_eif_answers', 'tbl_alumni.alumni_id', '=', 'form_eif_answers.alumni_id')
                    ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                    ->select('tbl_alumni.alumni_id')
                    ->distinct()
                    ->get();
                $perAnswer = Alumni::join('form_eif_answers', 'tbl_alumni.alumni_id', '=', 'form_eif_answers.alumni_id')
                    ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                    ->where('course_id', 'like', '%' . $request->course_id . '%')
                    ->where('tbl_alumni.sex', 'like', $request->sex . '%')
                    ->where('form_eif_answers.question_id', '=', $questions->question_id)
                    ->select('form_eif_answers.answer')
                    ->get();

                $legend1 = 0;
                $legend2 = 0;
                $legend3 = 0;
                $legend4 = 0;
                $legend5 = 0;

                foreach ($perAnswer as $answers) {
                    if ($answers->answer == 1) {
                        $legend1++;
                    }
                    elseif ($answers->answer == 2) {
                        $legend2++;
                    }
                    elseif ($answers->answer == 3) {
                        $legend3++;
                    }
                    elseif ($answers->answer == 4) {
                        $legend4++;
                    }
                    elseif ($answers->answer == 5) {
                        $legend5++;
                    }
                }

                    $html .= '<tr>
                        <th class="th-EI" colspan="1" style="width: 20%; font-size: 13px;">No. of Respondents</th>
                        <td class="th-EI" colspan="1" style="width: 16%;">' . $legend5 . '</td>
                        <td class="th-EI" colspan="1" style="width: 16%;">' . $legend4 . '</td>
                        <td class="th-EI" colspan="1" style="width: 16%;">' . $legend3 . '</td>
                        <td class="th-EI" colspan="1" style="width: 16%;">' . $legend2 . '</td>
                        <td class="th-EI" colspan="1" style="width: 16%;">' . $legend1 . '</td>
                    </tr>';
                    $html .= '<tr>
                        <th class="th-EI" colspan="1" style="width: 20%; font-size: 13px;">Percentage</th>
                        <td class="th-EI" colspan="1" style="width: 16%;">' . number_format(($legend5 / count($total)) *100, 2) . '% </td>
                        <td class="th-EI" colspan="1" style="width: 16%;">' . number_format(($legend4 / count($total)) *100, 2) . '% </td>
                        <td class="th-EI" colspan="1" style="width: 16%;">' . number_format(($legend3 / count($total)) *100, 2) . '% </td>
                        <td class="th-EI" colspan="1" style="width: 16%;">' . number_format(($legend2 / count($total)) *100, 2) . '% </td>
                        <td class="th-EI" colspan="1" style="width: 16%;">' . number_format(($legend1 / count($total)) *100, 2) . '% </td>
                    </tr>';
                $html .= '</table>';
            $pdf->writeHTML($html, true, 0, true, 0);
            $numeral++;
        }

        $pdf->lastPage();
        $user = auth()->user();
        Log::channel('admin')->info("UserId:".$user->user_id." | AdminId: ".$user->admin_id." | Username: ".$user->name." - Generated a Detailed EIF Report");
        $pdf->Output('EIF_Detailed_Report_' . date('m-d-y') . '.pdf', 'I');
    }

    public function SasDetailedReport($request) {
        $totalPending = 0;
        if ($request->sex == null || empty($request->sex)) {
            $allAlumni = Alumni::where('course_id', 'like', '%' . $request->course_id . '%')
                ->whereBetween('batch', [$request->batch_from, $request->batch_to])
                ->orderBy('last_name', 'asc')
                ->get();
        }
        else {
            $allAlumni = Alumni::where('course_id', 'like', '%' . $request->course_id . '%')
                ->where('sex', '=', $request->sex)
                ->whereBetween('batch', [$request->batch_from, $request->batch_to])
                ->orderBy('last_name', 'asc')
                ->get();
        }

        if($request->course_id == 'All'){
            $courses = Courses::get();
        }else{
            $courses = Courses::where('course_id', 'like', '%' . $request->course_id . '%')->get();
        }
        

        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetTitle('SAS_DETAILED_REPORTS');
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        $pdf->SetPrintHeader(true);
        $pdf->AddPage();
        $pdf->SetFont('times', 'B', 13);
        $pdf->ln(10);
        $pdf->Cell(0, 0, 'STUDENT AFFAIRS AND SERVICESS FORM - DETAILED REPORT', 0, 1, 'C', 0, '', 0);
        $pdf->SetFont('times', '', 12);
        $pdf->Cell(0, 0, 'ALUMNI BATCH FROM ' . $request->batch_from . ' TO ' . $request->batch_to, 0, 1, 'C', 0, '', 0);
        $pdf->SetFont('times', '', 11);
        $pdf->Cell(0, 0, 'Date Generated: ' . date('F d, Y'), 0, 1, 'C', 0, '', 0);

        $pdf->ln(2);
        $pdf->SetFont('times', 'B', 11);
        $pdf->Cell(0, 0, 'TABLE 1. COURSES', 0, 1, 'L', 0, '', 0);
        $pdf->SetFont('times', '', 11);
        $html = '<style>
                .table-EI, .th-EI, .td-EI {
                    border: 1px solid black;
                    border-collapse: collapse;
                    padding: 5px;
                }
                .theading {
                    background-color: #78281F;
                    color: #ffffff;
                }
                th {
                    font-weight: bold;
                }
                td {
                    text-align: center;
                }
            </style>
            <table class="table-EI" style="width:100%;">
                <tr class="theading" style="text-align: center; font-weight: bold;">
                    <th class="" colspan="1" style="width: 70%;">Course</th>
                    <td class="" colspan="1" style="width: 15%;">Complete</td>
                    <td class="" colspan="1" style="width: 15%;">Pending</td>
                </tr>';
                foreach ($courses as $course) {
                    if ($request->sex == null || empty($request->sex)) {
                        $checkCount = Alumni::join('form_sas_answers', 'tbl_alumni.alumni_id', '=', 'form_sas_answers.alumni_id')
                            ->where('tbl_alumni.course_id', '=', $course->course_id)
                            ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                            ->select('tbl_alumni.alumni_id')
                            // ->distinct()
                            ->where('form_sas_answers.question_id', '=', 1)
                            ->get();
                        $perCourse = Alumni::where('tbl_alumni.course_id', '=', $course->course_id)
                            ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                            ->select('tbl_alumni.alumni_id')
                            // ->distinct()
                            ->get();
                    }
                    else {
                        $checkCount = Alumni::join('form_sas_answers', 'tbl_alumni.alumni_id', '=', 'form_sas_answers.alumni_id')
                            ->where('tbl_alumni.course_id', '=', $course->course_id)
                            ->where('tbl_alumni.sex', '=', $request->sex)
                            ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                            ->select('tbl_alumni.alumni_id')
                            // ->distinct()
                            ->where('form_sas_answers.question_id', '=', 1)
                            ->get();
                        $perCourse = Alumni::where('tbl_alumni.course_id', '=', $course->course_id)
                            ->where('tbl_alumni.sex', '=', $request->sex)
                            ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                            ->select('tbl_alumni.alumni_id')
                            // ->distinct()
                            ->get();
                    }
                    $html .= '<tr>
                            <th class="th-EI" colspan="1" style="width: 70%;">' . $course->course_desc . '</th>
                            <td class="th-EI" colspan="2" style="width: 15%;">' . count($checkCount) . '</td>
                            <td class="th-EI" colspan="1" style="width: 15%;">' . count($perCourse)-count($checkCount) . '</td>
                        </tr>';
                }
            if($request->course_id == 'All'){
                $total = Alumni::join('form_sas_answers', 'tbl_alumni.alumni_id', '=', 'form_sas_answers.alumni_id')
                            ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                            ->select('tbl_alumni.alumni_id')
                            // ->distinct()
                            ->where('form_sas_answers.question_id', '=', 1)
                            ->get();
                $totalStudents = Alumni::whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])->get();
            }else{
                $total = Alumni::join('form_sas_answers', 'tbl_alumni.alumni_id', '=', 'form_sas_answers.alumni_id')
                            ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                            ->where('course_id', '=', $request->course_id)
                            ->select('tbl_alumni.alumni_id')
                            // ->distinct()
                            ->where('form_sas_answers.question_id', '=', 1)
                            ->get();
                $totalStudents = Alumni::whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])->get();
            }
            
            $totalPending = (count($totalStudents)-count($total));
            $html .= '<tr>
                    <th class="th-EI" colspan="1" style="width: 70%;">TOTAL</th>
                    <td class="th-EI" colspan="2" style="width: 15%; font-weight: bold;">' . count($total) . '</td>
                    <td class="th-EI" colspan="1" style="width: 15%; font-weight: bold;">' . $totalPending . '</td>
                </tr>';
            $html .= '</table>';
        $pdf->writeHTML($html, true, 0, true, 0);

        $totalPending = 0;
        $pdf->ln(2);
        $pdf->SetFont('times', 'B', 11);
        $pdf->MultiCell(88, 5, 'TABLE 2. AGE ', 0, 'L', 0, 0, '', '', true);
        $pdf->MultiCell(30, 5, 'TABLE 3. SEX', 0, 'R', 0, 0, '', '', true);
        $pdf->ln(5);
        $pdf->SetFont('times', '', 11);
        $html = '
                <table style="padding: 0px;">
                    <tr style="padding: 0px;">
                        <td style="padding: 0px;">';
        $html .= '<style>
                .table-EI, .th-EI, .td-EI {
                    border: 1px solid black;
                    border-collapse: collapse;
                    padding: 3px 5px;
                }
                .theading {
                    background-color: #78281F;
                    color: #ffffff;
                }
                th {
                    font-weight: bold;
                }
                td {
                    text-align: center;
                }
            </style>
            <table class="table-EI">
                <tr class="" style="text-align: center; font-weight: bold;">
                    <th class="theading" colspan="1" style="width: 35%;"></th>
                    <td class="theading" colspan="1" style="width: 18%;">Count</td>
                    <td class="theading" colspan="1" style="width: 25%;">Percentage</td>
                </tr>';

            if($request->course_id == 'All'){
                $total = Alumni::join('form_sas_answers', 'tbl_alumni.alumni_id', '=', 'form_sas_answers.alumni_id')
                ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                ->select('tbl_alumni.alumni_id')
                ->distinct()
                ->get();
                $perAge = Alumni::join('form_sas_answers', 'tbl_alumni.alumni_id', '=', 'form_sas_answers.alumni_id')
                ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                ->where('tbl_alumni.age', '!=', null)
                ->select('tbl_alumni.age')
                ->orderBy('tbl_alumni.age', 'asc')
                ->distinct()
                ->get();
            }else{
                $total = Alumni::join('form_sas_answers', 'tbl_alumni.alumni_id', '=', 'form_sas_answers.alumni_id')
                ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                ->where('course_id', '=', $request->course_id)
                ->select('tbl_alumni.alumni_id')
                ->distinct()
                ->get();
                $perAge = Alumni::join('form_sas_answers', 'tbl_alumni.alumni_id', '=', 'form_sas_answers.alumni_id')
                ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                ->where('course_id', 'like', '%' . $request->course_id . '%')
                ->where('tbl_alumni.age', '!=', null)
                ->select('tbl_alumni.age')
                ->orderBy('tbl_alumni.age', 'asc')
                ->distinct()
                ->get();
            }

            foreach ($perAge as $age) {
                if($request->course_id == 'All'){
                    $perSpecificAge = Alumni::join('form_sas_answers', 'tbl_alumni.alumni_id', '=', 'form_sas_answers.alumni_id')
                    ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                    ->where('tbl_alumni.age', '=', $age->age)
                    ->where('tbl_alumni.sex', 'like', $request->sex . '%')
                    ->select('tbl_alumni.alumni_id')
                    ->where('form_sas_answers.question_id', '=', 1)
                    ->get();
                }else{
                    $perSpecificAge = Alumni::join('form_sas_answers', 'tbl_alumni.alumni_id', '=', 'form_sas_answers.alumni_id')
                    ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                    ->where('tbl_alumni.age', '=', $age->age)
                    ->where('tbl_alumni.sex', 'like', $request->sex . '%')
                    ->where('course_id', 'like', '%' . $request->course_id . '%')
                    ->select('tbl_alumni.alumni_id')
                    ->where('form_sas_answers.question_id', '=', 1)
                    ->get();
                }
                
                $html .= '<tr>
                    <th class="th-EI" colspan="1" style="width: 35%;">' . $age->age . ' years old </th>
                    <td class="th-EI" colspan="2" style="width: 18%;">' . count($perSpecificAge) . '</td>
                    <td class="th-EI" colspan="1" style="width: 25%;">' . number_format(count($perSpecificAge) / count($total) * 100, 2) . '% </td>
                </tr>';
            }
        $html .= '</table>
            </td>
            <td style="padding: 0px;">';
        $html .= '
            <table class="table-EI">
                <tr class="" style="text-align: center; font-weight: bold;">
                    <th class="theading" colspan="1" style="width: 30%;"></th>
                    <td class="theading" colspan="1" style="width: 18%;">Count</td>
                    <td class="theading" colspan="1" style="width: 25%;">Percentage</td>
                </tr>';
            if($request->course_id == 'All'){
                
                $total = Alumni::join('form_sas_answers', 'tbl_alumni.alumni_id', '=', 'form_sas_answers.alumni_id')
                ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                ->select('tbl_alumni.alumni_id')
                ->distinct()
                ->get();
            } else {
                $total = Alumni::join('form_sas_answers', 'tbl_alumni.alumni_id', '=', 'form_sas_answers.alumni_id')
                ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                ->where('course_id', '=', $request->course_id)
                ->select('tbl_alumni.alumni_id')
                ->distinct()
                ->get();
            }
            
            if ($request->course_id == 'All') {
                if($request->course_id == 'All'){
                    $perMale = Alumni::join('form_sas_answers', 'tbl_alumni.alumni_id', '=', 'form_sas_answers.alumni_id')
                    ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                    ->where('tbl_alumni.sex', '=', 'Male')
                    ->select('tbl_alumni.alumni_id')
                    ->where('form_sas_answers.question_id', '=', 1)
                    ->get();
                    $perFemale = Alumni::join('form_sas_answers', 'tbl_alumni.alumni_id', '=', 'form_sas_answers.alumni_id')
                    ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                    ->where('tbl_alumni.sex', '=', 'Female')
                    ->select('tbl_alumni.alumni_id')
                    ->where('form_sas_answers.question_id', '=', 1)
                    ->get();
                }else{
                    $perMale = Alumni::join('form_sas_answers', 'tbl_alumni.alumni_id', '=', 'form_sas_answers.alumni_id')
                    ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                    ->where('course_id', 'like', '%' . $request->course_id . '%')
                    ->where('tbl_alumni.sex', '=', 'Male')
                    ->select('tbl_alumni.alumni_id')
                    ->where('form_sas_answers.question_id', '=', 1)
                    ->get();
                    $perFemale = Alumni::join('form_sas_answers', 'tbl_alumni.alumni_id', '=', 'form_sas_answers.alumni_id')
                    ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                    ->where('course_id', 'like', '%' . $request->course_id . '%')
                    ->where('tbl_alumni.sex', '=', 'Female')
                    ->select('tbl_alumni.alumni_id')
                    ->where('form_sas_answers.question_id', '=', 1)
                    ->get();
                }
                
                $html .= '<tr>
                    <th class="th-EI" colspan="1" style="width: 30%;">MALE</th>
                    <td class="th-EI" colspan="2" style="width: 18%;">' . count($perMale) . '</td>
                    <td class="th-EI" colspan="1" style="width: 25%;">' . number_format(count($perMale) / count($total) * 100, 2) . '% </td>
                </tr>';
                $html .= '<tr>
                    <th class="th-EI" colspan="1" style="width: 30%;">FEMALE</th>
                    <td class="th-EI" colspan="2" style="width: 18%;">' . count($perFemale) .  '</td>
                    <td class="th-EI" colspan="1" style="width: 25%;">' . number_format(count($perFemale) / count($total) * 100, 2) . '% </td>
                </tr>';
            }
            else {
                if($request->course_id == 'All'){
                    $perSex = Alumni::join('form_sas_answers', 'tbl_alumni.alumni_id', '=', 'form_sas_answers.alumni_id')
                    ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                    ->where('tbl_alumni.sex', '=', $request->sex)
                    ->select('tbl_alumni.alumni_id')
                    ->distinct()
                    ->get();
                }else{
                    $perSex = Alumni::join('form_sas_answers', 'tbl_alumni.alumni_id', '=', 'form_sas_answers.alumni_id')
                    ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                    ->where('tbl_alumni.sex', '=', $request->sex)
                    ->where('course_id', 'like', '%' . $request->course_id . '%')
                    ->select('tbl_alumni.alumni_id')
                    ->distinct()
                    ->get();
                }

                $html .= '<tr>
                    <th class="th-EI" colspan="1" style="width: 30%;">' . strtoupper($request->sex) . '</th>
                    <td class="th-EI" colspan="2" style="width: 20%;">' . count($perSex) .  '</td>
                    <td class="th-EI" colspan="1" style="width: 20%;">' . number_format(count($perSex) / count($total) * 100, 2) . '% </td>
                </tr>';
            }

        $html .= '</table>';
        $html .= '</td>
                </tr>
            </table>';
        $pdf->writeHTML($html, true, 0, true, 0);

        $sasCategories = SasCategories::whereIn('category_id', [3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14])->get();
        $numeral = 4;
        foreach ($sasCategories as $categories) {
            if ($numeral == 11 || $numeral == 13) {
                $pdf->SetPrintHeader(true);
                $pdf->AddPage('L');
                $pdf->SetPrintHeader(false);
                $style3 = array('width' => .5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
                $pdf->Line(10, 33, 288, 33, $style3);
                $pdf->ln(15);
            }
            elseif ($numeral == 12 || $numeral == 14) {
                $pdf->ln(3);
                $pdf->SetPrintHeader(true);
            }
            else {
                $pdf->SetPrintHeader(true);
                $pdf->AddPage('L');
                $pdf->SetPrintHeader(false);
                $style3 = array('width' => .5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
                $pdf->Line(10, 33, 288, 33, $style3);
                $pdf->ln(20);
            }
            $pdf->SetFont('times', 'B', 11);
            $pdf->Cell(0, 0, 'TABLE ' . $numeral . '. ' . strtoupper($categories->category_name), 0, 1, 'L', 0, '', 0);
            $pdf->SetFont('times', '', 11);

            if($request->course_id == 'All'){
                $total = Alumni::join('form_sas_answers', 'tbl_alumni.alumni_id', '=', 'form_sas_answers.alumni_id')
                ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                ->select('tbl_alumni.alumni_id')
                ->where('form_sas_answers.question_id', '=', 1)
                //->distinct()
                ->get();
            }else{
                $total = Alumni::join('form_sas_answers', 'tbl_alumni.alumni_id', '=', 'form_sas_answers.alumni_id')
                ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                ->where('course_id', '=', $request->course_id)
                ->select('tbl_alumni.alumni_id')
                ->where('form_sas_answers.question_id', '=', 1)
                //->distinct()
                ->get();
            }

            $sasQuestions = SasQuestions::where('category_id', '=', $categories->category_id)->get();

            $html = '<style>
                    .table-EI, .th-EI, .td-EI {
                        border: 1px solid black;
                        border-collapse: collapse;
                        padding: 3px;
                    }
                    .theading {
                        background-color: #78281F;
                        color: #ffffff;
                        text-align: center;
                    }
                    th {
                        font-weight: bold;
                    }
                    td {
                        text-align: center;
                    }
                </style>
                <table class="table-EI">
                    <tr>
                        <th rowspan="2" class="theading" style="width: 28%"></th>
                        <th colspan="2" class="theading" style="width: 18%">1 - Very Satisfactory</th>
                        <th colspan="2" class="theading" style="width: 18%">2 - Satisfactory</th>
                        <th colspan="2" class="theading" style="width: 18%">3 - Unsatisfactory</th>
                        <th colspan="2" class="theading" style="width: 18%">4 - Very Unsatisfactory</th>
                    </tr>
                    <tr>
                        <th class="th-EI" colspan="1" style="text-align: center;">No. of Respondents</th>
                        <th class="th-EI" colspan="1" style="text-align: center;">Percentage</th>
                        <th class="th-EI" colspan="1" style="text-align: center;">No. of Respondents</th>
                        <th class="th-EI" colspan="1" style="text-align: center;">Percentage</th>
                        <th class="th-EI" colspan="1" style="text-align: center;">No. of Respondents</th>
                        <th class="th-EI" colspan="1" style="text-align: center;">Percentage</th>
                        <th class="th-EI" colspan="1" style="text-align: center;">No. of Respondents</th>
                        <th class="th-EI" colspan="1" style="text-align: center;">Percentage</th>
                    </tr>';

            foreach ($sasQuestions as $questions) {

                if($request->course_id == 'All'){
                    $perAnswer = Alumni::join('form_sas_answers', 'tbl_alumni.alumni_id', '=', 'form_sas_answers.alumni_id')
                            ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                            ->where('form_sas_answers.question_id', '=', $questions->question_id)
                            ->select(DB::raw('form_sas_answers.answer'))
                            ->groupBy('tbl_alumni.alumni_id', 'form_sas_answers.answer')
                            ->get();
                    // $perAnswer = Alumni::join('form_sas_answers', 'tbl_alumni.alumni_id', '=', 'form_sas_answers.alumni_id')
                    // ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                    // ->where('tbl_alumni.sex', 'like', $request->sex . '%')
                    // ->where('form_sas_answers.question_id', '=', $questions->question_id)
                    // ->select('form_sas_answers.answer')
                    // ->get();
                }else{
                    $perAnswer = Alumni::join('form_sas_answers', 'tbl_alumni.alumni_id', '=', 'form_sas_answers.alumni_id')
                    ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                    ->where('course_id', 'like', '%' . $request->course_id . '%')
                    ->where('form_sas_answers.question_id', '=', $questions->question_id)
                    ->select(DB::raw('form_sas_answers.answer'))
                    ->groupBy('tbl_alumni.alumni_id', 'form_sas_answers.answer')
                    ->get();
                }
                

                $legend1 = 0;
                $legend2 = 0;
                $legend3 = 0;
                $legend4 = 0;
                foreach ($perAnswer as $answers) {
                    if ($answers->answer == 1) {
                        $legend1++;
                        // $legend1 = $answer->
                    }
                    elseif ($answers->answer == 2) {
                        $legend2++;
                    }
                    elseif ($answers->answer == 3) {
                        $legend3++;
                    }
                    elseif ($answers->answer == 4) {
                        $legend4++;
                    }
                }
                $html .= '<tr>
                        <th class="th-EI" style="text-align: left;">' . $questions->question_text .'</th>
                        <td class="tdata td-EI">' . $legend1 . '</td>
                        <td class="tdata td-EI">' . number_format($legend1 / count($total) * 100, 2) . '%</td>
                        <td class="tdata td-EI">' . $legend2 . '</td>
                        <td class="tdata td-EI">' . number_format($legend2 / count($total) * 100, 2) . '%</td>
                        <td class="tdata td-EI">' . $legend3 . '</td>
                        <td class="tdata td-EI">' . number_format($legend3 / count($total) * 100, 2) . '%</td>
                        <td class="tdata td-EI">' . $legend4 . '</td>
                        <td class="tdata td-EI">' . number_format($legend4 / count($total) * 100, 2) . '%</td>
                    </tr>';
            }
            $html .= '</table>';
            $pdf->writeHTML($html, true, 0, true, 0);
            $numeral++;
        }

        $pdf->lastPage();
        $user = auth()->user();
        Log::channel('admin')->info("UserId:".$user->user_id." | AdminId: ".$user->admin_id." | Username: ".$user->name." - Generated a Detailed SAS Report");
        $pdf->Output('SAS_Detailed_Report_' . date('m-d-y') . '.pdf', 'I');
    }
}