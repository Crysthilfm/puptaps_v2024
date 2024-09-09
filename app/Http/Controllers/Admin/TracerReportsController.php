<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\Courses;
use App\Models\Tracer\TracerAnswers;
use App\Models\Tracer\TracerQuestions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use DB;
use TCPDF;

class MYPDF extends TCPDF
{

    //Page header
    public function Header()
    {
        // Logo
        // $image_file = public_path('/img/pupLogo.png');
        $image_file = asset('img/pupLogo.png');
        $this->Image($image_file, 15, 10, 20, '', 'PNG', '', 'C', false, 300, '', false, false, 0, false, false, false);
        // Title
        $this->Ln(3);
        $this->SetFont('times', '', 12);
        $this->Cell(95, 0, 'Republic of the Philippines', 0, 1, 'C', 0, '', 0, false, 'T', 'M');
        $this->SetFont('times', 'B', 15);
        $this->Cell(179, 0, 'POLYTECHNIC UNIVERSITY OF THE PHILIPPINES', 0, 1, 'C', 0, '', 0, false, 'T', 'M');
        $this->SetFont('times', '', 12);
        $this->Cell(158, 0, 'Office of the Vice President for Branches and Satelite Campuses', 0, 1, 'C', 0, '', 0, false, 'T', 'M' );
        $this->SetFont('times', 'B', 12);
        $this->Cell(85, 0, 'TAGUIG BRANCH', 0, 1, 'C', 0, '', 0, false, 'T', 'M');

        $style3 = array('width' => .5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));

        // Line
        $this->Line(10, 33, 200, 33, $style3);
    }

    // Page footer
    public function Footer()
    {
        // Position at 10 mm from bottom
        $this->SetY(-10);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $style3 = array('width' => .5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));

        // Line
        $this->Line(10, 288, 200, 288, $style3);
        $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}


class TracerReportsController extends Controller
{
    public function tracerReportToPdf(Request $request) {
        switch($request->report_type) {
            case 1:
                $this->tracerSummaryReport($request);
                break;
            case 2:
                $this->tracerStatusReport($request);
                break;
            case 3:
                $this->tracerAlumniProfile($request);
                break;
            case 4:
                $this->tracerBoardPassers($request);
                break;
            default:
                return back()->with('fail', 'Error! Please try again.');
        }
    }

    public function tracerSummaryReport($request) {
        $totalRespondents = TracerAnswers::where('question_id', '=', 1)->join('tbl_alumni', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')->whereBetween('batch', [$request->batch_from, $request->batch_to])->get();
        $allAlumni = Alumni::whereBetween('batch', [$request->batch_from, $request->batch_to])->get();

        $total = 0;
        foreach ($allAlumni as $alumni) {
            $checkAlumni = TracerAnswers::where('alumni_id', '=', $alumni->alumni_id)->get();
            foreach ($checkAlumni as $check) {
                if ($check->question_id == 6) {
                    if ($check->answer != 'Unemployed') {
                        $total++;
                    }
                }
            }
        }

        if ($total > 0) {
            $answer1_1 = TracerAnswers::where('question_id', '=', 1)->where('answer', '=', 'Yes')->join('tbl_alumni', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')->whereBetween('batch', [$request->batch_from, $request->batch_to])->get();
            $answer2_1 = TracerAnswers::where('question_id', '=', 2)->where('answer', '=', 'Yes')->join('tbl_alumni', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')->whereBetween('batch', [$request->batch_from, $request->batch_to])->get();

            $answer3_1 = TracerAnswers::where('question_id', '=', 3)->where('answer', '=', 'Electronics Engineer Licensure Examination')->join('tbl_alumni', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')->whereBetween('batch', [$request->batch_from, $request->batch_to])->get();
            $answer3_2 = TracerAnswers::where('question_id', '=', 3)->where('answer', '=', 'Licensure Examination for Teachers')->join('tbl_alumni', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')->whereBetween('batch', [$request->batch_from, $request->batch_to])->get();
            $answer3_3 = TracerAnswers::where('question_id', '=', 3)->where('answer', '=', 'Certified Public Accountant Board Exam')->join('tbl_alumni', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')->whereBetween('batch', [$request->batch_from, $request->batch_to])->get();
            $answer3_4 = TracerAnswers::where('question_id', '=', 3)->where('answer', '=', 'Professional Mechanical Engineer Exam')->join('tbl_alumni', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')->whereBetween('batch', [$request->batch_from, $request->batch_to])->get();

            $answer5_1 = TracerAnswers::where('question_id', '=', 5)->where('answer', '=', 'Yes')->join('tbl_alumni', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')->whereBetween('batch', [$request->batch_from, $request->batch_to])->get();

            $answer10_1 = TracerAnswers::where('question_id', '=', 10)->where('answer', '!=', 'Unemployed')->join('tbl_alumni', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')->whereBetween('batch', [$request->batch_from, $request->batch_to])->get();
            $answer10_2 = TracerAnswers::where('question_id', '=', 10)->where('answer', '=', 'Unemployed')->join('tbl_alumni', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')->whereBetween('batch', [$request->batch_from, $request->batch_to])->get();

            $answer14_1 = TracerAnswers::where('question_id', '=', 14)->where('answer', '=', 'Yes')->join('tbl_alumni', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')->whereBetween('batch', [$request->batch_from, $request->batch_to])->get();
            $answer14_2 = TracerAnswers::where('question_id', '=', 14)->where('answer', '=', 'No')->join('tbl_alumni', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')->whereBetween('batch', [$request->batch_from, $request->batch_to])->get();

            $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

            // set document information
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetTitle('Tracer Reports');

            // set default monospaced font
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

            // set margins
            $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
            $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

            // set auto page breaks
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

            // set image scale factor
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

            $pdf->SetPrintHeader(true);
            $pdf->AddPage();
            $pdf->SetPrintHeader(false);
            $pdf->SetFont('times', 'B', 13);
            $pdf->ln(20);
            $pdf->Cell(0, 0, 'ALUMNI TRACER - SUMMARY REPORT', 0, 1, 'C', 0, '', 0);
            $pdf->SetFont('times', '', 12);
            $pdf->ln(1);
            $pdf->Cell(0, 0, 'ALUMNI BATCH FROM ' . $request->batch_from . ' TO ' . $request->batch_to, 0, 1, 'C', 0, '', 0);
            date_default_timezone_set('Asia/Manila');
            $pdf->Cell(0, 0, 'Date Generated: ' . date('F d, Y'), 0, 1, 'C', 0, '', 0);

            $pdf->ln(15);
            $pdf->SetFont('times', 'B', 12);
            $pdf->Cell(0, 0, 'TABLE I. ALUMNI CURRENT JOB', 0, 1, 'L', 0, '', 0);

            $pdf->SetFont('times', '', 12);
            $html = '<style>
                        .table-EI, .th-EI, .td-EI {
                            border: 1px solid black;
                            border-collapse: collapse;
                            padding: 5px 10px;
                        }
                        .theading {
                            background-color: #78281F;
                            color: #ffffff;
                        }
                        th {
                            font-weight: normal;
                        }
                        td {
                            text-align: center;
                        }
                        table {
                            padding: 5px;
                        }
                    </style>
                    <table class="table-EI" style="width:100%;">
                        <tr class="theading" style="text-align: center; font-weight: bold;">
                            <td class="" colspan="1" style="width: 67%;"></td>
                            <td class="" colspan="1" style="width: 18%;">No. of Alumni</td>
                            <td class="" colspan="1" style="width: 15%;">Percentage</td>
                        </tr>
                        <tr>
                            <th class="th-EI" colspan="1" style="width: 67%;">Alumni who are currently Employed</th>
                            <td class="th-EI" colspan="1" style="width: 18%;">' . count($answer10_1) . '</td>
                            <td class="th-EI" colspan="1" style="width: 15%;">' . number_format(count($answer10_1) / count($totalRespondents) * 100, 2, '.', '') . '%' . '</td>
                        </tr>
                        <tr>
                            <th class="th-EI" colspan="1" style="width: 67%;">Alumni who are currently Unemployed</th>
                            <td class="th-EI" colspan="1" style="width: 18%;">' . count($answer10_2) . '</td>
                            <td class="th-EI" colspan="1" style="width: 15%;">' . number_format(count($answer10_2) / count($totalRespondents) * 100, 2, '.', '') . '%' . '</td>
                        </tr>
                        <tr>
                            <th class="th-EI" colspan="1" style="width: 67%;">Alumni whose Current Job is related to the course/program graduated in PUP Taguig</th>
                            <td class="th-EI" colspan="1" style="width: 18%;">' . count($answer14_1) . '</td>
                            <td class="th-EI" colspan="1" style="width: 15%;">' . number_format(count($answer14_1) / $total * 100, 2, '.', '') . '%' . '</td>
                        </tr>
                        <tr>
                            <th class="th-EI" colspan="1" style="width: 67%;">Alumni whose Current Job is NOT related to the course/program graduated in PUP Taguig</th>
                            <td class="th-EI" colspan="1" style="width: 18%;">' . count($answer14_2) . '</td>
                            <td class="th-EI" colspan="1" style="width: 15%;">' . number_format(count($answer14_2) / $total * 100, 2, '.', '') . '%' . '</td>
                        </tr>
                    </table>';

            $pdf->writeHTML($html, true, 0, true, 0);

            $pdf->ln(5);
            $pdf->SetFont('times', 'B', 12);
            $pdf->Cell(0, 0, 'TABLE II. LICENSURE EXAMINATIONS RESULT', 0, 1, 'L', 0, '', 0);
            $pdf->SetFont('times', '', 12);
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
                        td {
                            text-align: center;
                        }
                        th {
                            font-weight: normal;
                        }
                        table {
                            padding: 5px;
                        }
                    </style>
                    <table class="table-EI" style="width:100%;">
                        <tr class="theading" style="text-align: center; font-weight: bold;">
                            <th class="" colspan="1" style="width: 67%;"></th>
                            <td class="" colspan="1" style="width: 18%;">No. of Alumni</td>
                            <td class="" colspan="1" style="width: 15%;">Percentage</td>
                        </tr>
                        <tr>
                            <th class="th-EI" colspan="1" style="width: 67%;">Alumni who passed the Board Exams</th>
                            <td class="th-EI" colspan="1" style="width: 18%;">' . count($answer1_1) . '</td>
                            <td class="th-EI" colspan="1" style="width: 15%;">' . number_format(count($answer1_1) / count($totalRespondents) * 100, 2, '.', '') . '%' . '</td>
                        </tr>
                        <tr>
                            <th class="th-EI" colspan="1" style="width: 67%;">Alumni who has a license related to the course/program graduated in PUP Taguig</th>
                            <td class="th-EI" colspan="1" style="width: 18%;">' . count($answer2_1) . '</td>
                            <td class="th-EI" colspan="1" style="width: 15%;">' . number_format(count($answer2_1) / count($totalRespondents) * 100, 2, '.', '') . '%' . '</td>
                        </tr>
                        <tr>
                            <th class="th-EI" colspan="1" style="width: 67%;">Alumni who passed the Electronics Engineer Licensure Examination</th>
                            <td class="th-EI" colspan="1" style="width: 18%;">' . count($answer3_1) . '</td>
                            <td class="th-EI" colspan="1" style="width: 15%;">' . number_format(count($answer3_1) / count($totalRespondents) * 100, 2, '.', '') . '%' . '</td>
                        </tr>
                        <tr>
                            <th class="th-EI" colspan="1" style="width: 67%;">Alumni who passed the Licensure Examination for Teachers</th>
                            <td class="th-EI" colspan="1" style="width: 18%;">' . count($answer3_2) . '</td>
                            <td class="th-EI" colspan="1" style="width: 15%;">' . number_format(count($answer3_2) / count($totalRespondents) * 100, 2, '.', '') . '%' . '</td>
                        </tr>
                        <tr>
                            <th class="th-EI" colspan="1" style="width: 67%;">Alumni who passed the Certified Public Accountant Board Exam</th>
                            <td class="th-EI" colspan="1" style="width: 18%;">' . count($answer3_3) . '</td>
                            <td class="th-EI" colspan="1" style="width: 15%;">' . number_format(count($answer3_3) / count($totalRespondents) * 100, 2, '.', '') . '%' . '</td>
                        </tr>
                        <tr>
                            <th class="th-EI" colspan="1" style="width: 67%;">Alumni who passed the Professional Mechanical Engineer Exam</th>
                            <td class="th-EI" colspan="1" style="width: 18%;">' . count($answer3_4)  . '</td>
                            <td class="th-EI" colspan="1" style="width: 15%;">' . number_format(count($answer3_4) / count($totalRespondents) * 100, 2, '.', '') . '%' . '</td>
                        </tr>
                        <tr>
                            <th class="th-EI" colspan="1" style="width: 67%;">Alumni who passed the Civil Service Examination</th>
                            <td class="th-EI" colspan="1" style="width: 18%;">' . count($answer5_1) . '</td>
                            <td class="th-EI" colspan="1" style="width: 15%;">' . number_format(count($answer5_1) / count($totalRespondents) * 100, 2, '.', '') . '%' . '</td>
                        </tr>
                    </table>';

            $pdf->writeHTML($html, true, 0, true, 0);

            $pdf->lastPage();
            //Close and output PDF document
            $user = auth()->user();
            Log::channel('admin')->info("UserId:".$user->user_id." | AdminId: ".$user->admin_id." | Username: ".$user->name." - Generated an Tracer Summary Report");
            $pdf->Output(date('m-d-y') . ' Tracer_Summary_Report' . '.pdf', 'I');
        } else {
            $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

            // set document information
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetTitle('Alumni Reports');

            // set default monospaced font
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

            // set margins
            $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
            $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

            // set auto page breaks
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

            // set image scale factor
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

            $pdf->SetPrintHeader(true);
            $pdf->AddPage();
            $pdf->SetPrintHeader(false);
            $pdf->SetFont('times', 'B', 13);
            $pdf->ln(20);
            $pdf->Cell(0, 0, 'ALUMNI TRACER - SUMMARY REPORT', 0, 1, 'C', 0, '', 0);
            $pdf->SetFont('times', '', 12);
            $pdf->ln(1);
            $pdf->Cell(0, 0, 'ALUMNI BATCH FROM ' . $request->batch_from . ' TO ' . $request->batch_to, 0, 1, 'C', 0, '', 0);
            date_default_timezone_set('Asia/Manila');
            $pdf->Cell(0, 0, 'Date Generated: ' . date('F d, Y'), 0, 1, 'C', 0, '', 0);

            $pdf->ln(20);
            $pdf->Cell(0, 0, 'Sorry! No data available.', 0, 1, 'C', 0, '', 0);
            $pdf->lastPage();
            //Close and output PDF document
            $user = auth()->user();
            Log::channel('admin')->info("UserId:".$user->user_id." | AdminId: ".$user->admin_id." | Username: ".$user->name." - Generated an Tracer Summary Report");
            $pdf->Output(date('m-d-y') . ' Tracer_Summary_Report' . '.pdf', 'I');
        }
    }

    public function tracerStatusReport($request) {
        $totalAlumni = Alumni::whereBetween('batch', [$request->batch_from, $request->batch_to])->get();
        $total = TracerAnswers::where('question_id', '=', 1)->join('tbl_alumni', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')->whereBetween('batch', [$request->batch_from, $request->batch_to])->get();

        if (count($total) > 0 || count($total) != null) {
            $bsed_eng = Alumni::where('course_id', '=', 'BSEd-English')
                ->join('tbl_tracer_answers', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')
                ->whereBetween('batch', [$request->batch_from, $request->batch_to])
                ->where('question_id', '=', 1)
                ->select('tbl_alumni.*')
                ->get();
            $bsed_math = Alumni::where('course_id', '=', 'BSEd-Mathematics')
                ->join('tbl_tracer_answers', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')
                ->whereBetween('batch', [$request->batch_from, $request->batch_to])
                ->where('question_id', '=', 1)
                ->select('tbl_alumni.*')
                ->get();
            $dict = Alumni::where('course_id', '=', 'DICT')
                ->join('tbl_tracer_answers', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')
                ->whereBetween('batch', [$request->batch_from, $request->batch_to])
                ->where('question_id', '=', 1)
                ->select('tbl_alumni.*')
                ->get();
            $bsit = Alumni::where('course_id', '=', 'BSIT')
                ->join('tbl_tracer_answers', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')
                ->whereBetween('batch', [$request->batch_from, $request->batch_to])
                ->where('question_id', '=', 1)
                ->select('tbl_alumni.*')
                ->get();
            $bshr = Alumni::where('course_id', '=', 'BSBA-HRM')
                ->join('tbl_tracer_answers', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')
                ->whereBetween('batch', [$request->batch_from, $request->batch_to])
                ->where('question_id', '=', 1)
                ->select('tbl_alumni.*')
                ->get();
            $bsmm = Alumni::where('course_id', '=', 'BSBA-MM')
                ->join('tbl_tracer_answers', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')
                ->whereBetween('batch', [$request->batch_from, $request->batch_to])
                ->where('question_id', '=', 1)
                ->select('tbl_alumni.*')
                ->get();
            $bsece = Alumni::where('course_id', '=', 'BSECE')
                ->join('tbl_tracer_answers', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')
                ->whereBetween('batch', [$request->batch_from, $request->batch_to])
                ->where('question_id', '=', 1)
                ->select('tbl_alumni.*')
                ->get();
            $bsme = Alumni::where('course_id', '=', 'BSME')
                ->join('tbl_tracer_answers', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')
                ->whereBetween('batch', [$request->batch_from, $request->batch_to])
                ->where('question_id', '=', 1)
                ->select('tbl_alumni.*')
                ->get();
            $domt = Alumni::where('course_id', '=', 'DOMT')
                ->join('tbl_tracer_answers', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')
                ->whereBetween('batch', [$request->batch_from, $request->batch_to])
                ->where('question_id', '=', 1)
                ->select('tbl_alumni.*')
                ->get();
            $bsoa = Alumni::where('course_id', '=', 'BSOA')
                ->join('tbl_tracer_answers', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')
                ->whereBetween('batch', [$request->batch_from, $request->batch_to])
                ->where('question_id', '=', 1)
                ->select('tbl_alumni.*')
                ->get();
            $bsa = Alumni::where('course_id', '=', 'BSA')
                ->join('tbl_tracer_answers', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')
                ->whereBetween('batch', [$request->batch_from, $request->batch_to])
                ->where('question_id', '=', 1)
                ->select('tbl_alumni.*')
                ->get();


            $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

            // set document information
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetTitle('Tracer Reports');

            // set default monospaced font
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

            // set margins
            $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
            $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

            // set auto page breaks
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

            // set image scale factor
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

            $pdf->SetPrintHeader(true);
            $pdf->AddPage();
            $pdf->SetPrintHeader(false);
            $pdf->SetFont('times', 'B', 13);
            $pdf->ln(20);
            $pdf->Cell(0, 0, 'ALUMNI TRACER - STATUS REPORT', 0, 1, 'C', 0, '', 0);
            $pdf->SetFont('times', '', 12);
            $pdf->ln(1);
            $pdf->Cell(0, 0, 'ALUMNI BATCH FROM ' . $request->batch_from . ' TO ' . $request->batch_to, 0, 1, 'C', 0, '', 0);
            date_default_timezone_set('Asia/Manila');
            $pdf->Cell(0, 0, 'Date Generated: ' . date('F d, Y'), 0, 1, 'C', 0, '', 0);

            $pdf->ln(10);
            $pdf->SetFont('times', 'B', 12);
            $pdf->Cell(0, 0, 'TABLE I. NUMBER OF ALUMNI', 0, 1, 'L', 0, '', 0);
            $pdf->SetFont('times', '', 12);
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
                    td {
                        text-align: center;
                    }
                    th {
                        font-weight: bold;
                    }
                    table {
                        padding: 5px;
                    }
                </style>
                <table class="table-EI" style="width:100%;">
                    <tr class="theading" style="text-align: center; font-weight: bold;">
                        <th class="" colspan="1" style="width: 67%;"></th>
                        <td class="" colspan="1" style="width: 18%;">No. of Alumni</td>
                        <td class="" colspan="1" style="width: 15%;">Percentage</td>
                    </tr>
                    <tr>
                        <th class="th-EI" colspan="1" style="width: 67%;">Number of Tracer Respondents</th>
                        <td class="th-EI" colspan="1" style="width: 18%;">' . count($total) . '</td>
                        <td class="th-EI" colspan="1" style="width: 15%;">' . number_format(count($total) / count($totalAlumni) * 100, 2, '.', '') . '% </td>
                    </tr>
                    <tr>
                        <th class="th-EI" colspan="1" style="width: 67%;">Number of Alumni from batch 2022 to present</th>
                        <td class="th-EI" colspan="2" style="width: 33%;">' . count($totalAlumni) . '</td>
                    </tr>
                </table>';
            $pdf->writeHTML($html, true, 0, true, 0);

            $pdf->ln(3);
            $pdf->SetFont('times', 'B', 12);
            $pdf->Cell(0, 0, 'TABLE II. RESPONDENTS PER COURSE', 0, 1, 'L', 0, '', 0);
            $pdf->SetFont('times', '', 12);
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
                    td {
                        text-align: center;
                    }
                    th {
                        font-weight: bold;
                    }
                    table {
                        padding: 5px;
                    }
                </style>
                <table class="table-EI" style="width:100%;">
                    <tr class="theading" style="text-align: center; font-weight: bold;">
                        <th class="" colspan="1" style="width: 67%;"></th>
                        <td class="" colspan="1" style="width: 18%;">No. of Alumni</td>
                        <td class="" colspan="1" style="width: 15%;">Percentage</td>
                    </tr>
                    <tr>
                        <th class="th-EI" colspan="1" style="width: 67%;">Bachelor in Secondary Education Major in English</th>
                        <td class="th-EI" colspan="1" style="width: 18%;">' . count($bsed_eng) . '</td>
                        <td class="th-EI" colspan="1" style="width: 15%;">' . number_format(count($bsed_eng) / count($totalAlumni) * 100, 2, '.', '') . '% </td>
                    </tr>
                    <tr>
                        <th class="th-EI" colspan="1" style="width: 67%;">Bachelor in Secondary Education Major in Mathematics</th>
                        <td class="th-EI" colspan="1" style="width: 18%;">' . count($bsed_math) . '</td>
                        <td class="th-EI" colspan="1" style="width: 15%;">' . number_format(count($bsed_math) / count($totalAlumni) * 100, 2, '.', '') . '% </td>
                    </tr>
                    <tr>
                        <th class="th-EI" colspan="1" style="width: 67%;">Diploma in Information Communication Technology</th>
                        <td class="th-EI" colspan="1" style="width: 18%;">' . count($dict) . '</td>
                        <td class="th-EI" colspan="1" style="width: 15%;">' . number_format(count($dict) / count($totalAlumni) * 100, 2, '.', '') . '% </td>
                    </tr>
                    <tr>
                        <th class="th-EI" colspan="1" style="width: 67%;">Bachelor of Science in Information Technology</th>
                        <td class="th-EI" colspan="1" style="width: 18%;">' . count($bsit) . '</td>
                        <td class="th-EI" colspan="1" style="width: 15%;">' . number_format(count($bsit) / count($totalAlumni) * 100, 2, '.', '') . '% </td>
                    </tr>
                    <tr>
                        <th class="th-EI" colspan="1" style="width: 67%;">Bachelor of Science in Business Administration Major in Human Resource Management</th>
                        <td class="th-EI" colspan="1" style="width: 18%;">' . count($bshr) . '</td>
                        <td class="th-EI" colspan="1" style="width: 15%;">' . number_format(count($bshr) / count($totalAlumni) * 100, 2, '.', '') . '% </td>
                    </tr>
                    <tr>
                        <th class="th-EI" colspan="1" style="width: 67%;">Bachelor of Science in Business Administration Major in Marketing Management</th>
                        <td class="th-EI" colspan="1" style="width: 18%;">' . count($bsmm) . '</td>
                        <td class="th-EI" colspan="1" style="width: 15%;">' . number_format(count($bsmm) / count($totalAlumni) * 100, 2, '.', '') . '% </td>
                    </tr>
                    <tr>
                        <th class="th-EI" colspan="1" style="width: 67%;">Bachelor of Science in Electronics and Communications Engineering</th>
                        <td class="th-EI" colspan="1" style="width: 18%;">' . count($bsece) . '</td>
                        <td class="th-EI" colspan="1" style="width: 15%;">' . number_format(count($bsece) / count($totalAlumni) * 100, 2, '.', '') . '% </td>
                    </tr>
                    <tr>
                        <th class="th-EI" colspan="1" style="width: 67%;">Bachelor of Science in Mechanical Engineering</th>
                        <td class="th-EI" colspan="1" style="width: 18%;">' . count($bsme) . '</td>
                        <td class="th-EI" colspan="1" style="width: 15%;">' . number_format(count($bsme) / count($totalAlumni) * 100, 2, '.', '') . '% </td>
                    </tr>
                    <tr>
                        <th class="th-EI" colspan="1" style="width: 67%;">Diploma in Office Management Technology</th>
                        <td class="th-EI" colspan="1" style="width: 18%;">' . count($domt) . '</td>
                        <td class="th-EI" colspan="1" style="width: 15%;">' . number_format(count($domt) / count($totalAlumni) * 100, 2, '.', '') . '% </td>
                    </tr>
                    <tr>
                        <th class="th-EI" colspan="1" style="width: 67%;">Bachelor of Science in Office Administration</th>
                        <td class="th-EI" colspan="1" style="width: 18%;">' . count($bsoa) . '</td>
                        <td class="th-EI" colspan="1" style="width: 15%;">' . number_format(count($bsoa) / count($totalAlumni) * 100, 2, '.', '') . '% </td>
                    </tr>
                    <tr>
                        <th class="th-EI" colspan="1" style="width: 67%;">Bachelor of Science in Accountancy</th>
                        <td class="th-EI" colspan="1" style="width: 18%;">' . count($bsa) . '</td>
                        <td class="th-EI" colspan="1" style="width: 15%;">' . number_format(count($bsa) / count($totalAlumni) * 100, 2, '.', '') . '% </td>
                    </tr>
                </table>';
            $pdf->writeHTML($html, true, 0, true, 0);

            $pdf->lastPage();
            //Close and output PDF document
            $user = auth()->user();
            Log::channel('admin')->info("UserId:".$user->user_id." | AdminId: ".$user->admin_id." | Username: ".$user->name." - Generated an Tracer Status Report");
            $pdf->Output(date('m-d-y') . ' Tracer_Summary_Report' . '.pdf', 'I');
        } else {
            $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

            // set document information
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetTitle('Alumni Reports');

            // set default monospaced font
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

            // set margins
            $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
            $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

            // set auto page breaks
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

            // set image scale factor
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

            $pdf->SetPrintHeader(true);
            $pdf->AddPage();
            $pdf->SetPrintHeader(false);
            $pdf->SetFont('times', 'B', 13);
            $pdf->ln(20);
            $pdf->Cell(0, 0, 'ALUMNI TRACER - STATUS REPORT', 0, 1, 'C', 0, '', 0);
            $pdf->SetFont('times', '', 12);
            $pdf->ln(1);
            $pdf->Cell(0, 0, 'ALUMNI BATCH FROM ' . $request->batch_from . ' TO ' . $request->batch_to, 0, 1, 'C', 0, '', 0);
            date_default_timezone_set('Asia/Manila');
            $pdf->Cell(0, 0, 'Date Generated: ' . date('F d, Y'), 0, 1, 'C', 0, '', 0);

            $pdf->ln(20);
            $pdf->Cell(0, 0, 'Sorry! No data available.', 0, 1, 'C', 0, '', 0);
            $pdf->lastPage();
            //Close and output PDF document
            $user = auth()->user();
            Log::channel('admin')->info("UserId:".$user->user_id." | AdminId: ".$user->admin_id." | Username: ".$user->name." - Generated an Tracer Status Report");
            $pdf->Output(date('m-d-y') . ' Tracer_Status_Report' . '.pdf', 'I');
        }
    }

    public function tracerAlumniProfile($request) {
        $allAlumni = Alumni::whereBetween('batch', [$request->batch_from, $request->batch_to])->where('profile_status', 'Complete')->get();

        $ctr = 0;
        foreach ($allAlumni as $alumni) {
            $checkAlumni = TracerAnswers::where('alumni_id', '=', $alumni->alumni_id)->get();
            foreach ($checkAlumni as $check) {
                if ($check->question_id == 6) {
                    if ($check->answer != 'Unemployed') {
                        $ctr++;
                    }
                }
            }
        }

        if ($ctr > 0) {
            $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

            // set document information
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetTitle('Tracer Reports');

            // set default monospaced font
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

            // set margins
            $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
            $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

            // set auto page breaks
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

            // set image scale factor
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

            foreach ($allAlumni as $alumni) {
                $checkAnswer = TracerAnswers::where('alumni_id', '=', $alumni->alumni_id)->get();
                $boardPasser = TracerAnswers::where('alumni_id', '=', $alumni->alumni_id)->where('question_id', '=', 1)->value('answer');
                $boardExam = TracerAnswers::where('alumni_id', '=', $alumni->alumni_id)->where('question_id', '=', 3)->value('answer');
                $civilService = TracerAnswers::where('alumni_id', '=', $alumni->alumni_id)->where('question_id', '=', 5)->value('answer');

                $position = TracerAnswers::where('alumni_id', '=', $alumni->alumni_id)->where('question_id', '=', 6)->value('answer');
                if ($position === 'UNEMPLOYED') {
                    $position = 'N/A';
                    $company = 'N/A';
                    $startDate = 'N/A';
                    $empType = 'N/A';
                    $comEmail = 'N/A';
                    $comNumber = 'N/A';
                    $relation = 'N/A';
                }
                else {
                    $company = TracerAnswers::where('alumni_id', '=', $alumni->alumni_id)->where('question_id', '=', 7)->value('answer');
                    $startDate = TracerAnswers::where('alumni_id', '=', $alumni->alumni_id)->where('question_id', '=', 8)->value('answer');
                    $empType = TracerAnswers::where('alumni_id', '=', $alumni->alumni_id)->where('question_id', '=', 10)->value('answer');
                    $comEmail = TracerAnswers::where('alumni_id', '=', $alumni->alumni_id)->where('question_id', '=', 12)->value('answer');
                    $comNumber = TracerAnswers::where('alumni_id', '=', $alumni->alumni_id)->where('question_id', '=', 13)->value('answer');
                    $relation = TracerAnswers::where('alumni_id', '=', $alumni->alumni_id)->where('question_id', '=', 14)->value('answer');
                }

                $fposition = TracerAnswers::where('alumni_id', '=', $alumni->alumni_id)->where('question_id', '=', 15)->value('answer');
                $fcompany = TracerAnswers::where('alumni_id', '=', $alumni->alumni_id)->where('question_id', '=', 16)->value('answer');
                $fstart = TracerAnswers::where('alumni_id', '=', $alumni->alumni_id)->where('question_id', '=', 17)->value('answer');
                $fcomEmail = TracerAnswers::where('alumni_id', '=', $alumni->alumni_id)->where('question_id', '=', 19)->value('answer');
                $fcomNumber = TracerAnswers::where('alumni_id', '=', $alumni->alumni_id)->where('question_id', '=', 20)->value('answer');


                if (count($checkAnswer) > 0 && $position != 'UNEMPLOYED' || $fposition != 'UNEMPLOYED') {
                    $pdf->SetPrintHeader(true);
                    $pdf->AddPage();
                    $pdf->SetPrintHeader(false);
                    $pdf->SetFont('times', 'B', 13);
                    $pdf->ln(15);
                    $pdf->Cell(0, 0, 'ALUMNI TRACER - ALUMNI PROFILES', 0, 1, 'C', 0, '', 0);
                    $pdf->SetFont('times', '', 12);
                    $pdf->ln(1);
                    $pdf->Cell(0, 0, 'ALUMNI BATCH FROM ' . $request->batch_from . ' TO ' . $request->batch_to, 0, 1, 'C', 0, '', 0);
                    date_default_timezone_set('Asia/Manila');
                    $pdf->Cell(0, 0, 'Date Generated: ' . date('F d, Y'), 0, 1, 'C', 0, '', 0);

                    $pdf->ln(15);
                    $pdf->SetFont('times', 'B', 12);
                    $pdf->Cell(0, 0, 'PERSONAL INFORMATION', 0, 1, 'L', 0, '', 0);
                    $pdf->ln(3);

                    $pdf->SetFont('times', '', 12);
                    $fullname = $alumni->last_name . ', ' . $alumni->first_name . ' ' . $alumni->suffix . ' ' . $alumni->middle_name;

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

                    $pdf->Ln(-1);
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

                    $pdf->Ln(-1);
                    $html = <<<EOF
                    <table style="width:100%; margin-top: 10px;">
                        <tr>
                            <th colspan="1" style="width: 19%; font-weight: bold;">Year Graduated: </th>
                            <td colspan="1" style="border-bottom: 1px solid black; width: 10%;"> $alumni->batch </td>
                        </tr>
                    </table>
                    EOF;
                    $pdf->writeHTML($html, true, 0, true, 0);
                    $pdf->Ln(-1);
                    $html = <<<EOF
                    <table style="width:100%; margin-top: 10px;">
                        <tr>
                            <th colspan="1" style="width: 8%; font-weight: bold;">Email: </th>
                            <td colspan="1" style="border-bottom: 1px solid black; width: 45%;"> $alumni->email </td>
                            <td style="width: 8%;"></td>
                            <th colspan="1" style="width: 19%; font-weight: bold;">Contact Number: </th>
                            <td colspan="1" style="border-bottom: 1px solid black; width: 20%;"> $alumni->number </td>
                        </tr>
                    </table>
                    EOF;
                    $pdf->writeHTML($html, true, 0, true, 0);

                    $pdf->Ln(-1);
                    $html = '<style>
                            .table-EI, .th-EI, .td-EI {
                                border-collapse: collapse;
                            }
                            th {
                                font-weight: bold;
                            }
                        </style>
                        <table class="table-EI" style="width:100%;">
                            <tr>
                                <th class="th-EI" colspan="1" style="width: 16%;">Board Passer: </th>
                                <td class="td-EI" colspan="1" style="border-bottom: 1px solid black; width: 10%;"> ' . $boardPasser . '</td>
                                <td style="width: 5%;"></td>
                                <th class="th-EI" colspan="1" style="width: 15%;">Exam Taken: </th>
                                <td class="td-EI" colspan="1" style="border-bottom: 1px solid black; width: 54%;"> ' . $boardExam . '</td>
                            </tr>
                        </table>';
                    $pdf->writeHTML($html, true, 0, true, 0);

                    $pdf->Ln(-1);
                    $html = '<style>
                            .table-EI, .th-EI, .td-EI {
                                border-collapse: collapse;
                            }
                            th {
                                font-weight: bold;
                            }
                        </style>
                        <table class="table-EI" style="width:100%;">
                            <tr>
                                <th class="th-EI" colspan="1" style="width: 29%;">Civil Service Exam Passer: </th>
                                <td class="td-EI" colspan="1" style="border-bottom: 1px solid black; width: 10%;"> ' . $civilService . '</td>
                            </tr>
                        </table>';
                    $pdf->writeHTML($html, true, 0, true, 0);

                    $pdf->SetFont('times', 'B', 12);
                    $pdf->ln(5);
                    $pdf->Cell(0, 0, 'CURRENT CAREER DETAILS', 0, 1, 'C', 0, '', 0);

                    $pdf->ln(3);
                    $pdf->SetFont('times', '', 12);
                    $html = '<style>
                            .table-EI, .th-EI, .td-EI {
                                border-collapse: collapse;
                            }
                            th {
                                font-weight: bold;
                            }
                        </style>
                        <table class="table-EI" style="width:100%;">
                            <tr>
                                <th class="th-EI" colspan="1" style="width: 20%;">Position/Job Title: </th>
                                <td class="td-EI" colspan="1" style="border-bottom: 1px solid black; width: 30%;">' . $position . '</td>
                                <td style="width: 5%;"></td>
                                <th class="th-EI" colspan="1" style="width: 12%;">Company: </th>
                                <td class="td-EI" colspan="1" style="border-bottom: 1px solid black; width: 33%;">' . $company . '</td>
                            </tr>
                        </table>';
                    $pdf->writeHTML($html, true, 0, true, 0);

                    $pdf->ln(-1);
                    if($startDate === 'N/A') {
                        $html = '<style>
                            .table-EI, .th-EI, .td-EI {
                                border-collapse: collapse;
                            }
                            th {
                                font-weight: bold;
                            }
                        </style>
                        <table class="table-EI" style="width:100%;">
                            <tr>
                                <th class="th-EI" colspan="1" style="width: 26%;">Employment Start Date: </th>
                                <td class="td-EI" colspan="1" style="border-bottom: 1px solid black; width: 24%;">N/A</td>
                                <td style="width: 5%;"></td>
                                <th class="th-EI" colspan="1" style="width: 21%;">Employment Type: </th>
                                <td class="td-EI" colspan="1" style="border-bottom: 1px solid black; width: 24%;">' . $empType . '</td>
                            </tr>
                        </table>';
                    }
                    else {
                        $html = '<style>
                                .table-EI, .th-EI, .td-EI {
                                    border-collapse: collapse;
                                }
                                th {
                                    font-weight: bold;
                                }
                            </style>
                            <table class="table-EI" style="width:100%;">
                                <tr>
                                    <th class="th-EI" colspan="1" style="width: 26%;">Employment Start Date: </th>
                                    <td class="td-EI" colspan="1" style="border-bottom: 1px solid black; width: 24%;">' . date('F d, Y', strtotime($startDate)) . '</td>
                                    <td style="width: 5%;"></td>
                                    <th class="th-EI" colspan="1" style="width: 21%;">Employment Type: </th>
                                    <td class="td-EI" colspan="1" style="border-bottom: 1px solid black; width: 24%;">' . $empType . '</td>
                                </tr>
                            </table>';
                    }
                    $pdf->writeHTML($html, true, 0, true, 0);

                    $pdf->ln(-1);
                    $html = '<style>
                            .table-EI, .th-EI, .td-EI {
                                border-collapse: collapse;
                            }
                            th {
                                font-weight: bold;
                            }
                        </style>
                        <table class="table-EI" style="width:100%;">
                            <tr>
                                <th class="th-EI" colspan="1" style="width: 19%;">Company Email: </th>
                                <td class="td-EI" colspan="1" style="border-bottom: 1px solid black; width: 31%;">' . $comEmail . '</td>
                                <td style="width: 5%;"></td>
                                <th class="th-EI" colspan="1" style="width: 21%;">Company Number: </th>
                                <td class="td-EI" colspan="1" style="border-bottom: 1px solid black; width: 24%;">' . $comNumber . '</td>
                            </tr>
                        </table>';
                    $pdf->writeHTML($html, true, 0, true, 0);

                    $pdf->ln(-1);
                    $html = '<style>
                            .table-EI, .th-EI, .td-EI {
                                border-collapse: collapse;
                            }
                            th {
                                font-weight: bold;
                            }
                        </style>
                        <table class="table-EI" style="width:100%;">
                            <tr>
                                <th class="th-EI" colspan="1" style="width: 45%;">Is Current Job related to course/program?:</th>
                                <td class="td-EI" colspan="1" style="border-bottom: 1px solid black; width: 8%;">' . $relation . '</td>
                            </tr>
                        </table>';
                    $pdf->writeHTML($html, true, 0, true, 0);

                    $pdf->SetFont('times', 'B', 12);
                    $pdf->ln(5);
                    $pdf->Cell(0, 0, 'FIRST CAREER DETAILS', 0, 1, 'C', 0, '', 0);

                    $pdf->ln(3);
                    $pdf->SetFont('times', '', 12);
                    $html = '<style>
                            .table-EI, .th-EI, .td-EI {
                                border-collapse: collapse;
                            }
                            th {
                                font-weight: bold;
                            }
                        </style>
                        <table class="table-EI" style="width:100%;">
                            <tr>
                                <th class="th-EI" colspan="1" style="width: 20%;">Position/Job Title: </th>
                                <td class="td-EI" colspan="1" style="border-bottom: 1px solid black; width: 30%;">' . $fposition . '</td>
                                <td style="width: 5%;"></td>
                                <th class="th-EI" colspan="1" style="width: 12%;">Company: </th>
                                <td class="td-EI" colspan="1" style="border-bottom: 1px solid black; width: 33%;">' . $fcompany . '</td>
                            </tr>
                        </table>';
                    $pdf->writeHTML($html, true, 0, true, 0);

                    $pdf->ln(-1);
                    $html = '<style>
                            .table-EI, .th-EI, .td-EI {
                                border-collapse: collapse;
                            }
                            th {
                                font-weight: bold;
                            }
                        </style>
                        <table class="table-EI" style="width:100%;">
                            <tr>
                                <th class="th-EI" colspan="1" style="width: 26%;">Employment Start Date: </th>
                                <td class="td-EI" colspan="1" style="border-bottom: 1px solid black; width: 24%;">' . date('F d, Y', strtotime($fstart)) . '</td>
                            </tr>
                        </table>';
                    $pdf->writeHTML($html, true, 0, true, 0);

                    $pdf->ln(-1);
                    $html = '<style>
                            .table-EI, .th-EI, .td-EI {
                                border-collapse: collapse;
                            }
                            th {
                                font-weight: bold;
                            }
                        </style>
                        <table class="table-EI" style="width:100%;">
                            <tr>
                                <th class="th-EI" colspan="1" style="width: 19%;">Company Email: </th>
                                <td class="td-EI" colspan="1" style="border-bottom: 1px solid black; width: 31%;">' . $fcomEmail . '</td>
                                <td style="width: 5%;"></td>
                                <th class="th-EI" colspan="1" style="width: 21%;">Company Number: </th>
                                <td class="td-EI" colspan="1" style="border-bottom: 1px solid black; width: 24%;">' . $fcomNumber . '</td>
                            </tr>
                        </table>';
                    $pdf->writeHTML($html, true, 0, true, 0);

                    $pdf->lastPage();
                }
            }
            //Close and output PDF document
            $user = auth()->user();
            Log::channel('admin')->info("UserId:".$user->user_id." | AdminId: ".$user->admin_id." | Username: ".$user->name." - Generated an Tracer Alumni Profile Report");
            $pdf->Output(date('m-d-y') . ' Tracer_Summary_Report' . '.pdf', 'I');
        } else {
            $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

            // set document information
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetTitle('Alumni Reports');

            // set default monospaced font
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

            // set margins
            $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
            $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

            // set auto page breaks
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

            // set image scale factor
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

            $pdf->SetPrintHeader(true);
            $pdf->AddPage();
            $pdf->SetPrintHeader(false);
            $pdf->SetFont('times', 'B', 13);
            $pdf->ln(20);
            $pdf->Cell(0, 0, 'ALUMNI TRACER - PROFILE REPORT', 0, 1, 'C', 0, '', 0);
            $pdf->SetFont('times', '', 12);
            $pdf->ln(1);
            $pdf->Cell(0, 0, 'ALUMNI BATCH FROM ' . $request->batch_from . ' TO ' . $request->batch_to, 0, 1, 'C', 0, '', 0);
            date_default_timezone_set('Asia/Manila');
            $pdf->Cell(0, 0, 'Date Generated: ' . date('F d, Y'), 0, 1, 'C', 0, '', 0);

            $pdf->ln(20);
            $pdf->Cell(0, 0, 'Sorry! No data available.', 0, 1, 'C', 0, '', 0);
            $pdf->lastPage();
            //Close and output PDF document
            $user = auth()->user();
            Log::channel('admin')->info("UserId:".$user->user_id." | AdminId: ".$user->admin_id." | Username: ".$user->name." - Generated an Tracer Alumni Profile Report");
            $pdf->Output(date('m-d-y') . ' Tracer_Profile_Report' . '.pdf', 'I');
        }
    }

    public function tracerBoardPassers($request) {
        $allAlumni = Alumni::whereBetween('batch', [$request->batch_from, $request->batch_to])->get();

        $ctr = 0;
        foreach ($allAlumni as $alumni) {
            $checkAlumni = TracerAnswers::where('alumni_id', '=', $alumni->alumni_id)->get();
            foreach ($checkAlumni as $check) {
                if ($check->question_id == 1) {
                    if ($check->answer == 'Yes') {
                        $ctr++;
                    }
                }
            }
        }

        if ($ctr > 0) {
            $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

            // set document information
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetTitle('Tracer Reports');

            // set default monospaced font
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

            // set margins
            $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
            $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

            // set auto page breaks
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

            // set image scale factor
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
            $board_exam = ['Electronics Engineer Licensure Examination', 'Licensure Examination for Teachers', 'Certified Public Accountant Board Exam', 'Professional Mechanical Engineer'];

            foreach ($board_exam as $exam) {

                $checkBoardPassers = Alumni::join('tbl_tracer_answers', 'tbl_alumni.alumni_id', '=', 'tbl_tracer_answers.alumni_id')
                    ->whereBetween('tbl_alumni.batch', [$request->batch_from, $request->batch_to])
                    ->where('tbl_tracer_answers.question_id', '=', 3)
                    ->where('tbl_tracer_answers.answer', '=', $exam)
                    ->select('tbl_tracer_answers.answer',)
                    ->get();

                if (count($checkBoardPassers) > 0) {
                    $pdf->SetPrintHeader(true);
                    $pdf->AddPage();
                    $pdf->SetPrintHeader(false);
                    $pdf->SetFont('times', 'B', 13);
                    $pdf->ln(20);
                    $pdf->Cell(0, 0, 'ALUMNI TRACER - LIST OF BOARD PASSERS', 0, 1, 'C', 0, '', 0);
                    $pdf->SetFont('times', 'B', 12);
                    $pdf->ln(1);
                    $pdf->Cell(0, 0, strtoupper($exam), 0, 1, 'C', 0, '', 0);
                    $pdf->SetFont('times', '', 12);
                    $pdf->ln(1);
                    $pdf->Cell(0, 0, 'ALUMNI BATCH FROM ' . $request->batch_from . ' TO ' . $request->batch_to, 0, 1, 'C', 0, '', 0);

                    $pdf->ln(15);
                    $pdf->SetFont('times', '', 11);
                    date_default_timezone_set('Asia/Manila');
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
                        td {
                            text-align: center;
                        }
                        th {
                            font-weight: bold;
                        }
                        table {
                            padding: 5px;
                        }
                    </style>
                    <table class="table-EI" style="width:100%;">
                        <tr class="theading" style="text-align: center; font-weight: bold;">
                            <th class="" colspan="1" style="width: 60%;">Name</th>
                            <td class="" colspan="1" style="width: 30%;">Course</td>
                            <td class="" colspan="1" style="width: 10%;">Batch</td>
                        </tr>';

                    foreach ($allAlumni as $alumni) {
                        $checkAnswer = TracerAnswers::where('alumni_id', '=', $alumni->alumni_id)->get();

                        if (count($checkAnswer) > 0 && $checkAnswer[0]['answer'] == "Yes" && $checkAnswer[2]['answer'] == $exam) {
                            $fullname = $alumni->last_name . ', ' . $alumni->first_name . ' ' . $alumni->suffix . ' ' . $alumni->middle_name;

                            $html .= '<tr>
                                <th class="th-EI" colspan="1" style="width: 60%;">' . strtoupper($fullname) . '</th>
                                <td class="th-EI" colspan="1" style="width: 30%;">' . $alumni->course_id . '</td>
                                <td class="th-EI" colspan="1" style="width: 10%;">' . $alumni->batch . '</td>
                            </tr>';
                        }
                    }
                    $html .= '</table>';
                    $pdf->writeHTML($html, true, 0, true, 0);
                }
            }
            $pdf->lastPage();
            $user = auth()->user();
            Log::channel('admin')->info("UserId:".$user->user_id." | AdminId: ".$user->admin_id." | Username: ".$user->name." - Generated an Tracer Board Passers Report");
            $pdf->Output(date('m-d-y') . ' Tracer_Summary_Report' . '.pdf', 'I');
        } else {
            $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

            // set document information
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetTitle('Alumni Reports');

            // set default monospaced font
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

            // set margins
            $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
            $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

            // set auto page breaks
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

            // set image scale factor
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

            $pdf->SetPrintHeader(true);
            $pdf->AddPage();
            $pdf->SetPrintHeader(false);
            $pdf->SetFont('times', 'B', 13);
            $pdf->ln(20);
            $pdf->Cell(0, 0, 'ALUMNI TRACER - PASSERS REPORT', 0, 1, 'C', 0, '', 0);
            $pdf->SetFont('times', '', 12);
            $pdf->ln(1);
            $pdf->Cell(0, 0, 'ALUMNI BATCH FROM ' . $request->batch_from . ' TO ' . $request->batch_to, 0, 1, 'C', 0, '', 0);
            date_default_timezone_set('Asia/Manila');
            $pdf->Cell(0, 0, 'Date Generated: ' . date('F d, Y'), 0, 1, 'C', 0, '', 0);

            $pdf->ln(20);
            $pdf->Cell(0, 0, 'Sorry! No data available.', 0, 1, 'C', 0, '', 0);
            $pdf->lastPage();
            //Close and output PDF document
            $user = auth()->user();
            Log::channel('admin')->info("UserId:".$user->user_id." | AdminId: ".$user->admin_id." | Username: ".$user->name." - Generated an Tracer Board Passers Report");
            $pdf->Output(date('m-d-y') . ' Tracer_Passers_Report' . '.pdf', 'I');
        }
    }
}