<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\Courses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use DB;
use TCPDF;

class MYPDF extends TCPDF {

    //Page header
    public function Header() {
        // Logo
        $image_file = public_path('/img/pupLogo.png');
        // $image_file = asset('img/pupLogo.png');
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
        $this->Line(10, 33, 288, 33, $style3);
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
        $this->Line(10, 288, 200, 288, $style3);
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

class UserReportToPdfController extends Controller
{
    public function getUserReportPdf(Request $request) {
        
        $courses = Courses::all();
        
        if ($request->course_id == null && $request->sex == null) {
            $getAlumni = Alumni::whereBetween('batch', [$request->batch_from, $request->batch_to])
                        ->orderBy('last_name', 'asc')
                        ->get();
        }
        if ($request->course_id != null && $request->sex != null) {
            $getAlumni = Alumni::where('course_id', 'like', '%' . $request->course_id . '%')
                        ->where('sex', '=', $request->sex)
                        ->whereBetween('batch', [$request->batch_from, $request->batch_to])
                        ->orderBy('last_name', 'asc')
                        ->get();
        }
        elseif ($request->course_id == null & $request->sex != null) {
            $getAlumni = Alumni::where('sex', '=', $request->sex)
                        ->whereBetween('batch', [$request->batch_from, $request->batch_to])
                        ->orderBy('last_name', 'asc')
                        ->get();
        }
        elseif ($request->course_id != null & $request->sex == null) {
            $getAlumni = Alumni::where('course_id', 'like', '%' . $request->course_id . '%')
                        ->whereBetween('batch', [$request->batch_from, $request->batch_to])
                        ->orderBy('last_name', 'asc')
                        ->get();
        }
        $batch_to = $request->batch_to;

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
        // ---------------------------------------------------------

        // set font
        // add a page

        foreach ($courses as $course) {
        
            if ($request->course_id == null) {
                if ($request->sex == null) {
                    for ($batch_from = $request->batch_from ; $batch_from <= $batch_to; $batch_from++) {
                        $alumniCount = Alumni::where('batch', '=', $batch_from)->where('course_id', '=', $course->course_id)->get();
                        if (count($alumniCount) > 0) {
                            $pdf->SetPrintHeader(true);
                            $pdf->AddPage('L');
                            $pdf->SetPrintHeader(false);
                            $pdf->SetFont('times', 'B', 13);
                            $pdf->ln(17);
                            $pdf->Cell(0, 0, 'MASTER LIST', 0, 1, 'C', 0, '', 0);

                            $pdf->ln(5);
                            $pdf->SetFont('times', 'B', 13);
                            // $pdf->Cell(0, 0, strtoupper($course->course_desc), 0, 1, 'C', 0, '', 0);
                            $pdf->MultiCell(270, 5, strtoupper($course->course_desc), 0, 'C', 0, 1, '', '', true);
                            $pdf->SetFont('times', '', 11);
                            $pdf->Cell(0, 0, strtoupper('Alumni Batch of ' . $batch_from), 0, 1, 'C', 0, '', 0);
                            $pdf->ln(5);
                            date_default_timezone_set('Asia/Manila');
                            $pdf->Cell(0, 0, 'Date Generated: ' . date('F d, Y'), 0, 1, 'R', 0, '', 0);

                            $pdf->ln(0);
                            $html = '<style>
                                        .table-EI, .th-EI, .td-EI {
                                            border: 1px solid black;
                                            border-collapse: collapse;
                                            padding: 5px 8px;
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
                                            <td class="tdHeading" colspan="1" style="width: 27%;">Name</td>
                                            <td class="tdHeading" colspan="1" style="width: 20%;">Email</td>
                                            <td class="tdHeading" colspan="1" style="width: 13%;">Number</td>
                                            <td class="tdHeading" colspan="1" style="width: 10%;">Birthday</td>
                                            <td class="tdHeading" colspan="1" style="width: 30%;">Address</td>
                                        </tr>';
                            foreach ($getAlumni as $alumni) {
                                if ($course->course_id == $alumni->course_id && $batch_from == $alumni->batch && $alumni->email != null && $alumni->number != null && $alumni->city_address != null) {
                                    $html .= '<tr>
                                                <td class="th-EI" colspan="1" style="width: 27%; font-weight: bold;">' . strtoupper($alumni->last_name) . ', ' . strtoupper($alumni->first_name) . ' ' .strtoupper($alumni->suffix) . ' ' . strtoupper($alumni->middle_name) . '</td>
                                                <td class="th-EI" colspan="1" style="width: 20%;">' . $alumni->email . '</td>
                                                <td class="th-EI" colspan="1" style="width: 13%; text-align: center;">' . $alumni->number . '</td>
                                                <td class="th-EI" colspan="1" style="width: 10%; text-align: center;">';
                                                if ($alumni->birthday != '0000-00-00') {
                                                    $html .= date('m/d/Y' , strtotime($alumni->birthday));
                                                }
                                                $html .= '</td>
                                                <td class="th-EI" colspan="1" style="width: 30%;">' . $alumni->city_address . '</td>
                                            </tr>';
                                }
                            }
                            $html .= '</table>';

                            $pdf->writeHTML($html, true, 0, true, 0);
                            $pdf->lastPage();
                        }
                    }
                }
                else {
                    for ($batch_from = $request->batch_from ; $batch_from <= $batch_to; $batch_from++) {
                        $alumniCount = Alumni::where('batch', '=', $batch_from)->where('course_id', '=', $course->course_id)->get();
                        if (count($alumniCount) > 0) {
                            $pdf->SetPrintHeader(true);
                            $pdf->AddPage();
                            $pdf->SetPrintHeader(false);
                            $pdf->SetFont('times', 'B', 13);
                            $pdf->ln(17);
                            $pdf->Cell(0, 0, 'MASTER LIST', 0, 1, 'C', 0, '', 0);

                            $pdf->ln(5);
                            $pdf->SetFont('times', 'B', 13);
                            $pdf->MultiCell(180, 5, strtoupper($course->course_desc), 0, 'C', 0, 1, '', '', true);
                            $pdf->SetFont('times', '', 11);
                            $pdf->Cell(0, 0, strtoupper('Alumni Batch of ' . $batch_from . ' - ' . $request->sex), 0, 1, 'C', 0, '', 0);
                            $pdf->ln(5);
                            date_default_timezone_set('Asia/Manila');
                            $pdf->Cell(0, 0, 'Date Generated: ' . date('F d, Y'), 0, 1, 'R', 0, '', 0);

                            $pdf->ln(0);
                            $html = '<style>
                                        .table-EI, .th-EI, .td-EI {
                                            border: 1px solid black;
                                            border-collapse: collapse;
                                        }
                                        .theading {
                                            background-color: #78281F;
                                            color: #ffffff;
                                        }
                                        th {
                                            font-weight: bold;
                                        }
                                        table {
                                            padding: 5px;
                                        }
                                        tr:nth-child(even) {
                                            background-color: #f2f2f2;
                                        }
                                    </style>
                                    <table class="table-EI" style="width:100%;">
                                        <tr class="theading" style="text-align: center; font-weight: bold;">
                                            <td class="tdHeading" colspan="1" style="width: 25%;">Name</td>
                                            <td class="tdHeading" colspan="1" style="width: 20%;">Email</td>
                                            <td class="tdHeading" colspan="1" style="width: 17%;">Number</td>
                                            <td class="tdHeading" colspan="1" style="width: 13%;">Birthday</td>
                                            <td class="tdHeading" colspan="1" style="width: 25%;">Address</td>
                                        </tr>';
                            foreach ($getAlumni as $alumni) {
                                if ($course->course_id == $alumni->course_id && $batch_from == $alumni->batch && $request->sex == $alumni->sex) {
                                    $html .= '<tr>
                                                <td class="th-EI" colspan="1" style="width: 25%;">' . strtoupper($alumni->last_name) . ', ' . strtoupper($alumni->first_name) . ' ' .strtoupper($alumni->suffix) . ' ' . strtoupper($alumni->middle_name) . '</td>
                                                <td class="th-EI" colspan="1" style="width: 20%;">' . $alumni->email . '</td>
                                                <td class="th-EI" colspan="1" style="width: 17%; text-align: center;">' . $alumni->number . '</td>
                                                <td class="th-EI" colspan="1" style="width: 13%; text-align: center;">';
                                                if ($alumni->birthday != '0000-00-00') {
                                                    $html .= date('m/d/Y' , strtotime($alumni->birthday));
                                                }
                                                $html .= '</td>
                                                <td class="th-EI" colspan="1" style="width: 25%;">' . $alumni->city_address . '</td>
                                            </tr>';
                                }
                            }
                            $html .= '</table>';

                            $pdf->writeHTML($html, true, 0, true, 0);
                            $pdf->lastPage();
                        }
                    }
                }
            }
            else {
                if ($request->sex == null) {
                    if ($course->course_id == $request->course_id) {
                        $batch_to = (int)$batch_to;
                        
                        for ($batch_from = (int)$request->batch_from ; $batch_from <= $batch_to; $batch_from++) {
                            $alumniCount = Alumni::where('batch', '=', $batch_from)->where('course_id', '=', $course->course_id)->get();
                            
                            if (count($alumniCount) > 0) {
                                $pdf->SetPrintHeader(true);
                                $pdf->AddPage();
                                $pdf->SetPrintHeader(false);
                                $pdf->SetFont('times', 'B', 13);
                                $pdf->ln(17);
                                $pdf->Cell(0, 0, 'MASTER LIST', 0, 1, 'C', 0, '', 0);

                                $pdf->ln(5);
                                $pdf->SetFont('times', 'B', 13);
                                $pdf->MultiCell(180, 5, strtoupper($course->course_desc), 0, 'C', 0, 1, '', '', true);
                                $pdf->SetFont('times', '', 11);
                                $pdf->Cell(0, 0, strtoupper('Alumni Batch of ' . $batch_from), 0, 1, 'C', 0, '', 0);
                                $pdf->ln(5);
                                date_default_timezone_set('Asia/Manila');
                                $pdf->Cell(0, 0, 'Date Generated: ' . date('F d, Y'), 0, 1, 'R', 0, '', 0);

                                $pdf->ln(0);
                                $html = '<style>
                                            .table-EI, .th-EI, .td-EI {
                                                border: 1px solid black;
                                                border-collapse: collapse;
                                            }
                                            .theading {
                                                background-color: #78281F;
                                                color: #ffffff;
                                            }
                                            th {
                                                font-weight: bold;
                                            }
                                            table {
                                                padding: 5px;
                                            }
                                            tr:nth-child(even) {
                                                background-color: #f2f2f2;
                                            }
                                        </style>
                                        <table class="table-EI" style="width:100%;">
                                            <tr class="theading" style="text-align: center; font-weight: bold;">
                                                <td class="tdHeading" colspan="1" style="width: 25%;">Name</td>
                                                <td class="tdHeading" colspan="1" style="width: 20%;">Email</td>
                                                <td class="tdHeading" colspan="1" style="width: 17%;">Number</td>
                                                <td class="tdHeading" colspan="1" style="width: 13%;">Birthday</td>
                                                <td class="tdHeading" colspan="1" style="width: 25%;">Address</td>
                                            </tr>';
                                foreach ($getAlumni as $alumni) {
                                    if ($batch_from == $alumni->batch) {
                                        $html .= '<tr>
                                                    <td class="th-EI" colspan="1" style="width: 25%;">' . strtoupper($alumni->last_name) . ', ' . strtoupper($alumni->first_name) . ' ' .strtoupper($alumni->suffix) . ' ' . strtoupper($alumni->middle_name) . '</td>
                                                    <td class="th-EI" colspan="1" style="width: 20%;">' . $alumni->email . '</td>
                                                    <td class="th-EI" colspan="1" style="width: 17%; text-align: center;">' . $alumni->number . '</td>
                                                    <td class="th-EI" colspan="1" style="width: 13%; text-align: center;">';
                                                    if ($alumni->birthday != '0000-00-00') {
                                                        $html .= date('m/d/Y' , strtotime($alumni->birthday));
                                                    }
                                                    $html .= '</td>
                                                    <td class="th-EI" colspan="1" style="width: 25%;">' . $alumni->city_address . '</td>
                                                </tr>';
                                    }
                                }
                                $html .= '</table>';
                                
                                $pdf->writeHTML($html, true, 0, true, 0);
                                $pdf->lastPage();
                            }
                            
                        }
                    }
                }
                else {
                    if ($course->course_id == $request->course_id) {
                        for ($batch_from = $request->batch_from ; $batch_from <= $batch_to; $batch_from++) {
                            $alumniCount = Alumni::where('batch', '=', $batch_from)->where('course_id', '=', $course->course_id)->get();
                            if (count($alumniCount) > 0) {
                                $pdf->SetPrintHeader(true);
                                $pdf->AddPage();
                                $pdf->SetPrintHeader(false);
                                $pdf->SetFont('times', 'B', 13);
                                $pdf->ln(17);
                                $pdf->Cell(0, 0, 'MASTER LIST', 0, 1, 'C', 0, '', 0);

                                $pdf->ln(5);
                                $pdf->SetFont('times', 'B', 13);
                                $pdf->MultiCell(180, 5, strtoupper($course->course_desc), 0, 'C', 0, 1, '', '', true);
                                $pdf->SetFont('times', '', 11);
                                $pdf->Cell(0, 0, strtoupper('Alumni Batch of ' . $batch_from . ' - ' . $request->sex), 0, 1, 'C', 0, '', 0);
                                $pdf->ln(5);
                                date_default_timezone_set('Asia/Manila');
                                $pdf->Cell(0, 0, 'Date Generated: ' . date('F d, Y'), 0, 1, 'R', 0, '', 0);

                                $pdf->ln(0);
                                $html = '<style>
                                            .table-EI, .th-EI, .td-EI {
                                                border: 1px solid black;
                                                border-collapse: collapse;
                                            }
                                            .theading {
                                                background-color: #78281F;
                                                color: #ffffff;
                                            }
                                            th {
                                                font-weight: bold;
                                            }
                                            table {
                                                padding: 5px;
                                            }
                                            tr:nth-child(even) {
                                                background-color: #f2f2f2;
                                            }
                                        </style>
                                        <table class="table-EI" style="width:100%;">
                                            <tr class="theading" style="text-align: center; font-weight: bold;">
                                                <td class="tdHeading" colspan="1" style="width: 25%;">Name</td>
                                                <td class="tdHeading" colspan="1" style="width: 20%;">Email</td>
                                                <td class="tdHeading" colspan="1" style="width: 17%;">Number</td>
                                                <td class="tdHeading" colspan="1" style="width: 13%;">Birthday</td>
                                                <td class="tdHeading" colspan="1" style="width: 25%;">Address</td>
                                            </tr>';
                                foreach ($getAlumni as $alumni) {
                                    if ($course->course_id == $request->course_id && $batch_from == $alumni->batch && $request->sex == $alumni->sex) {
                                        $html .= '<tr>
                                                    <td class="th-EI" colspan="1" style="width: 25%;">' . strtoupper($alumni->last_name) . ', ' . strtoupper($alumni->first_name) . ' ' .strtoupper($alumni->suffix) . ' ' . strtoupper($alumni->middle_name) . '</td>
                                                    <td class="th-EI" colspan="1" style="width: 20%;">' . $alumni->email . '</td>
                                                    <td class="th-EI" colspan="1" style="width: 17%; text-align: center;">' . $alumni->number . '</td>
                                                    <td class="th-EI" colspan="1" style="width: 13%; text-align: center;">';
                                                    if ($alumni->birthday != '0000-00-00') {
                                                        $html .= date('m/d/Y' , strtotime($alumni->birthday));
                                                    }
                                                    $html .= '</td>
                                                    <td class="th-EI" colspan="1" style="width: 25%;">' . $alumni->city_address . '</td>
                                                </tr>';
                                    }
                                }
                                $html .= '</table>';

                                $pdf->writeHTML($html, true, 0, true, 0);
                                $pdf->lastPage();
                            }
                        }
                    }
                }
            }
        }

        $user = auth()->user();
        Log::channel('admin')->info("UserId:".$user->user_id." | AdminId: ".$user->admin_id." | Username: ".$user->name." - Generated an Alumni report");
        $pdf->Output(date('m-d-y') . ' Alumni_Report' . '.pdf', 'I');
    }
}
