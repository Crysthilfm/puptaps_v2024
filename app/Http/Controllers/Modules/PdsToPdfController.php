<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\Courses;
use App\Models\Forms\Pds\PdsAnswers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        // $this->Line(10, 288, 200, 288, $style3);
        $this->Line(10, 320, 200, 320, $style3);
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

class PdsToPdfController extends Controller
{
    public function PDS_to_PDF(Request $request) {
        $users = Alumni::where('alumni_id', '=', $request->alumni_id)->get();

        $fathers_name = PdsAnswers::where([['alumni_id', '=', $request->alumni_id],['question_id', '=', 2]])->value('answer');
        $fathers_number = PdsAnswers::where([['alumni_id', '=', $request->alumni_id],['question_id', '=', 3]])->value('answer');
        $mothers_name = PdsAnswers::where([['alumni_id', '=', $request->alumni_id],['question_id', '=', 4]])->value('answer');
        $mothers_number = PdsAnswers::where([['alumni_id', '=', $request->alumni_id],['question_id', '=', 5]])->value('answer');

        $department = PdsAnswers::where([['alumni_id', '=', $request->alumni_id],['question_id', '=', 6]])->value('answer');
        $position = PdsAnswers::where([['alumni_id', '=', $request->alumni_id],['question_id', '=', 7]])->value('answer');
        $job_date = PdsAnswers::where([['alumni_id', '=', $request->alumni_id],['question_id', '=', 8]])->value('answer');

        $sem_1 = PdsAnswers::where([['alumni_id', '=', $request->alumni_id],['question_id', '=', 9]])->value('answer');
        $sem_date_1 = PdsAnswers::where([['alumni_id', '=', $request->alumni_id],['question_id', '=', 10]])->value('answer');

        $sem_2 = PdsAnswers::where([['alumni_id', '=', $request->alumni_id],['question_id', '=', 11]])->value('answer');
        $sem_date_2 = PdsAnswers::where([['alumni_id', '=', $request->alumni_id],['question_id', '=', 12]])->value('answer');

        $sem_3 = PdsAnswers::where([['alumni_id', '=', $request->alumni_id],['question_id', '=', 13]])->value('answer');
        $sem_date_3 = PdsAnswers::where([['alumni_id', '=', $request->alumni_id],['question_id', '=', 14]])->value('answer');

        $signature_date = date('F d, Y', strtotime(PdsAnswers::where([['alumni_id', '=', $request->alumni_id],['question_id', '=', 15]])->value('answer')));
        $signature = strtoupper(PdsAnswers::where([['alumni_id', '=', $request->alumni_id],['question_id', '=', 16]])->value('answer'));

        foreach($users as $user)
        {
            // $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf = new MYPDF('P', 'mm', array(215.9, 330.2), true, 'UTF-8', false);

            // set document information
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetTitle('PDS_' . $user->stud_number);

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
            $pdf->AddPage();
            $pdf->SetFont('times', 'B', 13);
            $pdf->ln(15);
            $pdf->Cell(0, 0, 'PERSONAL DATA SHEET', 0, 1, 'C', 0, '', 0);

            $pdf->ln(8);
            $pdf->SetFont('times', '', 12);
            $html = <<<EOF
              <table style="width:100%">
                <tr>
                    <th colspan="1" style="width: 8%; font-weight: bold;">Name: </th>
                    <td colspan="1" style="text-align: center; width: 30%">$user->last_name </td>
                    <td colspan="1" style="text-align: center; width: 32%">$user->first_name $user->suffix </td>
                    <td colspan="1" style="text-align: center; width: 30%">$user->middle_name </td>
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
            $bday = date('F d, Y', strtotime($user->birthday));
            $html = <<<EOF
              <table style="width:100%; margin-top: 300px;">
                <tr>
                    <th colspan="1" style="width: 16%; font-weight: bold;">Date of Birth: </th>
                    <td colspan="1" style="border-bottom: 1px solid black; width: 25%;"> $bday </td>
                    <td colspan="1" style="width: 10%;"></td>
                    <th colspan="1" style="width: 6%; font-weight: bold;">Age: </th>
                    <td colspan="1" style="border-bottom: 1px solid black; width: 17%;"> $user->age years old</td>
                </tr>
              </table>
            EOF;
            $pdf->writeHTML($html, true, 0, true, 0);

            $pdf->Ln(-3);
            $bday = date('F d, Y', strtotime($user->birthday));
            $html = <<<EOF
              <table style="width:100%; margin-top: 300px;">
                <tr>
                    <th colspan="1" style="width: 11%; font-weight: bold;">Religion: </th>
                    <td colspan="1" style="border-bottom: 1px solid black; width: 30%;"> $user->religion </td>
                    <td colspan="1" style="width: 10%;"></td>
                    <th colspan="1" style="width: 6%; font-weight: bold;">Sex: </th>
                    <td colspan="1" style="border-bottom: 1px solid black; width: 17%;"> $user->sex </td>
                </tr>
              </table>
            EOF;
            $pdf->writeHTML($html, true, 0, true, 0);

            $pdf->Ln(-3);
            $course = Courses::where('course_id', '=', $user->course_id)->value('course_desc');
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
                    <td colspan="1" style="border-bottom: 1px solid black; width: 10%;"> $user->batch </td>
                </tr>
              </table>
            EOF;
            $pdf->writeHTML($html, true, 0, true, 0);

            $pdf->Ln(-3);
            $html = <<<EOF
                <table style="width:100%; margin-top: 10px;">
                  <tr>
                      <th colspan="1" style="width: 16%; font-weight: bold;">City Address: </th>
                      <td colspan="1" style="border-bottom: 1px solid black; width: 84%;"> $user->city_address </td>
                  </tr>
                </table>
            EOF;
            $pdf->writeHTML($html, true, 0, true, 0);

            $pdf->Ln(-3);
            $html = <<<EOF
                <table style="width:100%; margin-top: 10px;">
                  <tr>
                      <th colspan="1" style="width: 22%; font-weight: bold;">Provincial Address: </th>
                      <td colspan="1" style="border-bottom: 1px solid black; width: 78%;"> $user->provincial_address </td>
                  </tr>
                </table>
            EOF;
            $pdf->writeHTML($html, true, 0, true, 0);

            $pdf->Ln(-3);
            $html = <<<EOF
                <table style="width:100%; margin-top: 10px;">
                    <tr>
                        <th colspan="1" style="width: 17%; font-weight: bold;">Father's Name: </th>
                        <td colspan="1" style="border-bottom: 1px solid black; width: 39%;">$fathers_name</td>
                        <td colspan="1" style="width: 6%;"></td>
                        <th colspan="1" style="width: 15%; font-weight: bold;">Father's No.: </th>
                        <td colspan="1" style="border-bottom: 1px solid black; width: 23%;">$fathers_number</td>
                    </tr>
                </table>
            EOF;
            $pdf->writeHTML($html, true, 0, true, 0);

            $pdf->Ln(-3);
            $html = <<<EOF
                <table style="width:100%; margin-top: 10px;">
                    <tr>
                        <th colspan="1" style="width: 18%; font-weight: bold;">Mother's Name: </th>
                        <td colspan="1" style="border-bottom: 1px solid black; width: 38%;">$mothers_name</td>
                        <td colspan="1" style="width: 6%;"></td>
                        <th colspan="1" style="width: 16%; font-weight: bold;">Mother's No.: </th>
                        <td colspan="1" style="border-bottom: 1px solid black; width: 22%;">$mothers_number</td>
                    </tr>
                </table>
            EOF;
            $pdf->writeHTML($html, true, 0, true, 0);

            $pdf->SetPrintHeader(false);
            $pdf->Ln(3);
            $html = <<<EOF
                <style>
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
                <h4 style="text-align: center;">OJT and Work Experience/s:</h4>
                <table class="table-work" style="width:100%;">
                    <tr>
                        <th class="th-work" colspan="1" style="font-weight: bold; text-align: center; width:50%">Department / Agency / Office</th>
                        <th class="th-work" colspan="1" style="font-weight: bold; text-align: center; width: 25%">Position</th>
                        <th class="th-work" colspan="1" style="font-weight: bold; text-align: center; width: 25%">Inclusive Dates</th>
                    </tr>
                    <tr>
                        <td class="td-work">$department</td>
                        <td class="td-work">$position</td>
                        <td class="td-work">$job_date</td>
                    </tr>
                </table>
            EOF;
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
                            <td class="td-work">'. $sem_1 . '</td>
                            <td class="td-work">'. $sem_date_1 . '</td>
                        </tr>';


                    if ($sem_2 != 'N/A' && $sem_date_2 != 'N/A') {
                        $html .= '<tr>
                                <td class="td-work">'. $sem_2 . '</td>
                                <td class="td-work">'. $sem_date_2 . '</td>
                            </tr>';
                    }
                    if ($sem_3 != 'N/A' && $sem_date_3 != 'N/A') {
                        $html .= '<tr>
                                <td class="td-work">'. $sem_3 . '</td>
                                <td class="td-work">'. $sem_date_3 . '</td>
                            </tr>';
                    }
                    $html .= '</table>';
                $pdf->writeHTML($html, true, 0, true, 0);

            $pdf->Ln(-15);
            $html = <<<EOF
                <div>
                    <p style="text-align: justify; text-justify: inter-word; line-height: 1.5;">I hereby certify that all information provided are true and correct to the best of my knowledge.
                    </p>
                    <table style="width:100%;">
                        <tr>
                            <td colspan="1" style="width: 20%; text-align: center; text-transform: uppercase;"></td>
                            <td style="width: 25%"></td>
                            <td colspan="1" style="width: 55%; text-align: center; text-transform: uppercase;">$signature</td>
                        </tr>
                        <tr>
                            <td colspan="1" style="width: 20%; text-align: center;">

                            </td>
                            <td style="width: 25%"></td>
                            <td colspan="1" style="font-size:14px; width: 55%; text-align: center; border-top: 1px solid black;">
                                <i>Signature</i>
                            </td>
                        </tr>
                    </table>
                </div>
            EOF;
            $pdf->writeHTML($html, true, 0, true, 0);

            $pdf->Ln(-5);
            $html = <<<EOF
                <div>
                    <h4 style="text-align: center; text-decoration: underline;">WAIVER</h4>
                    <p style="text-align: justify; text-justify: inter-word; line-height: 1.5;">This is to signify that I am willing to be subjected to company calls for placement or employment purposes. This shall also authorize the Polytechnic University of The Philippines – Career Development and Placement Office (PUP-CDPO) to include my name and contact details in the directory of graduates.
                    </p>
                    <table style="width:100%;">
                        <tr>
                            <td colspan="1" style="width: 25%; text-align: center; text-transform: uppercase;">$signature_date</td>
                            <td style="width: 20%"></td>
                            <td colspan="1" style="width: 55%; text-align: center; text-transform: uppercase;">$signature</td>
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
                </div>
            EOF;
            $pdf->writeHTML($html, true, 0, true, 0);

            //Close and output PDF document
            $pdf->Output('PDS_' . $user->stud_number . '.pdf', 'I');
        }

    }
}
