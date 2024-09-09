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
    page-break-before: always;
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
    margin-bottom: -130px;
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

</head>

<body>
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

    <div style="align-items: center; ">
      <div style="background-color:yellow; font-weight:600;">
        <h2>{{$report_type}} Reports for Batch {{$batch}}
          @if ($course != "all")
             - Course: {{$course}}
          @endif
        </h2>
        <p>Total Respondents {{$alumniCount}}</p>
        <hr>
      </div>
      @if($data)
          {!! $data !!}
      @endif
    </div>
</body>
</html>
