//$(document).ready(function () {
//    console.log("ready...")
/*
Code 1 System Aux
Code 2 Istirahat
Code 3 Eskalasi
Code 4 Briefing
Code 5 Outgoing call
Code 6 Isi form
Code 7 Rest Room
Code 8 Sholat
Code 9 System Error
Code 0 Aux System
*/


//});
var myVarX;
var myVarY;
var urlUidesk='https://localhost/ahuomni/apps/WebServiceGetDataMaster.asmx/UIDESK_TrmMasterCombo';

// $(document).ready(function(){
//     $("#submitData").click(function(){
//         // Your data to be sent in the POST request
        
//         // jQuery POST request
//         /*$.post("https://crm.uidesk.id/roatex/apps/WebServiceGetDataMaster.asmx/UIDESK_TrmMasterCombo?TrxID=2024-03-04&TrxUserName=2024-03-04&TrxAction=UIDESK128", postData, function(data, status){
//             // Handle the response here
//             console.log("Data: " + ("#date1").val() + "\nStatus: " + status);
//         });*/
//         console.log("date1: " + $("#date1").val().replace('T', ' ') + "\ndate2: " + $("#date2").val().replace('T', ' '));
//         fetchData($("#date1").val().replace('T', ' '),$("#date2").val().replace('T', ' '));
//     });
// });

function myFunction() {
 
//   myVarX = setInterval(fetchData, 1000);
//   myVarY = setInterval(agentList, 1000);
  //calloutbound staffedoutbound auxagent waiting acdin avail callabdn callanswer

  //------------------------
  getDateTime();

  SLA();
  fetchDataState();
  fetchDataTotalEmail();
  fetchDataKelola();

  //------------------------

  
}
function SLA(){
  var currentDate = new Date();
  var day = currentDate.getDate();
    var jqxhr = $.getJSON("BE/getsummary_v2.php", function (data) {
        $.each(data["DataDetail"], function (i, items) {
                  
            
            $('#callAht').html(items['Average Handling Time (AHT)'][day]);
            $('#calltotal').html(items['Total Call'][day]);
            $('#callanswer').html(items['Call Answered'][day]);
            $('#callabdn').html(items['Abnd. Queue'][day]);
            $('#valueVoip').html(items['Service Level'][day]+' %');
           
           
        });
        })
        .done(function () {
          //console.log( "done" );
          
        })
        .fail(function () {
          //console.log( "error" );
        })
        .always(function () {
          //console.log( "complete" );
        });
}

function fetchDataState(){
  
    

    $.ajax({
      type: "POST",
      url: "https://kanmo.uidesk.id/crm/apps/WebServiceGetDataMaster.asmx/UIDESK_TrmMasterCombo",
      data: "{TrxID:'', TrxUserName: '', TrxAction: 'UIDESK136'}",
      contentType: "application/json; charset=utf-8",
      dataType: "json",
      success: function (data) {

          var json = JSON.parse(data.d);
          var i, x, resultSourceEnquiryReason = "";
          console.log(json);
          for (i = 0; i < json.length; i++) {
            //alert(json[i].Jumlah)
            if(json[i].Jenis == "Ready")

              $("#stateready").html(json[i].Jumlah);
            else
              $("#stateaux").html(json[i].Jumlah);   

           
            

        }

      },
      error: function (xmlHttpRequest, textStatus, errorThrown) {
          console.log(xmlHttpRequest.responseText);
          console.log(textStatus);
          console.log(errorThrown);
      }
  })

 
}

function fetchDataTotalEmail(){
  
  $("#TotalAnsweredEmail").html(0);
  $("#TotalIncomingEmail").html(0);
  $("#TotalQueEmail").html(0);
  $("#TotalAbnEmail").html(0);
  $("#TotalNotResponseEmail").html(0);


  $.ajax({
      type: "POST",
      url: urlUidesk,
      data: "{TrxID:'', TrxUserName: '', TrxAction: 'UIDESK200'}",
      contentType: "application/json; charset=utf-8",
      dataType: "json",
      success: function (data) {

          var json = JSON.parse(data.d);
          var i, x, resultSourceEnquiryReason = "";
          console.log(json);
          for (i = 0; i < json.length; i++) {

            console.log(json[i].Jenis);
            console.log(json[i].Jumlah);
            if(json[i].Jenis == "TotalEmail"){
              $("#emailtotal").html(json[i].Jumlah);
            }else if(json[i].Jenis == "AnsweredEmail"){
              $("#emailanswer").html(json[i].Jumlah);
            }else if(json[i].Jenis == "QueueEmail"){
              $("#emailwait").html(json[i].Jumlah);
            }else if(json[i].Jenis == "FRT"){
              $("#emailfrt").html(json[i].Jumlah);
            }
             

        }
        var totalEmails = parseFloat($("#emailtotal").html());
        var answeredEmails = parseFloat($("#emailanswer").html());
        
        var aht = (answeredEmails !== 0) ? (totalEmails / answeredEmails) * 100 : 0;
        
        $("#emailaht").html(aht);
        

      },
      error: function (xmlHttpRequest, textStatus, errorThrown) {
          console.log(xmlHttpRequest.responseText);
          console.log(textStatus);
          console.log(errorThrown);
      }
  })
}
function fetchDataKelola(){
  //Lc
  $("#lctotal").html(0);
  $("#lcreply").html(0);
  $("#lcwait").html(0);

    //Lc
    $("#fbtotal").html(0);
    $("#fbreply").html(0);
    $("#fbwait").html(0);


  $.ajax({
      type: "POST",
      url: urlUidesk,
      data: "{TrxID:'UideskIndonesia', TrxUserName: '', TrxAction: 'UIDESK201'}",
      contentType: "application/json; charset=utf-8",
      dataType: "json",
      success: function (data) {

          var json = JSON.parse(data.d);
          var i, x, resultSourceEnquiryReason = "";
          console.log(json);
          for (i = 0; i < json.length; i++) {

          
            //LC
            if(json[i].label == "Total" && json[i].type =='Lc'){
              $("#lctotal").html(json[i].Jumlah);
            }else if(json[i].label == "Reply" && json[i].type =='Lc'){
              $("#lcreply").html(json[i].Jumlah);
            }else if(json[i].label == "queue" && json[i].type =='Lc'){
              $("#lcwait").html(json[i].Jumlah);
            }
            //fb
            if(json[i].label == "Total" && json[i].type =='Fb'){
              $("#fbtotal").html(json[i].Jumlah );
            }else if(json[i].label == "Reply" && json[i].type =='Fb'){
              $("#fbreply").html(json[i].Jumlah);
            }else if(json[i].label == "queue" && json[i].type =='Fb'){
              $("#fbwait").html(json[i].Jumlah);
            }
            //Ig
           
            if(json[i].label == "Total" && json[i].type =='Ig'){
              $("#igtotal").html(json[i].Jumlah );
            }else if(json[i].label == "Reply" ){
              $("#igreply").html(json[i].Jumlah);
            }else if(json[i].label == "queue" && json[i].type =='Ig'){
              $("#igwait").html(json[i].Jumlah);
            }
            //Ig
           
            if(json[i].label == "Total" && json[i].Jenis =='Wa'){
              $("#watotal").html(json[i].Jumlah );
            }else if(json[i].label == "Reply" && json[i].type =='Wa'){
              $("#wareply").html(json[i].Jumlah );
            }else if(json[i].label == "queue" && json[i].type =='Wa'){
              $("#wawait").html(json[i].Jumlah);
            }
            
             

        }
        
        

      },
      error: function (xmlHttpRequest, textStatus, errorThrown) {
          console.log(xmlHttpRequest.responseText);
          console.log(textStatus);
          console.log(errorThrown);
      }
  })
}


function storeUserData(fieldName,userData) {
  // Convert the data to a JSON string
  var jsonString = JSON.stringify(userData);

  // Store the data in local storage under the key 'user'
  localStorage.setItem(fieldName, jsonString);

  console.log('Data stored in local storage successfully.');
}

function secondsToHHMMSS(totalSeconds) {
  var hours   = Math.floor(totalSeconds / 3600);
  var minutes = Math.floor((totalSeconds % 3600) / 60);
  var seconds = totalSeconds % 60;

  // Add leading zeros if needed
  hours   = hours.toString().padStart(2, '0');
  minutes = minutes.toString().padStart(2, '0');
  seconds = seconds.toString().padStart(2, '0');

  return hours + ':' + minutes + ':' + seconds;
}
function convertDate(dateString) {
    const milliseconds = parseInt(dateString.replace(/\/Date\((\d+)\)\//, '$1'));
    return new Date(milliseconds);
}
function sortByNumericPropertyAsc(array, propertyName) {
  return array.sort(function (a, b) {
    return b[propertyName] - a[propertyName];
  });
}
function getTopRows(array, n) {
  return array.slice(0, n);
}
function secondsToMinutes(seconds) {
    const minutes = Math.floor(seconds / 60);
    const remainingSeconds =  Math.round(seconds % 60);
    
    const formattedMinutes = String(minutes).padStart(2, '0');
    const formattedSeconds = String(remainingSeconds).padStart(2, '0');
    
    if (isNaN(formattedMinutes)) formattedMinutes = 0;
    if (isNaN(formattedSeconds)) formattedSeconds = 0;

    return `${formattedMinutes}:${formattedSeconds}`;
}

function chartPie(){
  // Retrieve data from local storage under the key 'user'
  var storedDataAUX = localStorage.getItem('DATANOTREADY');
  var storedDataACDIN = localStorage.getItem('DATAACDIN');
  var storedDataREADY = localStorage.getItem('DATAAVAIL');
  var storedDataQUE = localStorage.getItem('DATAQUE');
  var options={
    chart: {
        height: 365,
        type: "pie"
    },
    plotOptions: {
        pie: {
            donut: {
                size: "70%"
            }
        }
    },
    dataLabels: {
        formatter(val, opts) {
            const name = opts.w.globals.labels[opts.seriesIndex]
            const value = opts.w.config.series[opts.seriesIndex]
            return [name, value]
          }
    },
    series: [storedDataQUE, storedDataAUX, storedDataACDIN],
    labels: ["QUE", "AUX", "ACD IN"],
    colors: ["#EB1616", "#C7EB16", "#164FEB"],
    legend: {
        show: false,
        position: "bottom",
        horizontalAlign: "center",
        verticalAlign: "middle",
        floating: !1,
        fontSize: "14px",
        offsetX: 0
    }
  };
  var chart = new ApexCharts(document.querySelector("#chart-donut"), options);
  chart.render();
}


// Function to update chart data
function updateChartData() {
  // Generate new random data
  var newData = [];
  /*for (var i = 0; i < pieData.series.length; i++) {
      newData.push(Math.floor(Math.random() * 100) + 1);
  }*/
  
// Define initial chart data
var storedDataACDIN = parseInt(localStorage.getItem('DATAACDIN'));
var storedDataAUX = parseInt(localStorage.getItem('DATANOTREADY'));
var storedDataREADY = parseInt(localStorage.getItem('DATAAVAIL'));
var pieData = {
  series: [storedDataACDIN, storedDataAUX, storedDataREADY],
  labels: ["ACD IN", "NOT READY", "AVAIL"]
};

// Define chart options
var pieOptions = {
  chart: {
    height: 365,
    type: "pie"
  },
  labels: pieData.labels,
  dataLabels: {
    formatter(val, opts) {
        //const name = opts.w.globals.labels[opts.seriesIndex]
        const value = opts.w.config.series[opts.seriesIndex]
        //return [name, value]
          const name = opts.w.globals.labels[opts.seriesIndex]
            return [name, value]
      }
},
  series: pieData.series,
  colors: ["#309E43", "#F20F3C", "#160FF2"],
    legend: {
        show: false,
        position: "bottom",
        horizontalAlign: "center",
        verticalAlign: "middle",
        floating: !1,
        fontSize: "14px",
        offsetX: 0
    },
  responsive: [{
      breakpoint: 480,
      options: {
          chart: {
              width: 200
          },
          legend: {
              position: 'bottom'
          }
      }
  }]
};


// Create the pie chart
var pieChart = new ApexCharts(document.querySelector('#chart-donut'), pieOptions);

// Render the chart
pieChart.render();
 // var storedDataACDIN = parseInt(localStorage.getItem('DATAACDIN'));
  //var storedDataAUX = parseInt(localStorage.getItem('DATANOTREADY'));
  //var storedDataREADY = parseInt(localStorage.getItem('DATAAVAIL'));
  //var storedDataQUE = parseInt(localStorage.getItem('DATAQUE'));
  
  newData.push(storedDataACDIN);
  newData.push(storedDataAUX);
  newData.push(storedDataREADY);



  console.log(newData);
  // Update chart series with new data
  pieChart.updateSeries(newData);
}



String.prototype.toHHMMSS = function () {
  var sec_num = parseInt(this, 10); // don't forget the second param
  var hours   = Math.floor(sec_num / 3600);
  var minutes = Math.floor((sec_num - (hours * 3600)) / 60);
  var seconds = sec_num - (hours * 3600) - (minutes * 60);

  if (hours   < 10) {hours   = "0"+hours;}
  if (minutes < 10) {minutes = "0"+minutes;}
  if (seconds < 10) {seconds = "0"+seconds;}
  return hours + ':' + minutes + ':' + seconds;
}
function blink(selector){
  $(selector).fadeOut('slow', function(){
      $(this).fadeIn('slow', function(){
          blink(this);
      });
  });
}

function agentList() {
    getDateTime();
   
}

function getDateTime() {
  var today = new Date();
  let hours = today.getHours(); // get hours
  let minutes = today.getMinutes(); // get minutes
  let seconds = today.getSeconds(); //  get seconds
  // add 0 if value < 10; Example: 2 => 02
  if (hours < 10) { hours = "0" + hours; }
  if (minutes < 10) { minutes = "0" + minutes; }
  if (seconds < 10) { seconds = "0" + seconds; }
  var time = hours + ":" + minutes + ":" + seconds;
  var today = new Date();
  var dateNya = today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate();
  //var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
  var dateTime = dateNya + ' ' + time;
  var divTimenya = $('#timeNya');
  var divDateNya = $('#dateNya');

  var months = new Array(12);
  months[0] = "January";
  months[1] = "February";
  months[2] = "March";
  months[3] = "April";
  months[4] = "May";
  months[5] = "June";
  months[6] = "July";
  months[7] = "August";
  months[8] = "September";
  months[9] = "October";
  months[10] = "November";
  months[11] = "December";

  var current_date = new Date();
  current_date.setDate(current_date.getDate() + 0);
  month_value = current_date.getMonth();
  day_value = current_date.getDate();
  year_value = current_date.getFullYear();
  divTimenya.empty();
  divTimenya.append(time);
  divDateNya.empty();
  divDateNya.append(months[month_value] + " " + day_value + ", " + year_value);
}