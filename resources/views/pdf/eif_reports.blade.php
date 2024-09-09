<!DOCTYPE html>
<html>
<head>
<style>
  /* Reset some default styling */
  body, div, p {
    margin: 0;
    padding: 0;
    align-content: center;
    text-align: center;
  }
  .txtHead {
    margin:0;
    padding:0;
  }
  .container h3{
    margin:0px;
    margin-bottom: 10px;
    padding:0px;
    display: inline-block;
    vertical-align: middle;
    font-size: 15px;
  }
  .content{
    margin: auto;
    padding: auto;
  }
  .txt{
    margin-left: 100px;
  }
  .content{
    float: left;
  }
  .container{
    display: block;
    padding:3px;
    width: 100%;
    text-align: left;
    height: auto;
    padding-left: 10px;
  }
  .page-break {
    page-break-after: always;
  }
  .boldText{
    font-style: bold;
  }
  header{
    position: fixed;
    left: 0px;
    right: 0px;
    height: 60px;
    margin-top: -105px;
    margin-bottom: 100px;
  }
  .container-text {
    display: inline-block;
    font-size: 15px;
    text-align: center;
    padding:2px;
  }
  footer{
    position: fixed;
    left: 0px;
    right: 0px;
    height: 50px;
    bottom: 50px;
    margin-bottom: -110px;
    display: table; 
    clear:both; 
    width:100%;
  }  
  @page{
    margin-top: 125px;
    margin-bottom: 160px;
  }
  table{
    width:100%;
	  border:1px solid rgb(102, 102, 102);
    position: relative;
    margin-top:20px;
  }
  th{
    width:100%;
    background:#570000;
    padding:2px;
    color: white;
    border-bottom: 1px solid rgb(102, 102, 102);
  }
  td{
    border-bottom: 1px solid rgb(102, 102, 102);
    text-align: center;
  }
  .pup-footer {
    font-size: 12px;
    font-family: 'Times New Roman', Times, serif;
    display: inline-block;
    margin-top:15px;
    text-align: left;
  }
  .head-title{
    background-color: yellow;
    border:1px solid black;
    width:60%;
    margin:auto;
    margin-bottom: 15px;
    text-align: center;
    margin-top:15px;
  }
  .categoryHeader{
    text-align: center;
    background-color: #570000;
    color: white;
    width: 100%;
    font-size: 25px;
    font-weight: bold;
    margin-bottom: 5px;
  }
  .container img{
    height:235px;
    margin:0px;
    padding:0px;
    display: inline-block;
  }
</style>
<link rel="stylesheet" href="{{ public_path().'bootstrap/css/bootstrap.min.css' }}">

</head>

<body style="border:4px solid yellow;">
    <header>
      <div style="float:right; width:80%; text-align:left;">
        <p style="text-align:left;">Republic of the Philippines</p>
        <h4 class="txtHead">POLYTECHNIC UNIVERSITY OF THE PHILIPPINES
        <p style="text-align:left;">Office of the Vice President for Branches and Satellite Campuses</p>
        <h4 class="txtHead">TAGUIG BRANCH
      </div>
      <div>
        <img src="{{ public_path().'/img/pupLogo.png' }}" style="width:75px; padding: 5px;">
      </div>
      <hr>
    </header>
    <footer>
      <hr style="margin-top: -14px;">
      <div class="pup-footer" style="width:70%;">
          Printed on:  {{date('Y-m-d')}} <br>
          General Santos Avenue, Lower Bicutan, Taguig City, Philippines 1632 <br>
          Direct Line: (02) 8837 5858 to 60 <br>
          Website: <u>www.pup.edu.ph</u> | Email: <u>taguig@pup.edu.ph</u>
          <h2>THE COUNTRY'S 1'<sup>st</sup> POLYTECHNIC U</h2>
      </div>

      <div class="pup-footer" style="width:28%; text-align:right; padding-top:10px;">
        <img src="{{ public_path().'/img/SOCO_PAB.png' }}" style="width:135px; margin: 2px;"><br>
        <b style="color:rgb(0, 132, 255); font-family: Arial, Helvetica, sans-serif; font-size: 11.2px;">ISO 9001:2015 CERTIFIED</b><br>
        <b style="color:rgb(0, 132, 255); font-family: Arial, Helvetica, sans-serif; font-size: 7.8px;">CERTIFICATE NUMBER: SCP0004130</b><br>
      </div>
    </footer>
      
  <div class="bodyContainer">
    <div class="head-title">
        <h3>EXIT INTERVIEW RESULT</h3>
        @if ($batch_from == $batch_to)
          Graduates YE {{$batch_from}}</h4>
        @else
          <h4>Graduates YE {{$batch_from}} - {{$batch_to}}</h4>
        @endif
    </div>

    @if($answerCount < 1)
      <div class="container-text"><h3>No data available...</h3></div>

    @else
      <br><br>
      <div class="container-text">
        @if ($batch_from == $batch_to)
          <h3>There are a total of {{$answerCount}} Exit Interview answers from batch {{$batch_from}}</h3>
        @else
          <h3>There are a total of {{$answerCount}} Exit Interview answers from batches {{$batch_from}} - {{$batch_to}}</h3>
        @endif
      </div>
      <br><br>

      <div class="container">
        <img src="{{ $alumniPersonalInfo[0] }}">
        <h3>Table 1: Gender</h3>
      </div> 
      <div class="container">
        <img src="{{ $alumniPersonalInfo[1] }}">
        <h3>Table 2: Age</h3>
      </div> 
      <div class="container" style="top:20px">
        <div class="page-break"></div>
        <br><br>
        <img src="{{ $alumniPersonalInfo[2] }}">
        <h3>Table 3: Civil Status</h3>
      </div> 
      <div class="container">
        <img src="{{ $alumniPersonalInfo[3] }}">
        <h3>Table 4: Course</h3>
      </div> 

      <?php $ctr=0; $tableNum=5; $showOnly=0;?>
      @foreach ($arrayanswer as $a)
          {{-- Taglines per category --}}
          @switch($tableNum)
              @case(6)
                  <div class="page-break"></div>
                  <div class="categoryHeader">Student's University Experience</div>
                  @break
              @case(9)
                  <div class="page-break"></div>
                  @break
              @case(12)
                  <div class="page-break"></div>
                  @break
              @case(13)
                  <div class="page-break"></div>
                  <div class="categoryHeader">Director's Office</div>
                  @break
              @case(16)
                  <div class="page-break"></div>
                  <div class="categoryHeader">Office of the Head of Academic Program</div>
                  @break
              @case(19)
                  <div class="page-break"></div>
                  <div class="categoryHeader">Administrative Office</div>
                  @break
              @case(22)
                  <div class="page-break"></div>
                  <div class="categoryHeader">Accounting/Cashier's Office</div>
                  @break
              @case(25)
                  <div class="categoryHeader">Office of Student Services/Scholarship</div>
                  @break
              @case(28)
                  <div class="page-break"></div>
                  <div class="categoryHeader">Admission/Registration Office</div>
                  @break
              @case(31)
                  <div class="page-break"></div>
                  <div class="categoryHeader">Guidance and Counseling Office</div>
                  @break
              @case(34)
                  <div class="page-break"></div>
                  <div class="categoryHeader">Library Services</div>
                  @break
              @case(37)
                  <div class="page-break"></div>
                  <div class="categoryHeader">Medical Services</div>
                  @break
              @case(40)
                  <div class="page-break"></div>
                  <div class="categoryHeader">Dental Services</div>
                  @break
              @case(43)
                  <div class="page-break"></div>
                  <div class="categoryHeader">Security Office</div>
                  @break
              @case(46)
                  <div class="page-break"></div>
                  <div class="categoryHeader">Janitorial Services</div>
                  @break
              @case(49)
                  <div class="page-break"></div>
                  <div class="categoryHeader">Overall Evaluation on PUPT</div>
                  @break
              @default         
          @endswitch

          @if ($tableNum == 6 || $tableNum == 9 || $tableNum == 12 || $tableNum == 13 || $tableNum == 16 || $tableNum == 19 || $tableNum == 22 || $tableNum == 25 || 
          $tableNum == 28 || $tableNum == 31 || $tableNum == 34 || $tableNum == 37 || $tableNum == 40 || $tableNum == 43 ||
          $tableNum == 46)
            <br><br><br>
          @elseif($tableNum == 49)
            <br><br>
          @endif

          <div class="container">  
            <img src="{{ $a }}">
            @if ($tableNum == 5)
              <h3>Table 5: Reason for leaving PUP</h3>
            @else
              <h3>Table {{$tableNum}}: {{$questionArray[$ctr][0]}}</h3>
            @endif
          </div>   
  
          <?php $ctr++; $tableNum++; $showOnly++;?>
      @endforeach

    @endif

    <?php $ctr=0 ?>
    @foreach ($suggestionsByCourse as $courseSuggestion)
    @if ($ctr > 0)
      <div class="page-break"></div>
    @endif
      <table>
        <tr>
          <th height="40px">Suggestions of [{{$courses[$ctr]->course_desc}}]</th>
        </tr>
        <tbody>
          @foreach ($courseSuggestion as $a)
          <tr>
            <td>{{$a->answer}}</td>
          </tr>
          @endforeach
        </tbody>
      </table> 
      <?php $ctr++; ?>
    @endforeach
    

    
    <div style="width:100%; margin-top:70px;">
      <div style="text-align:right !important; width:100%; margin-right:80px;">
        <div style="display: inline-block; ">
          <p>Prepared by: <br><br><br><br><br> 
            _________________ <br> 
            <b>LIWANAG L. MALIKSI</b> <br> 
            Guidance Counselor </p>
        </div>
      </div>
      <div style="text-align:left !important; width:100%;">
        <div style="display: inline-block;">
          <p>Noted: <br><br><br><br><br> 
            _________________ <br> 
            <b>MARISSA B. FERRER</b> <br>
             Director, PUP Taguig </p>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
