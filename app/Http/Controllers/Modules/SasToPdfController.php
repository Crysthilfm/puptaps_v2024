<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\Courses;
use App\Models\Forms\Sas\SasAnswers;
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
        $this->Line(10, 288, 200, 288, $style3);
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

class SasToPdfController extends Controller
{
    public function SAS_TO_PDF(Request $request) {
        $users = Alumni::where('alumni_id', '=', $request->alumni_id)->get();

        $semesters = SasAnswers::where([['alumni_id', '=', $request->alumni_id],['question_id', '=', 2]])->value('answer');

        $section_1 = SasAnswers::whereIn('question_id', [3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24])->where('alumni_id', '=', $request->alumni_id)->get('answer');
        $arraySection_1 = [];
        foreach($section_1 as $section1) {
            array_push($arraySection_1, $section1->answer);
        }

        $section_2 = SasAnswers::whereIn('question_id', [25, 26, 27, 28])->where('alumni_id', '=', $request->alumni_id)->get('answer');
        $arraySection_2 = [];
        foreach($section_2 as $section2) {
            array_push($arraySection_2, $section2->answer);
        }

        $section_3 = SasAnswers::whereIn('question_id', [29, 30, 31, 32, 33, 34])->where('alumni_id', '=', $request->alumni_id)->get('answer');
        $arraySection_3 = [];
        foreach($section_3 as $section3) {
            array_push($arraySection_3, $section3->answer);
        }

        $section_4 = SasAnswers::whereIn('question_id', [35, 36, 37, 38])->where('alumni_id', '=', $request->alumni_id)->get('answer');
        $arraySection_4 = [];
        foreach($section_4 as $section4) {
            array_push($arraySection_4, $section4->answer);
        }

        $section_5 = SasAnswers::whereIn('question_id', [39, 40, 41, 42, 43, 44])->where('alumni_id', '=', $request->alumni_id)->get('answer');
        $arraySection_5 = [];
        foreach($section_5 as $section5) {
            array_push($arraySection_5, $section5->answer);
        }

        $section_6 = SasAnswers::whereIn('question_id', [45, 46, 47, 48, 49])->where('alumni_id', '=', $request->alumni_id)->get('answer');
        $arraySection_6 = [];
        foreach($section_6 as $section6) {
            array_push($arraySection_6, $section6->answer);
        }

        $section_7 = SasAnswers::whereIn('question_id', [50, 51, 52, 53, 54])->where('alumni_id', '=', $request->alumni_id)->get('answer');
        $arraySection_7 = [];
        foreach($section_7 as $section7) {
            array_push($arraySection_7, $section7->answer);
        }

        $section_8 = SasAnswers::whereIn('question_id', [55, 56, 57, 58, 59])->where('alumni_id', '=', $request->alumni_id)->get('answer');
        $arraySection_8 = [];
        foreach($section_8 as $section8) {
            array_push($arraySection_8, $section8->answer);
        }

        $section_9 = SasAnswers::whereIn('question_id', [60, 61, 62])->where('alumni_id', '=', $request->alumni_id)->get('answer');
        $arraySection_9 = [];
        foreach($section_9 as $section9) {
            array_push($arraySection_9, $section9->answer);
        }

        $section_10 = SasAnswers::whereIn('question_id', [63, 64, 65])->where('alumni_id', '=', $request->alumni_id)->get('answer');
        $arraySection_10 = [];
        foreach($section_10 as $section10) {
            array_push($arraySection_10, $section10->answer);
        }

        $section_11 = SasAnswers::whereIn('question_id', [66, 67, 68])->where('alumni_id', '=', $request->alumni_id)->get('answer');
        $arraySection_11 = [];
        foreach($section_11 as $section11) {
            array_push($arraySection_11, $section11->answer);
        }

        $section_12 = SasAnswers::whereIn('question_id', [69, 70, 71, 72, 73, 74, 75, 76])->where('alumni_id', '=', $request->alumni_id)->get('answer');
        $arraySection_12 = [];
        foreach($section_12 as $section12) {
            array_push($arraySection_12, $section12->answer);
        }

        $signature = strtoupper(SasAnswers::where([['alumni_id', '=', $request->alumni_id],['question_id', '=', 78]])->value('answer'));

        foreach($users as $user)
        {
            $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

            // set document information
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetTitle('SAS_' . $user->stud_number);

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
            $pdf->Cell(0, 0, 'QUESTIONNAIRE', 0, 1, 'C', 0, '', 0);

            $pdf->SetFont('times', '', 12);
            $pdf->ln(5);
            $html = <<<EOF
              <p><i>The objective of this study is to assess the Student Affairs and Services (SAS) Program, projects and activities of the Polytechnic University of the Philippines Taguig Branch.</i></p>
            EOF;
            $pdf->writeHTML($html, true, 0, true, 0);

            $pdf->SetFont('times', '', 12);
            $pdf->ln(15);
            $html = <<<EOF
                <b>Part 1.</b> RESPONDENT’S PERSONAL INFORMATION</p>
            EOF;
            $pdf->writeHTML($html, true, 0, true, 0);

            $pdf->SetFont('times', '', 12);
            $pdf->ln(5);
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
              <table style="width:100%; margin-top: 300px;">
                <tr>
                    <th colspan="1" style="width: 6%; font-weight: bold;">Sex: </th>
                    <td colspan="1" style="border-bottom: 1px solid black; width: 13%;"> $user->sex </td>
                    <td colspan="1" style="width: 7%;"></td>
                    <th colspan="1" style="width: 6%; font-weight: bold;">Age: </th>
                    <td colspan="1" style="border-bottom: 1px solid black; width: 17%;"> $user->age years old</td>
                    <td colspan="1" style="width: 7%;"></td>
                    <th colspan="1" style="width: 27%; font-weight: bold;">No. of Semesters in PUP: </th>
                    <td colspan="1" style="border-bottom: 1px solid black; width: 17%;"> $semesters </td>
                </tr>
              </table>
            EOF;
            $pdf->writeHTML($html, true, 0, true, 0);

            $pdf->SetFont('times', '', 12);
            $pdf->ln(10);
            $html = <<<EOF
                <p><b>Part 2.</b> ASSESSMENT OF THE STUDENTS AFFAIRS AND SERVICES (SAS) PROGRAM, PROJECTS AND ACTIVITIES OF THE POLYTECHNIC UNIVERSITY OF THE PHILIPPINES TAGUIG BRANCH</p>
            EOF;
            $pdf->writeHTML($html, true, 0, true, 0);

            $pdf->SetFont('times', '', 12);
            $pdf->ln(10);
            $html = <<<EOF
                <div style="text-align: center; font-weight: bold;">
                    <span>  1 - Very Satisfactory</span>
                    <span>  2 - Satisfactory</span>
                    <span>  3 - Unsatisfactory</span>
                    <span>  4 - Very Unsatisfactory</span>
                </div>
            EOF;
            $pdf->writeHTML($html, true, 0, true, 0);

            $pdf->SetFont('times', '', 12);
            $pdf->ln(5);
            $html = <<<EOF
                <style>
                    .table-form, .th-form, .td-form {
                        border: 1px solid black;
                        border-collapse: collapse;
                        padding: 8px;
                    }

                    th {
                        text-align: center;
                        font-weight: bold;
                    }

                    .number {
                        text-align: center;
                    }
                </style>
                <table style="width:100%;" class="table-form">
                <!-- Section 1 -->
                    <tr>
                        <th colspan="2" class="th-form" style="width: 87%;">STUDENT AFFAIRS AND SERVICES (SAS) PROGRAM</th>
                        <th colspan="1" class="th-form" style="width: 13%;">Rating</th>
                    </tr>
                    <tr>
                        <td class="td-form number" style="width: 8%">1.</td>
                        <td class="td-form" style="width: 79%">Clarity of objectives of the SAS program, projects and activities.</td>
                        <td class="td-form" style="text-align: center;">$arraySection_1[0]</td>
                    </tr>
                    <tr>
                        <td class="td-form number">2.</td>
                        <td class="td-form">Relevance of the SAS Program to students’ welfare and development.</td>
                        <td class="td-form" style="text-align: center;">$arraySection_1[1]</td>
                    </tr>
                    <tr>
                        <td class="td-form number">3.</td>
                        <td class="td-form">Consistency of the SAS Program with the PUP mission and vision.</td>
                        <td class="td-form" style="text-align: center;">$arraySection_1[2]</td>
                    </tr>
                    <tr>
                        <td class="td-form number">4.</td>
                        <td class="td-form">Consistency of the SAS Program with the College goals and curricular program objectives.</td>
                        <td class="td-form" style="text-align: center;">$arraySection_1[3]</td>
                    </tr>
                    <tr>
                        <td class="td-form number">5.</td>
                        <td class="td-form">Dissemination of the SAS Program, projects and activities.</td>
                        <td class="td-form" style="text-align: center;">$arraySection_1[4]</td>
                    </tr>
                    <tr>
                        <td class="td-form number">6.</td>
                        <td class="td-form">Qualification of heads and administrative support staff of SAS offices.</td>
                        <td class="td-form" style="text-align: center;">$arraySection_1[5]</td>
                    </tr>
                    <tr>
                        <td class="td-form number">7.</td>
                        <td class="td-form">Management and supervision of SAS program.</td>
                        <td class="td-form" style="text-align: center;">$arraySection_1[6]</td>
                    </tr>
                    <tr>
                        <td class="td-form number">8.</td>
                        <td class="td-form">Implementation of the SAS program.</td>
                        <td class="td-form" style="text-align: center;">$arraySection_1[7]</td>
                    </tr>
                    <tr>
                        <td class="td-form number">9.</td>
                        <td class="td-form">Responsiveness of the SAS program to students’ welfare and development.</td>
                        <td class="td-form" style="text-align: center;">$arraySection_1[8]</td>
                    </tr>
                    <tr>
                        <td class="td-form number">10.</td>
                        <td class="td-form">Adequacy of administrative support personnel for SAS.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_1[9]</td>
                    </tr>
                    <tr>
                        <td class="td-form number">11.</td>
                        <td class="td-form">Proximity of SAS offices.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_1[10]</td>
                    </tr>
                    <tr>
                        <td class="td-form number">12.</td>
                        <td class="td-form">Promptness of student services and transactions.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_1[11]</td>
                    </tr>
                    <tr>
                        <td class="td-form number">13.</td>
                        <td class="td-form">Courtesy of administrative support personnel.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_1[12]</td>
                    </tr>
                    <tr>
                        <td class="td-form number">14.</td>
                        <td class="td-form">Adequacy of physical facilities for SAS program, projects and activities.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_1[13]</td>
                    </tr>
                    <tr>
                        <td class="td-form number">15.</td>
                        <td class="td-form">Adequacy of equipment and materials for SAS.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_1[14]</td>
                    </tr>
                    <tr>
                        <td class="td-form number">16.</td>
                        <td class="td-form">Efficiency of student services and transactions.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_1[15]</td>
                    </tr>
                    <tr>
                        <td class="td-form number">17.</td>
                        <td class="td-form">Transparency and accountability in spending the budget for SAS.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_1[16]</td>
                    </tr>
                    <tr>
                        <td class="td-form number">18.</td>
                        <td class="td-form">Monitoring of SAS program, projects and activities.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_1[17]</td>
                    </tr>
                    <tr>
                        <td class="td-form number">19.</td>
                        <td class="td-form">Evaluation of the SAS program, projects and activities.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_1[18]</td>
                    </tr>
                    <tr>
                        <td class="td-form number">20.</td>
                        <td class="td-form">Conduct research on SAS program, projects and activities.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_1[19]</td>
                    </tr>
                    <tr>
                        <td class="td-form number">21.</td>
                        <td class="td-form">Dissemination and utilization of research results and outputs.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_1[20]</td>
                    </tr>
                    <tr>
                        <td class="td-form number">22.</td>
                        <td class="td-form">Rewards and incentives for excellence in SAS.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_1[21]</td>
                    </tr>

                <!-- Section 2 -->
                    <tr>
                        <th colspan="2" class="th-form">ADMISSION SERVICES</th>
                        <th colspan="1" class="th-form"></th>
                    </tr>
                    <tr>
                        <td class="td-form number">23.</td>
                        <td class="td-form">Dissemination of policy on student recruitment, selection, admission and retention.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_2[0] </td>
                    </tr>
                    <tr>
                        <td class="td-form number">24.</td>
                        <td class="td-form">System of student recruitment, selection and admission.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_2[1] </td>
                    </tr>
                    <tr>
                        <td class="td-form number">25.</td>
                        <td class="td-form">Implementation of the policy on student retentions.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_2[2] </td>
                    </tr>
                    <tr>
                        <td class="td-form number">26.</td>
                        <td class="td-form">Processing of students’ entrance and requirements.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_2[3] </td>
                    </tr>

                <!-- Section 3 -->
                    <tr>
                        <th colspan="2" class="th-form">INFORMATION AND ORIENTATION SERVICES</th>
                        <th colspan="1" class="th-form"></th>
                    </tr>
                    <tr>
                        <td class="td-form number">27.</td>
                        <td class="td-form">Availability of informational materials on the University’s mission and vision.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_3[0] </td>
                    </tr>
                    <tr>
                        <td class="td-form number">28.</td>
                        <td class="td-form">Availability of informational materials on College goals and program objectives.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_3[1] </td>
                    </tr>
                    <tr>
                        <td class="td-form number">29.</td>
                        <td class="td-form">Accessibility of informational materials on academic rules and regulations, student conduct and discipline.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_3[2] </td>
                    </tr>
                    <tr>
                        <td class="td-form number">30.</td>
                        <td class="td-form">Orientation of new students.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_3[3] </td>
                    </tr>
                    <tr>
                        <td class="td-form number">31.</td>
                        <td class="td-form">Orientation of returning and continuing students.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_3[4] </td>
                    </tr>
                    <tr>
                        <td class="td-form number">32.</td>
                        <td class="td-form">Availability of educational, career and social reading materials.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_3[5] </td>
                    </tr>
                <!-- Section 4 -->
                    <tr>
                        <th colspan="2" class="th-form">SCHOLARSHIP AND FINANCIAL ASSISTANCE</th>
                        <th colspan="1" class="th-form"></th>
                    </tr>
                    <tr>
                        <td class="td-form number">33.</td>
                        <td class="td-form">Accessibility of informational materials about scholarships, study grants and other schemes of financial aides.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_4[0] </td>
                    </tr>
                    <tr>
                        <td class="td-form number">34.</td>
                        <td class="td-form">Implementation of the policy on scholarship, study grants and other schemes of financial aide.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_4[1] </td>
                    </tr>
                    <tr>
                        <td class="td-form number">35.</td>
                        <td class="td-form">Monitoring of the performance of recipients of scholarship, study grants and other schemes of financial aides.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_4[2] </td>
                    </tr>
                    <tr>
                        <td class="td-form number">36.</td>
                        <td class="td-form">Generation of funds for scholarship, study grants and other financial aides.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_4[3] </td>
                    </tr>
                <!-- Section 5 -->
                    <tr>
                        <th colspan="2" class="th-form">HEALTH SERVICES</th>
                        <th colspan="1" class="th-form"></th>
                    </tr>
                    <tr>
                        <td class="td-form number">37.</td>
                        <td class="td-form">Dissemination of health services program, projects and activities.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_5[0] </td>
                    </tr>
                    <tr>
                        <td class="td-form number">38.</td>
                        <td class="td-form">Adequacy of medical services.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_5[1] </td>
                    </tr>
                    <tr>
                        <td class="td-form number">39.</td>
                        <td class="td-form">Adequacy of dental services.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_5[2] </td>
                    </tr>
                    <tr>
                        <td class="td-form number">40.</td>
                        <td class="td-form">Competence of medical and dental personnel.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_5[3] </td>
                    </tr>
                    <tr>
                        <td class="td-form number">41.</td>
                        <td class="td-form">Adequacy of medical and dental facilities, equipment and supplies.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_5[4] </td>
                    </tr>
                    <tr>
                        <td class="td-form number">42.</td>
                        <td class="td-form">Promptness of medical and dental services.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_5[5] </td>
                    </tr>
                <!-- Section 6 -->
                    <tr>
                        <th colspan="2" class="th-form">GUIDANCE AND COUNSELING SERVICES</th>
                        <th colspan="1" class="th-form"></th>
                    </tr>
                    <tr>
                        <td class="td-form number">43.</td>
                        <td class="td-form">Appraisal system for student’s attributes, adaptability, and competence.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_6[0] </td>
                    </tr>
                    <tr>
                        <td class="td-form number">44.</td>
                        <td class="td-form">Availability of counseling services.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_6[1] </td>
                    </tr>
                    <tr>
                        <td class="td-form number">45.</td>
                        <td class="td-form">Maintenance of confidential records by the guidance counselors.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_6[2] </td>
                    </tr>
                    <tr>
                        <td class="td-form number">46.</td>
                        <td class="td-form">Availability of counseling rooms.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_6[3] </td>
                    </tr>
                    <tr>
                        <td class="td-form number">47.</td>
                        <td class="td-form">Monitoring of the effectiveness of guidance and placement activities.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_6[4] </td>
                    </tr>
                <!-- Section 7 -->
                    <tr>
                        <th colspan="2" class="th-form">FOOD SERVICES</th>
                        <th colspan="1" class="th-form"></th>
                    </tr>
                    <tr>
                        <td class="td-form number">48.</td>
                        <td class="td-form">Management of safety and sanitary conditions of canteen and food outlets.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_7[0] </td>
                    </tr>
                    <tr>
                        <td class="td-form number">49.</td>
                        <td class="td-form">Coordination of the food safety of food services outside the school premises with the local government.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_7[1] </td>
                    </tr>
                    <tr>
                        <td class="td-form number">50.</td>
                        <td class="td-form">Nutrition of meals served in the canteen and food outlets.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_7[2] </td>
                    </tr>
                    <tr>
                        <td class="td-form number">51.</td>
                        <td class="td-form">Affordability and reasonableness of prices of the meals in the canteen and food outlets.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_7[3] </td>
                    </tr>
                    <tr>
                        <td class="td-form number">52.</td>
                        <td class="td-form">Personal hygiene of canteen and food outlets personnel.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_7[4] </td>
                    </tr>
                <!-- Section 8 -->
                    <tr>
                        <th colspan="2" class="th-form">CAREER AND PLACEMENT SERVICES</th>
                        <th colspan="1" class="th-form"></th>
                    </tr>
                    <tr>
                        <td class="td-form number">53.</td>
                        <td class="td-form">Availability of informational materials about career and employment opportunities.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_8[0] </td>
                    </tr>
                    <tr>
                        <td class="td-form number">54.</td>
                        <td class="td-form">Appraisal of students for career and job placement.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_8[1] </td>
                    </tr>
                    <tr>
                        <td class="td-form number">55.</td>
                        <td class="td-form">Provision for career seminar and job placement services.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_8[2] </td>
                    </tr>
                    <tr>
                        <td class="td-form number">56.</td>
                        <td class="td-form">Linkages and networks for possible career and job placement.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_8[3] </td>
                    </tr>
                    <tr>
                        <td class="td-form number">57.</td>
                        <td class="td-form">Monitoring of student placement provided.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_8[4] </td>
                    </tr>
                <!-- Section 9 -->
                    <tr>
                        <th colspan="2" class="th-form">SAFETY AND SECURITY SERVICES</th>
                        <th colspan="1" class="th-form"></th>
                    </tr>
                    <tr>
                        <td class="td-form number">58.</td>
                        <td class="td-form">Competence of security personnel.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_9[0] </td>
                    </tr>
                    <tr>
                        <td class="td-form number">59.</td>
                        <td class="td-form">Care of safety and security of students and students’ belongings.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_9[1] </td>
                    </tr>
                    <tr>
                        <td class="td-form number">60.</td>
                        <td class="td-form">Maintenance of safety and security of school environment, buildings and facilities.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_9[2] </td>
                    </tr>
                <!-- Section 10 -->
                    <tr>
                        <th colspan="2" class="th-form">STUDENT DISCIPLINE</th>
                        <th colspan="1" class="th-form"></th>
                    </tr>
                    <tr>
                        <td class="td-form number">61.</td>
                        <td class="td-form">Dissemination of sex sensitive rules and regulations.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_10[0] </td>
                    </tr>
                    <tr>
                        <td class="td-form number">62.</td>
                        <td class="td-form">Definition of appropriate student conduct.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_10[1] </td>
                    </tr>
                    <tr>
                        <td class="td-form number">63.</td>
                        <td class="td-form">Sanctions for student misconduct.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_10[2] </td>
                    </tr>
                <!-- Section 11 -->
                    <tr>
                        <th colspan="2" class="th-form">SERVICES FOR STUDENTS WITH SPECIAL NEEDS</th>
                        <th colspan="1" class="th-form"></th>
                    </tr>
                    <tr>
                        <td class="td-form number">64.</td>
                        <td class="td-form">Accommodation of students with disabilities and learners with special needs.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_11[0] </td>
                    </tr>
                    <tr>
                        <td class="td-form number">65.</td>
                        <td class="td-form">Provision of facilities for students with disabilities.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_11[1] </td>
                    </tr>
                    <tr>
                        <td class="td-form number">66.</td>
                        <td class="td-form">Provision of life skills training like conflict management and counseling.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_11[2] </td>
                    </tr>
                <!-- Section 12 -->
                    <tr>
                        <th colspan="2" class="th-form">STUDENT ORGANIZATIONS AND ACTIVITIES</th>
                        <th colspan="1" class="th-form"></th>
                    </tr>
                    <tr>
                        <td class="td-form number">67.</td>
                        <td class="td-form">System of accreditation and recognition of student organizations.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_12[0] </td>
                    </tr>
                    <tr>
                        <td class="td-form number">68.</td>
                        <td class="td-form">Dissemination of requirements and procedure for accreditation of student groups.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_12[1] </td>
                    </tr>
                    <tr>
                        <td class="td-form number">69.</td>
                        <td class="td-form">Provision of office space and other school support for accredited student group.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_12[2] </td>
                    </tr>
                    <tr>
                        <td class="td-form number">70.</td>
                        <td class="td-form">Mechanism for student organizations to coordinate with school administration.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_12[3] </td>
                    </tr>
                    <tr>
                        <td class="td-form number">71.</td>
                        <td class="td-form">Provision of leadership trainings.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_12[4] </td>
                    </tr>
                    <tr>
                        <td class="td-form number">72.</td>
                        <td class="td-form">Opportunity to interact with other student organizations from other schools.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_12[5] </td>
                    </tr>
                    <tr>
                        <td class="td-form number">73.</td>
                        <td class="td-form">Recognition of the students the right to govern themselves.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_12[6] </td>
                    </tr>
                    <tr>
                        <td class="td-form number">74.</td>
                        <td class="td-form">Opportunity to represent students in student council and board of regents.</td>
                        <td class="td-form" style="text-align: center;"> $arraySection_12[7] </td>
                    </tr>
                </table>
            EOF;
            $pdf->writeHTML($html, true, 0, true, 0);

            $pdf->ln(20);
            $html = <<<EOF
                <table style="width:100%;">
                    <tr>
                        <td style="width: 45%"></td>
                        <td colspan="1" style="width: 55%; text-align: center; text-transform: uppercase;">
                            $signature
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 45%"></td>
                        <td colspan="1" style="font-size: 14px; width: 55%; text-align: center; border-top: 1px solid black;">
                            <i>Signature</i>
                        </td>
                    </tr>
                </table>
            EOF;
            $pdf->writeHTML($html, true, 0, true, 0);

            //Close and output PDF document
            $pdf->Output('SAS_' . $user->stud_number . '.pdf', 'I');
        }
    }
}
