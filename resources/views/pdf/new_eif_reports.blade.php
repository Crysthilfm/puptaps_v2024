<!DOCTYPE html>
<html>
  <head
    <link rel="stylesheet" href="{{ public_path().'bootstrap/css/bootstrap.min.css' }}">
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    {{-- Bootstrap --}}
    
    {{-- CSS --}}
<link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/common.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
  </head>
<body>
  <div class="px-0 py-0 container-fluid">
      <div class="row g-0" style="height: 100vh;">

          <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
              <div class="row g-0 w-100">
                  <div class="col-12">
                      <livewire:admin.admin-navbar />
                  </div>
                  <form action="/reports/printReports" id="hiddenForm" method="post" enctype="multipart/form-data" class="row" style="margin-top: 10%; width:100%; display:flex; justify-content:center;">
                    @csrf
                    <div style="display:flex; justify-content:center;">
                      <h4>After charts have loaded, please press the print button</h4><br><br><br>
                      <input type="hidden" name="chartData" id="chartInputData">
                      <input type="hidden" name="batch" value="{{$batch}}">
                      <input type="hidden" name="course" value="{{$course}}">
                      <input type="hidden" name="alumniCount" value="{{$alumniCount}}">
                      <input type="hidden" name="report_type" value="Exit Interview Form">
                    </div>
                    
                    <div style="display:flex; justify-content:center;">
                      <br>
                      <input type="submit" value="Print Chart" style="background-color: #642406; /* Green */
                      border: none;
                      color: white;
                      padding: 20px;
                      text-align: center;
                      text-decoration: none;
                      font-size: 16px;
                      margin: 4px 2px;
                      cursor: pointer;
                      border-radius:20px;
                      font-weight:800;">
                    </div>
                  </form>
                  <div class="col-12">
                      <div class="container pt-5" style="display:flex; justify-content:center;">
                        <div id="draw-charts"></div>
                      </div>
                  </div>
              </div>
          </div>
          
      </div>
  </div>

    {{-- JS --}}
    <script src="{{ public_path().'bootstrap/js/bootstrap.bundle.js' }}"></script>

    <script>
      // ===================================================================================================================================================
// // Other info
// ===================================================================================================================================================
$(document).ready(function() {
        // Load in data
        var dataContent = {!! json_encode($questionArray) !!};
        var totalAlumni = {!! json_encode($alumniCount) !!};
        var alumniData = {!! json_encode($alumni) !!};
        console.log(alumniData);
        var loadedCharts = "";
        var sas = {!! json_encode($sasQuestionArray) !!};

        // AGE
        google.charts.load('current',{
          callback: function(){
            // Initiate google chartt
            var data = new google.visualization.DataTable();
            var totalCount = 0;
            data.addColumn('string', 'Answer');
            data.addColumn('number', 'Count');

            // Load in answers in an array
            let content = [];

            // Manually inputted data to table
            var tabledData = "<div><span style='margin:0px;width:100%;text-align: center !important;font-size: 18px; font-weight:800;'>Age</span><table cellspacing='0' cellpadding='0' style:'font-size: 8px; border:none'><thead style='border:none;'><th style='border:none'>Answer</th><th style='border:none'>Count</th><th style='border:none'>Percentage</th></thead><tbody style='border:none'>";

            for(let i = 0; i<alumniData.age.length; i++) {
              var desccription = "";
              description = "";
              
              // Get text description of rating
              var total = alumniData.age[i].count;
              var percentage = (alumniData.age[i].count/totalAlumni)*100;

              description += alumniData.age[i].item;
              tabledData += "<tr style='border:none'><td>"+description+"</td>";
              tabledData += "<td>"+ total+"</td>";
              tabledData += "<td> "+ parseFloat(percentage).toFixed(2)+"% </td></tr>";

              totalCount += alumniData.age[i].count;
              
              var cell = [ description, total ]
              content.push(cell);
            }

            tabledData += "<tr><td>Total</td> "+"<td>"+totalCount+"</td><td></td>"+"</tr>";
            // Close table
            tabledData += "</tbody></table><div>";

            data.addRows(content);

            // Graph settings
            var options = {
              pieSliceText: 'percentage',  // Display the numeric values
              legend: { position: 'right' },  // Position the legend to avoid overlap
              pieSliceTextStyle: { fontSize: 12 },  // Adjust font size if needed
              width:500,
              height:250,
              length:500
            };

            // Set  and print out graph
            let chart_div = document.getElementById("draw-charts"+"0"); 
            let chart = new google.visualization.PieChart(chart_div);
            google.visualization.events.addListener(chart, 'ready', function(){

              // Define headers
              
              loadedCharts += "<div><h3>Table 1: Age</h3>";

              chart_div.innerHtml = '<img src="'+chart.getImageURI()+'">';
              loadedCharts += "<table cellspacing='0' cellpadding='0' style='border: none; width:100%;'><tr style='border: none;'>";

              loadedCharts += "<td style='border: none; width:250px;'>";
              loadedCharts += tabledData;
              loadedCharts += "</td>";

              loadedCharts += "<td style='border: none; margin-left:-100px;'>";
              loadedCharts += chart_div.innerHtml;
              loadedCharts += "</td>";

              loadedCharts +="</tr></table>";
            });
            chart.draw(data, options);

          },
          packages: ['corechart']
        });
        
        // GENDER
        google.charts.load('current',{
          callback: function(){
            // Initiate google chartt
            var data = new google.visualization.DataTable();
            var totalCount = 0;
            data.addColumn('string', 'Answer');
            data.addColumn('number', 'Count');

            // Load in answers in an array
            let content = [];

            // Manually inputted data to table
            var tabledData = "<div><span style='margin:0px;width:100%;text-align: center !important;font-size: 18px; font-weight:800;'>Gender</span><table cellspacing='0' cellpadding='0' style:'font-size: 8px; border:none'><thead style='border:none;'><th style='border:none'>Answer</th><th style='border:none'>Count</th><th style='border:none'>Percentage</th></thead><tbody style='border:none'>";

            for(let i = 0; i<alumniData.gender.length; i++) {
              var desccription = "";
              description = "";
              
              // Get text description of rating
              var total = alumniData.gender[i].count;
              var percentage = (alumniData.gender[i].count/totalAlumni)*100;

              description += alumniData.gender[i].item;
              tabledData += "<tr style='border:none'><td>"+description+"</td>";
              tabledData += "<td>"+ total+"</td>";
              tabledData += "<td> "+ parseFloat(percentage).toFixed(2)+"% </td></tr>";

              totalCount += alumniData.gender[i].count;
              
              var cell = [ description, total ]
              content.push(cell);
            }

            tabledData += "<tr><td>Total</td> "+"<td>"+totalCount+"</td><td></td>"+"</tr>";
            // Close table
            tabledData += "</tbody></table><div>";

            data.addRows(content);

            // Graph settings
            var options = {
              pieSliceText: 'percentage',  // Display the numeric values
              legend: { position: 'right' },  // Position the legend to avoid overlap
              pieSliceTextStyle: { fontSize: 12 },  // Adjust font size if needed
              width:500,
              length:500
            };

            // Set  and print out graph
            let chart_div = document.getElementById("draw-charts"+"0"); 
            let chart = new google.visualization.PieChart(chart_div);
            google.visualization.events.addListener(chart, 'ready', function(){

              // Define headers
              
              loadedCharts += "<div><h3>Table 2: Gender</h3>";

              chart_div.innerHtml = '<img src="'+chart.getImageURI()+'">';
              loadedCharts += "<table cellspacing='0' cellpadding='0' style='border: none; width:100%;'><tr style='border: none;'>";

              loadedCharts += "<td style='border: none; width:250px;'>";
              loadedCharts += tabledData;
              loadedCharts += "</td>";

              loadedCharts += "<td style='border: none; margin-left:-100px;'>";
              loadedCharts += chart_div.innerHtml;
              loadedCharts += "</td>";

              loadedCharts +="</tr></table>";
            });
            chart.draw(data, options);

          },
          packages: ['corechart']
        });

    //CIVIL STATUS
        google.charts.load('current',{
          callback: function(){
            // Initiate google chartt
            var data = new google.visualization.DataTable();
            var totalCount = 0;
            data.addColumn('string', 'Answer');
            data.addColumn('number', 'Count');

            // Load in answers in an array
            let content = [];

            // Manually inputted data to table
            var tabledData = "<div><span style='margin:0px;width:100%;text-align: center !important;font-size: 18px; font-weight:800;'>Civil Status</span><table cellspacing='0' cellpadding='0' style:'font-size: 8px; border:none'><thead style='border:none;'><th style='border:none'>Answer</th><th style='border:none'>Count</th><th style='border:none'>Percentage</th></thead><tbody style='border:none'>";

            for(let i = 0; i<alumniData.gender.length; i++) {
              var desccription = "";
              description = "";
              
              // Get text description of rating
              var total = alumniData.civilStatus[i].count;
              var percentage = (alumniData.civilStatus[i].count/totalAlumni)*100;

              description += alumniData.civilStatus[i].item;
              tabledData += "<tr style='border:none'><td>"+description+"</td>";
              tabledData += "<td>"+ total+"</td>";
              tabledData += "<td> "+ parseFloat(percentage).toFixed(2)+"% </td></tr>";

              totalCount += alumniData.civilStatus[i].count;
              
              var cell = [ description, total ]
              content.push(cell);
            }

            tabledData += "<tr><td>Total</td> "+"<td>"+totalCount+"</td><td></td>"+"</tr>";
            // Close table
            tabledData += "</tbody></table><div>";

            data.addRows(content);

            // Graph settings
            var options = {
              pieSliceText: 'percentage',  // Display the numeric values
              legend: { position: 'right' },  // Position the legend to avoid overlap
              pieSliceTextStyle: { fontSize: 12 },  // Adjust font size if needed
              width:500,
              length:500
            };

            // Set  and print out graph
            let chart_div = document.getElementById("draw-charts"+"0"); 
            let chart = new google.visualization.PieChart(chart_div);
            google.visualization.events.addListener(chart, 'ready', function(){

              // Define headers
              
              loadedCharts += "<div><h3>Table 3: Civil Status</h3>";

              chart_div.innerHtml = '<img src="'+chart.getImageURI()+'">';
              loadedCharts += "<table cellspacing='0' cellpadding='0' style='border: none; width:100%;'><tr style='border: none;'>";

              loadedCharts += "<td style='border: none; width:250px;'>";
              loadedCharts += tabledData;
              loadedCharts += "</td>";

              loadedCharts += "<td style='border: none; margin-left:-100px;'>";
              loadedCharts += chart_div.innerHtml;
              loadedCharts += "</td>";

              loadedCharts +="</tr></table>";
            });
            chart.draw(data, options);

          },
          packages: ['corechart']
        });
        //Course
          google.charts.load('current',{
            callback: function(){
              // Initiate google chartt
              var data = new google.visualization.DataTable();
              var totalCount = 0;
              data.addColumn('string', 'Answer');
              data.addColumn('number', 'Count');

              // Load in answers in an array
              let content = [];

              // Manually inputted data to table
              var tabledData = "<div><span style='margin:0px;width:100%;text-align: center !important;font-size: 18px; font-weight:800;'>Course</span><table cellspacing='0' cellpadding='0' style:'font-size: 8px; border:none'><thead style='border:none;'><th style='border:none'>Answer</th><th style='border:none'>Count</th><th style='border:none'>Percentage</th></thead><tbody style='border:none'>";

              for(let i = 0; i<alumniData.course.length; i++) {
                var desccription = "";
                description = "";
                
                // Get text description of rating
                var total = alumniData.course[i].count;
                var percentage = (alumniData.course[i].count/totalAlumni)*100;

                description += alumniData.course[i].item;
                tabledData += "<tr style='border:none'><td>"+description+"</td>";
                tabledData += "<td>"+ total+"</td>";
                tabledData += "<td> "+ parseFloat(percentage).toFixed(2)+"% </td></tr>";

                totalCount += alumniData.course[i].count;
                
                var cell = [ description, total ]
                content.push(cell);
              }

              tabledData += "<tr><td>Total</td> "+"<td>"+totalCount+"</td><td></td>"+"</tr>";
              // Close table
              tabledData += "</tbody></table><div>";

              data.addRows(content);

              // Graph settings
              var options = {
                pieSliceText: 'percentage',  // Display the numeric values
                legend: { position: 'right' },  // Position the legend to avoid overlap
                pieSliceTextStyle: { fontSize: 12 },  // Adjust font size if needed
                width:500,
                length:600
              };

              // Set  and print out graph
              let chart_div = document.getElementById("draw-charts"+"0"); 
              let chart = new google.visualization.PieChart(chart_div);
              google.visualization.events.addListener(chart, 'ready', function(){

                // Define headers
                
                loadedCharts += "<div><h3>Table 4: Course</h3>";

                chart_div.innerHtml = '<img src="'+chart.getImageURI()+'">';
                loadedCharts += "<table cellspacing='0' cellpadding='0' style='border: none; width:100%;'><tr style='border: none;'>";

                loadedCharts += "<td style='border: none; width:250px;'>";
                loadedCharts += tabledData;
                loadedCharts += "</td>";

                loadedCharts += "<td style='border: none; margin-left:-100px;'>";
                loadedCharts += chart_div.innerHtml;
                loadedCharts += "</td>";

                loadedCharts +="</tr></table>";
              });
              chart.draw(data, options);

            },
            packages: ['corechart']
          });
// ===================================================================================================================================================
// ===================================================================================================================================================
// ===================================================================================================================================================
        
        
        // Loop through each question
        for(let questionIndex = 0; questionIndex<dataContent.length; questionIndex++){
          
          // Define headers
            if(questionIndex == 0){
                $("#draw-charts").append( "<div> <h3>Personal</h4>");
            }
            if(questionIndex == 1){
                $("#draw-charts").append( "<hr style='height: 5px; background-color: black;'><h3>Overall</h4>");
            }
            if(questionIndex == 8){
                $("#draw-charts").append( "<hr style='height: 5px; background-color: black;'>Directors Office</h4>");
            }
            if(questionIndex == 11){
                $("#draw-charts").append( "<hr style='height: 5px; background-color: black;'>Office of the Head of Academic Programs</h4>");
            }
            if(questionIndex == 14){
                $("#draw-charts").append( "<hr style='height: 5px; background-color: black;'>Administrative Office</h4>");
            }
            if(questionIndex == 17){
                $("#draw-charts").append( "<hr style='height: 5px; background-color: black;'>Accounting/Cashier’s Office</h4>");
            }
            if(questionIndex == 20){
                $("#draw-charts").append( "<hr style='height: 5px; background-color: black;'>Office of Student Services/Scholarship</h4>");
            }
            if(questionIndex == 23){
                $("#draw-charts").append( "<hr style='height: 5px; background-color: black;'>Admission/Registration Office</h4>");
            }
            if(questionIndex == 26){
                $("#draw-charts").append( "<hr style='height: 5px; background-color: black;'>Guidance and Counseling Office</h4>");
            }
            if(questionIndex == 29){
                $("#draw-charts").append( "<hr style='height: 5px; background-color: black;'>Library Services</h4>");
            }
            if(questionIndex == 32){
                $("#draw-charts").append( "<hr style='height: 5px; background-color: black;'>Medical Services</h4>");
            }
            if(questionIndex == 35){
                $("#draw-charts").append( "<hr style='height: 5px; background-color: black;'>Dental Services</h4>");
            }
            if(questionIndex == 38){
                $("#draw-charts").append( "<hr style='height: 5px; background-color: black;'>Security Office</h4>");
            }
            if(questionIndex == 41){
                $("#draw-charts").append( "<hr style='height: 5px; background-color: black;'>Janitorial Services</h4>");
            }
            if(questionIndex == 44){
                $("#draw-charts").append( "<hr style='height: 5px; background-color: black;'>Overall PUPT</h4>");
            }

          if((questionIndex%4) == 0 && questionIndex != 0){
            $("#draw-charts").append("<div class='pagebreak' id='draw-charts"+questionIndex+"'></div> <br>")
          } else {
            $("#draw-charts").append("<div id='draw-charts"+questionIndex+"'></div> <br>")
          }

          google.charts.load('current',{
            callback: function(){
              // Initiate google chartt
              var data = new google.visualization.DataTable();
              data.addColumn('string', 'Answer');
              data.addColumn('number', 'Count');

              // Load in answers in an array
              let content = [];

              // Manually inputted data to table
              var tabledData = "<div><span style='text-align: start; font-weight:800; font-size: 14px;''>"+"Table "+(questionIndex + 5)+": "+sas[questionIndex].text+"</span><table cellspacing='0' cellpadding='0' style:'font-size: 8px; border:none'><thead style='border:none'><th style='border:none'>Answer</th><th style='border:none'>Count</th><th style='border:none'>Percentage</th></thead><tbody style='border:none'>";

              var totalCount = 0;
              var largest = 0;
              for(let i = 0; i<dataContent[questionIndex].answerList[0].length; i++){
                if(dataContent[questionIndex].answerList[0][i].totalCount > largest) largest = dataContent[questionIndex].answerList[0][i].totalCount
              }

              for(let i = 0; i<dataContent[questionIndex].answerList[0].length; i++){
                var desccription = "";
                description = "";

                // Get text description of rating
                var total = dataContent[questionIndex].answerList[0][i].totalCount;
                var percentage = (dataContent[questionIndex].answerList[0][i].totalCount/totalAlumni)*100;
                
                switch(dataContent[questionIndex].answerList[0][i].description){
                  case "5":  
                    if(largest == total){
                      var style = "background-color:yellow;"
                    } else {
                      var style = "";
                    }
                    
                    description += "Outstanding";
                    tabledData += "<tr style='border:none;"+style+"'><td>Outstanding</td>";
                    tabledData += "<td>"+ total +"</td>";
                    tabledData += "<td> "+ parseFloat(percentage).toFixed(2)+"% </td></tr>";
                    break;
                  case "4":
                    if(largest == total){
                      var style = "background-color:yellow;"
                    } else {
                      var style = "";
                    }
                    description += "Satisfactory";
                    tabledData += "<tr style='border:none;"+style+"'><td>Satisfactory</td>";
                    tabledData += "<td>"+ total +"</td>";
                    tabledData += "<td> "+ parseFloat(percentage).toFixed(2)+"% </td></tr>";
                    break;
                  case "3":
                    if(largest == total){
                      var style = "background-color:yellow;"
                    } else {
                      var style = "";
                    }
                    description += "Neutral";
                    tabledData += "<tr style='border:none;"+style+"'><td>Neutral</td>";
                    tabledData += "<td>"+ total +"</td>";
                    tabledData += "<td> "+ parseFloat(percentage).toFixed(2)+"% </td></tr>";
                    break;
                  case "2":
                    if(largest == total){
                      var style = "background-color:yellow;"
                    } else {
                      var style = "";
                    }
                    description += "Unsatisfactory";
                    tabledData += "<tr style='border:none;"+style+"'><td>Unsatisfactory</td>";
                    tabledData += "<td>"+ total +"</td>";
                    tabledData += "<td> "+ parseFloat(percentage).toFixed(2)+"% </td></tr>";
                    break;
                  case "1":
                    if(largest == total){
                      var style = "background-color:yellow;"
                    } else {
                      var style = "";
                    }
                    description += "Very Unsatisfactory";
                    tabledData += "<tr style='border:none;"+style+"'><td>Very Unatisfactory</td>";
                    tabledData += "<td>"+ total+"</td>";
                    tabledData += "<td> "+ parseFloat(percentage).toFixed(2)+"% </td></tr>";
                    break;
                  default:
                    description += dataContent[questionIndex].answerList[0][i].description;
                    tabledData += "<tr style='border:none'><td>"+dataContent[questionIndex].answerList[0][i].description+"</td>";
                    tabledData += "<td>"+ total+"</td>";
                    tabledData += "<td> "+ parseFloat(percentage).toFixed(2)+"% </td></tr>";
                    break;
                }
                totalCount += total;
                var cell = [ description, dataContent[questionIndex].answerList[0][i].totalCount ]
                content.push(cell);
              }
              tabledData += "<tr><td>Total</td> "+"<td>"+totalCount+"</td><td></td>"+"</tr>";
              // Close table
              tabledData += "</tbody></table>";

              data.addRows(content);

              // Graph settings
              var options = {
                pieSliceText: 'percentage',  // Display the numeric values
                legend: { position: 'right' },  // Position the legend to avoid overlap
                pieSliceTextStyle: { fontSize: 12 },  // Adjust font size if needed
                title: sas[questionIndex].text,
                width:500,
                height:200,
                length:500
              };

              // Set  and print out graph
              let chart_div = document.getElementById("draw-charts"+questionIndex); 
              let chart = new google.visualization.PieChart(chart_div);
              google.visualization.events.addListener(chart, 'ready', function(){

                // Define headers
                if(questionIndex == 0){
                  loadedCharts += "<div class='page-break'></div><h3>Personal</p>";
                }
                if(questionIndex == 1){
                  loadedCharts += "<div class='page-break'></div><hr style='height: 5px; background-color: black;'><p style='font-size:20px; font-weight:800;'>Overall</p>";
                }
                if(questionIndex == 8){
                  loadedCharts += "<div class='page-break'></div><hr style='height: 5px; background-color: black;'><p style='font-size:20px; font-weight:800;'>Directors Office</p>";
                }
                if(questionIndex == 11){
                  loadedCharts += "<div class='page-break'></div><hr style='height: 5px; background-color: black;'><p style='font-size:20px; font-weight:800;'>Office of the Head of Academic Programs</p>";
                }
                if(questionIndex == 14){
                  loadedCharts += "<div class='page-break'></div><hr style='height: 5px; background-color: black;'><p style='font-size:20px; font-weight:800;'>Administrative Office</p>";
                }
                if(questionIndex == 17){
                  loadedCharts += "<div class='page-break'></div><hr style='height: 5px; background-color: black;'><p style='font-size:20px; font-weight:800;'>Accounting/Cashier’s Office</p>";
                }
                if(questionIndex == 20){
                  loadedCharts += "<div class='page-break'></div><hr style='height: 5px; background-color: black;'><p style='font-size:20px; font-weight:800;'>Office of Student Services/Scholarship</p>";
                }
                if(questionIndex == 23){
                  loadedCharts += "<div class='page-break'></div><hr style='height: 5px; background-color: black;'><p style='font-size:20px; font-weight:800;'>Admission/Registration Office</p>";
                }
                if(questionIndex == 26){
                  loadedCharts += "<div class='page-break'></div><hr style='height: 5px; background-color: black;'><p style='font-size:20px; font-weight:800;'>Guidance and Counseling Office</p>";
                }
                if(questionIndex == 29){
                  loadedCharts += "<div class='page-break'></div><hr style='height: 5px; background-color: black;'><p style='font-size:20px; font-weight:800;'>Library Services</p>";
                }
                if(questionIndex == 32){
                  loadedCharts += "<div class='page-break'></div><hr style='height: 5px; background-color: black;'><p style='font-size:20px; font-weight:800;'>Medical Services</p>";
                }
                if(questionIndex == 35){
                  loadedCharts += "<div class='page-break'></div><hr style='height: 5px; background-color: black;'><p style='font-size:20px; font-weight:800;'>Dental Services</p>";
                }
                if(questionIndex == 38){
                  loadedCharts += "<div class='page-break'></div><hr style='height: 5px; background-color: black;'><p style='font-size:20px; font-weight:800;'>Security Office</p>";
                }
                if(questionIndex == 41){
                  loadedCharts += "<div class='page-break'></div><hr style='height: 5px; background-color: black;'><p style='font-size:20px; font-weight:800;'>Janitorial Services</p>";
                }
                if(questionIndex == 44){
                  loadedCharts += "<div class='page-break'></div><hr style='height: 5px; background-color: black;'><p style='font-size:20px; font-weight:800;'>Overall PUPT</p>";
                }

                if((questionIndex%4) == 0 && questionIndex != 0){
                  loadedCharts += "<div class='pagebreak' id='draw-charts"+questionIndex+"'></div> <br>";
                } else {
                  loadedCharts += "<div id='draw-charts"+questionIndex+"'></div> <br>";
                }

                chart_div.innerHtml = '<img src="'+chart.getImageURI()+'">';

                loadedCharts += "<table cellspacing='0' cellpadding='0' style='border: none; width:100%;'><tr style='border: none;'>";

                loadedCharts += "<td style='border: none; width:250px;'>";
                loadedCharts += tabledData;
                loadedCharts += "</td>";

                loadedCharts += "<td style='border: none; margin-left:-100px;'>";
                loadedCharts += chart_div.innerHtml;
                loadedCharts += "</td>";

                loadedCharts +="</tr></table>";
              });
              chart.draw(data, options);

            },
            packages: ['corechart']
          });

          if(questionIndex == 72){
            $("#draw-charts").append("</div>");
          }
        }

        setTimeout(() => {
          let chartsData = $("#draw-charts").html();
          $("#chartInputData").val(loadedCharts);
        }, 1000);
        
      });

    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>
