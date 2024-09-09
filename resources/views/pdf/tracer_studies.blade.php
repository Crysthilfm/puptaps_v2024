<!DOCTYPE html>
<html>
<head>
<style>
  /* Reset some default styling */
  body, div, p {
    margin: 0;
    padding: 0;
  }
  .txtHead {
    margin:0;
    padding:0;
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
    margin-left: auto;
    margin-right: auto;
    width: 100%;
    text-align: center;
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
    height: 50px;
    margin-top: -105px;
  }
  footer{
    position: fixed;
    left: 0px;
    right: 0px;
    height: 50px;
    bottom: 65px;
    margin-bottom: -115px;
    display: table; 
    clear:both; 
    width:100%;
  } 
  .pup-footer {
    font-size: 12px;
    font-family: 'Times New Roman', Times, serif;
    display: inline-block;
    margin-top:15px;
    text-align: left;
  } 
  @page{
    margin-top: 125px;
    margin-bottom: 150px;
  }
  table{
    width:100%;
	  border:1px solid rgb(102, 102, 102);
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
  
</style>
<link rel="stylesheet" href="{{ public_path().'bootstrap/css/bootstrap.min.css' }}">

</head>
<body>

    <header>
      <div style="float:right; width:80%;">
        <p>Republic of the Philippines</p>
        <h4 class="txtHead">POLYTECHNIC UNIVERSITY OF THE PHILIPPINES</h4>
        <p>Office of the Vice President for Branches and Satelite Campuses</p>
        <h4 class="txtHead">TAGUIG BRANCH</h4>
      </div>
      <div>
        <img src="{{ public_path().'/img/pupLogo.png' }}" style="width:75px; padding: 5px;">
      </div>
      <hr>
    </header>
    <footer>
      <hr style="margin-top: -14px;">
      <div class="pup-footer" style="width:70%;">
          Printed on:  {{date('Y-m-d')}} | Version: {{ $tracerVersionName->tracer_version_name }}<br>
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
      
    
    @if($totalRespondentsThisBatch < 1)
      <div class="container"><h3>No data available...</h3></div>
    @else

    <div>
      <br>
      <p>1.	What is the response rate of the graduates from academic year {{$batch_from}} - {{$batch_to}}?</p>
        <b> There are {{$totalRespondentsThisBatch}} @if ($course != "all") {{ $course }}@endif Respondents out of {{ $totalAlumniThisBatch}} total Alumni in batch
          @if ($batch_from == $batch_to)
            {{$batch_from}}
          @else
            {{$batch_from}}-{{$batch_to}}
          @endif </b>
      <br><br>
      <p>2.	What are the employment characteristics of the graduates as classified according to their course in terms of?:</p>
      <ul>
        <li><b>Employment Status:</b> <br>
          As of {{ date('Y-m-d') }}
          {{ $employmentStatus }}
        </li>
        <li><b>First Job after college:</b> <br>
            <div class="container">
                <h3>Table 1: First Job</h3>
                <img src="{{ $chartUrl_FirstJob }}" height="220px">
              </div>
            <div>
            {{ $getFirstJobAfterCollege }}
            </div>
            <br>
        </li>
        <li>
            <b>Length of job search?:</b><br>
            {{$lengthOfJobSearch}}
            
            <br>
        </li>
        <li> <b>Present position?</b><br>
          {{$getPresentPosition}}
        </li>
      </ul>
    </div>

    <div class="container">
      <h3>Table 2: Graduate to Employment</h3>
      <img src="{{ $chartUrl_GradToEmp }}" height="220px"><br><br>
      <p>{{$graduateToEmploymentDescription}}</p>
    </div>
    
    <div class="container">
      <h3>Table 3: Tenure in the Company</h3>
      <img src="{{ $chartUrl_EmpType }}" height="220px"><br><br>
      <p>{{$employmentTypeDescription}}</p>
    </div>
    <br>
    <div class="container">
      <div>
        <h3>Table 4: Top 10 Companies Alumni are employed in</h3>
        <table style="margin-left: auto;
        margin-right: auto;">
          <tr>
            <th>Company Name</th>
            <th>Alumni Employed</th>
          </tr>
          <tbody>
            @foreach ($companyName as $a)
            <tr>
              <td>{{$a->Company}}</td>
              <td>{{$a->alumniCount}}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
        <br><br>
      </div>
    </div>

    <div class="container">
      <h3>Table 5: Salary</h3>
      <img src="{{ $chartUrl_Salary }}" height="220px"><br><br>
      <p>{{$salaryRankDescription}}</p>
    </div>
    
    <hr>
    <?php $ctr=0; $tableNum=6;?>
    @foreach ($arrayanswer as $a)
      <div class="container">
        <h3>Table {{$tableNum}}: {{$questionArray[$ctr][0]}}</h3>
        <img src="{{ $a }}" height="220px"><br>
        {{$descriptionArray[$ctr]}}
      </div>
      <?php $ctr++; $tableNum++;?>
    @endforeach

    @endif
    
    <script src="{{ public_path().'bootstrap/js/bootstrap.bundle.js' }}"></script>
</body>
</html>
