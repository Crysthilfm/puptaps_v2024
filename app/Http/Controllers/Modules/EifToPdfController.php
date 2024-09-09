<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\Courses;
use App\Models\Forms\Eif\EifAnswers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
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
        $this->Line(10, 288, 200, 288, $style3);
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

class EifToPdfController extends Controller
{
    public function EIF_TO_PDF(Request $request) {
        $users = Alumni::where('alumni_id', '=', $request->alumni_id)->get();

        $employment = EifAnswers::where([['alumni_id', '=', $request->alumni_id],['question_id', '=', 2]])->value('answer');

        $overalls = EifAnswers::whereIn('question_id', [4, 5, 6, 7, 8, 9, 10])->where('alumni_id', '=', $request->alumni_id)->get('answer');
        $arrayOverall = [];
        foreach($overalls as $overall) {
            array_push($arrayOverall, $overall->answer);
        }

        $offices = EifAnswers::whereIn('question_id', [11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46])->where('alumni_id', '=', $request->alumni_id)->get('answer');
        $arrayOffices = [];
        foreach($offices as $office) {
            array_push($arrayOffices, $office->answer);
        }

        $PupOveralls = EifAnswers::whereIn('question_id', [47, 48, 49])->where('alumni_id', '=', $request->alumni_id)->get('answer');
        $arrayPupOverall = [];
        foreach($PupOveralls as $PupOverall) {
            array_push($arrayPupOverall, $PupOverall->answer);
        }

        $comment = EifAnswers::where([['alumni_id', '=', $request->alumni_id],['question_id', '=', 50]])->value('answer');

        $signature = strtoupper(EifAnswers::where([['alumni_id', '=', $request->alumni_id],['question_id', '=', 52]])->value('answer'));

        foreach($users as $user)
        {
            $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

            // set document information
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetTitle('EIF_' . $user->stud_number);

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
            $pdf->SetPrintHeader(false);
            $pdf->SetFont('times', 'B', 13);
            $pdf->ln(20);
            $pdf->Cell(0, 0, 'EXIT INTERVIEW FORM', 0, 1, 'C', 0, '', 0);

            $pdf->ln(12);
            $pdf->SetFont('times', '', 12);
            $html = <<<EOF
              <p><u><b>PERSONAL INFORMATION</b></u></p>
            EOF;
            $pdf->writeHTML($html, true, 0, true, 0);

            $pdf->ln(5);
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
                </tr>
              </table>
            EOF;
            $pdf->writeHTML($html, true, 0, true, 0);

            $pdf->Ln(-3);
            $html = <<<EOF
              <table style="width:100%; margin-top: 300px;">
                <tr>
                    <th colspan="1" style="width: 6%; font-weight: bold;">Sex: </th>
                    <td colspan="1" style="border-bottom: 1px solid black; width: 13%;"> $user->sex </td>
                    <td colspan="1" style="width: 12%;"></td>
                    <th colspan="1" style="width: 6%; font-weight: bold;">Age: </th>
                    <td colspan="1" style="border-bottom: 1px solid black; width: 17%;"> $user->age years old</td>
                    <td colspan="1" style="width: 12%;"></td>
                    <th colspan="1" style="width: 14%; font-weight: bold;">Civil Status: </th>
                    <td colspan="1" style="border-bottom: 1px solid black; width: 20%;"> $user->civil_status </td>
                </tr>
              </table>
            EOF;
            $pdf->writeHTML($html, true, 0, true, 0);

            $pdf->Ln(-1);
            $html = <<<EOF
              <table style="width:100%; margin-top: 10px;">
                <tr>
                    <th colspan="1" style="width: 8%; font-weight: bold;">Email: </th>
                    <td colspan="1" style="border-bottom: 1px solid black; width: 45%;"> $user->email </td>
                    <td style="width: 8%;"></td>
                    <th colspan="1" style="width: 19%; font-weight: bold;">Contact Number: </th>
                    <td colspan="1" style="border-bottom: 1px solid black; width: 20%;"> $user->number </td>
                </tr>
              </table>
            EOF;
            $pdf->writeHTML($html, true, 0, true, 0);

            $pdf->Ln(-1);
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

            $pdf->Ln(-1);
            $html = <<<EOF
              <table style="width:100%; margin-top: 10px;">
                <tr>
                    <th colspan="1" style="width: 19%; font-weight: bold;">Student Number: </th>
                    <td colspan="1" style="border-bottom: 1px solid black; width: 25%;"> $user->stud_number </td>
                </tr>
              </table>
            EOF;
            $pdf->writeHTML($html, true, 0, true, 0);

            $pdf->Ln(-1);
            $html = <<<EOF
                <table style="width:100%; margin-top: 10px;">
                  <tr>
                      <th colspan="1" style="width: 16%; font-weight: bold;">City Address: </th>
                      <td colspan="1" style="border-bottom: 1px solid black; width: 84%;"> $user->city_address </td>
                  </tr>
                </table>
            EOF;
            $pdf->writeHTML($html, true, 0, true, 0);

            $pdf->ln(-1);
            $html = <<<EOF
              <table style="width:100%">
                <tr>
                    <th colspan="1" style="width: 15%; font-weight: bold;">If Employed: </th>
                    <td colspan="3" style="text-align: center; width: 85%"> $employment </td>
                </tr>
                <tr>
                    <td></td>
                    <td style="text-align: center; font-size:12px; border-top: 1px solid black;"><i>Position</i></td>
                    <td style="text-align: center; font-size:12px; border-top: 1px solid black;"><i>Company/Company Address</i></td>
                    <td style="text-align: center; font-size:12px; border-top: 1px solid black;"><i>Telephone Number</i></td>
                </tr>
              </table>
            EOF;
            $pdf->writeHTML($html, true, 0, true, 0);

            $pdf->ln(12);
            $pdf->SetFont('times', '', 12);
            $html = <<<EOF
              <p><u><b>EXIT SURVEY</b></u></p>
            EOF;
            $pdf->writeHTML($html, true, 0, true, 0);

            $pdf->ln(-10);
            $pdf->SetFont('times', '', 12);
            $data = [
                'reason' => EifAnswers::where([['alumni_id', '=', $request->alumni_id],['question_id', '=', 3]])->value('answer'),
            ];
            $view = View::make('pdf.eif_form', $data);
            $html = $view->render();
            $pdf->writeHTML($html, true, 0, true, 0);

            $pdf->ln(2);
            $html = <<<EOF
                <style>
                    .table-EI, .th-EI, .td-EI {
                        border: 1px solid black;
                        border-collapse: collapse;
                        padding: 7px;
                    }
                    th {
                        font-weight: bold;
                    }
                    td {
                        text-align: center;
                    }
                </style>
                <p><b>2.</b>  Please rate the following items by writing your choice number on the box provided:</p>
                <div style="text-align: center; font-weight: bold;">
                    <span>5 - Outstanding</span>
                    <span>  4 - Very Satisfactory </span>
                    <span>  3 - Satisfactory</span>
                    <span>  2 - Fair</span>
                    <span>  1 - Poor</span>
                </div>
                <br>
                <table class="table-EI" style="width:100%;">
                    <tr>
                        <td class="th-EI" colspan="1" style="width: 80%"></td>
                        <th class="th-EI" colspan="1" style="width: 20%; text-align:center;">Ratings</th>
                    </tr>
                    <tr>
                        <th class="td-EI">Academic Standard</th>
                        <td class="td-EI"> $arrayOverall[0] </td>
                    </tr>
                    <tr>
                        <th class="td-EI">School Activities</th>
                        <td class="td-EI"> $arrayOverall[1] </td>
                    </tr>
                    <tr>
                        <th class="td-EI">Faculty/Teacher</th>
                        <td class="td-EI"> $arrayOverall[2] </td>
                    </tr>
                    <tr>
                        <th class="td-EI">Facilities</th>
                        <td class="td-EI"> $arrayOverall[3] </td>
                    </tr>
                    <tr>
                        <th class="td-EI">Course Taken</th>
                        <td class="td-EI"> $arrayOverall[4] </td>
                    </tr>
                    <tr>
                        <th class="td-EI">Student Organization/s</th>
                        <td class="td-EI"> $arrayOverall[5] </td>
                    </tr>
                    <tr>
                        <th class="td-EI">Classmates</th>
                        <td class="td-EI"> $arrayOverall[6] </td>
                    </tr>
                </table>
            EOF;
            $pdf->writeHTML($html, true, 0, true, 0);

            $pdf->ln(5);
            $html = <<<EOF
                <style>
                    .table-EI, .th-EI, .td-EI {
                        border: 1px solid black;
                        border-collapse: collapse;
                        padding: 8px;
                    }
                    th {
                        font-weight: bold;
                    }
                    td {
                        text-align: center;
                    }
                </style>
                <p><b>3.</b> Please rate the services (quality and timeliness of service, and courtesy of staff) provided to you by the following offices (please rate by writing the  number corresponding your rating):</p>
                <div style="text-align: center; font-weight: bold;">
                    <span>5 - Outstanding</span>
                    <span>  4 - Very Satisfactory </span>
                    <span>  3 - Satisfactory</span>
                    <span>  2 - Fair</span>
                    <span>  1 - Poor</span>
                </div>
                <br>
                <table class="table-EI" style="width:100%;">
                    <tr>
                        <th class="th-EI" colspan="1" style="width: 49%; text-align:center;">PUP Taguig Offices</th>
                        <th class="th-EI" colspan="1" style="width: 17%; text-align:center;">Quality of Service</th>
                        <th class="th-EI" colspan="1" style="width: 17%; text-align:center;">Timeliness of Service</th>
                        <th class="th-EI" colspan="1" style="width: 17%; text-align:center;">Courtesy of Staff</th>
                    </tr>
                    <tr>
                        <th class="td-EI" >Director’s Office</th>
                        <td class="td-EI"> $arrayOffices[0] </td>
                        <td class="td-EI"> $arrayOffices[1] </td>
                        <td class="td-EI"> $arrayOffices[2] </td>
                    </tr>
                    <tr>
                        <th class="td-EI">Office of the Head of Academic Programs</th>
                        <td class="td-EI"> $arrayOffices[3] </td>
                        <td class="td-EI"> $arrayOffices[4] </td>
                        <td class="td-EI"> $arrayOffices[5] </td>
                    </tr>
                    <tr>
                        <th class="td-EI">Administrative Office</th>
                        <td class="td-EI"> $arrayOffices[6] </td>
                        <td class="td-EI"> $arrayOffices[7] </td>
                        <td class="td-EI"> $arrayOffices[8] </td>
                    </tr>
                    <tr>
                        <th class="td-EI">Accounting/Cashier’s Office</th>
                        <td class="td-EI"> $arrayOffices[9] </td>
                        <td class="td-EI"> $arrayOffices[10] </td>
                        <td class="td-EI"> $arrayOffices[11] </td>
                    </tr>
                    <tr>
                        <th class="td-EI">Office of Student Services/Scholarship</th>
                        <td class="td-EI"> $arrayOffices[12] </td>
                        <td class="td-EI"> $arrayOffices[13] </td>
                        <td class="td-EI"> $arrayOffices[14] </td>
                    </tr>
                    <tr>
                        <th class="td-EI">Admission/Registration Office</th>
                        <td class="td-EI"> $arrayOffices[15] </td>
                        <td class="td-EI"> $arrayOffices[16] </td>
                        <td class="td-EI"> $arrayOffices[17] </td>
                    </tr>
                    <tr>
                        <th class="td-EI">Guidance and Counseling Office</th>
                        <td class="td-EI"> $arrayOffices[18] </td>
                        <td class="td-EI"> $arrayOffices[19] </td>
                        <td class="td-EI"> $arrayOffices[20] </td>
                    </tr>
                    <tr>
                        <th class="td-EI">Library Services</th>
                        <td class="td-EI"> $arrayOffices[21] </td>
                        <td class="td-EI"> $arrayOffices[22] </td>
                        <td class="td-EI"> $arrayOffices[23] </td>
                    </tr>
                    <tr>
                        <th class="td-EI">Medical Services</th>
                        <td class="td-EI"> $arrayOffices[24] </td>
                        <td class="td-EI"> $arrayOffices[25] </td>
                        <td class="td-EI"> $arrayOffices[26] </td>
                    </tr>
                    <tr>
                        <th class="td-EI">Dental Services</th>
                        <td class="td-EI"> $arrayOffices[27] </td>
                        <td class="td-EI"> $arrayOffices[28] </td>
                        <td class="td-EI"> $arrayOffices[29] </td>
                    </tr>
                    <tr>
                        <th class="td-EI">Security Office</th>
                        <td class="td-EI"> $arrayOffices[30] </td>
                        <td class="td-EI"> $arrayOffices[31] </td>
                        <td class="td-EI"> $arrayOffices[32] </td>
                    </tr>
                    <tr>
                        <th class="td-EI">Janitorial Service</th>
                        <td class="td-EI"> $arrayOffices[33] </td>
                        <td class="td-EI"> $arrayOffices[34] </td>
                        <td class="td-EI"> $arrayOffices[35] </td>
                    </tr>
                </table>
            EOF;
            $pdf->writeHTML($html, true, 0, true, 0);

            $pdf->ln(5);
            $html = <<<EOF
                <style>
                    .table-EI, .th-EI, .td-EI {
                        border: 1px solid black;
                        border-collapse: collapse;
                        padding: 8px;
                    }
                    th {
                        font-weight: bold;
                    }
                    td {
                        text-align: center;
                    }
                </style>
                <p><b>4.</b> Overall evaluation on the following by writing your choice number on the box provided:</p>
                <div style="text-align: center; font-weight: bold;">
                    <span>5 - Outstanding</span>
                    <span>  4 - Very Satisfactory </span>
                    <span>  3 - Satisfactory</span>
                    <span>  2 - Fair</span>
                    <span>  1 - Poor</span>
                </div>
                <br>
                <table class="table-EI" style="width:100%;">
                    <tr>
                        <th class="th-EI" colspan="1" style="text-align: center; width: 70%">Overall</th>
                        <th class="th-EI" colspan="1" style="text-align: center; width: 30%;">Ratings</th>
                    </tr>
                    <tr>
                        <th class="td-EI"> PUP Taguig Systems and Procedures</th>
                        <td class="td-EI"> $arrayPupOverall[0] </td>
                    </tr>
                    <tr>
                        <th class="td-EI">PUP Taguig Programs / Courses</th>
                        <td class="td-EI"> $arrayPupOverall[1] </td>
                    </tr>
                    <tr>
                        <th class="td-EI">PUP Taguig Services</th>
                        <td class="td-EI"> $arrayPupOverall[2] </td>
                    </tr>
                </table>
            EOF;
            $pdf->writeHTML($html, true, 0, true, 0);

            $pdf->ln(5);
            $html = <<<EOF
                <p><b>5.</b> Give your comments/suggestions/recommendations for the improvement of PUP Taguig.</p>
                <table style="width:100%;">
                    <tr>
                        <td colspan="1" style="word-spacing: 5px; line-height: 25px; text-indent: 50px; text-align: justify;">$comment.</td>
                    </tr>
                </table>
            EOF;
            $pdf->writeHTML($html, true, 0, true, 0);
            $html = <<<EOF
                <p></p>
                <table style="width:100%;">
                    <tr>
                        <td style="width: 45%;"></td>
                        <td colspan="1" style="width: 55%; text-align: center; text-transform: uppercase;">
                            $signature
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 45%;"></td>
                        <td colspan="1" style="font-size: 14px; width: 55%; text-align: center; border-top: 1px solid black;">
                            <i>Signature</i>
                        </td>
                    </tr>
                </table>
            EOF;
            $pdf->writeHTML($html, true, 0, true, 0);

            //Close and output PDF document
            $pdf->Output('EIF_' . $user->stud_number . '.pdf', 'I');
        }
    }
}
