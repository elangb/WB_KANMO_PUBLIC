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

$(document).ready(function(){
    $("#submitData").click(function(){
        // Your data to be sent in the POST request
        
        // jQuery POST request
        /*$.post("https://crm.uidesk.id/roatex/apps/WebServiceGetDataMaster.asmx/UIDESK_TrmMasterCombo?TrxID=2024-03-04&TrxUserName=2024-03-04&TrxAction=UIDESK128", postData, function(data, status){
            // Handle the response here
            console.log("Data: " + ("#date1").val() + "\nStatus: " + status);
        });*/
        console.log("date1: " + $("#date1").val().replace('T', ' ') + "\ndate2: " + $("#date2").val().replace('T', ' '));
        fetchData($("#date1").val().replace('T', ' '),$("#date2").val().replace('T', ' '));
    });
});

function myFunction() {
  console.log("New PROV JS");
  //myVar = setInterval(llll, 3000);
  myVarX = setInterval(fetchData, 1000);
  myVarY = setInterval(agentList, 1000);
  //calloutbound staffedoutbound auxagent waiting acdin avail callabdn callanswer
  $('#calloutbound').html('0');
  $('#staffedoutbound').html('0');
  $('#auxagent').html('0');
  $('#waiting').html('0');
  $('#acdin').html('0');
  $('#avail').html('0');
  $('#callabdn').html('0');
  $('#callanswer').html('0');
  //myVarY = setInterval(getRedirect, 10000);
  
}
function getRedirect() {
  console.log("getRedirect");
  //window.location.replace("outbound.html");
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
function convertIntToTime(seconds) {
    var hours = Math.floor(seconds / 3600);
    var minutes = Math.floor((seconds % 3600) / 60);
    seconds = seconds % 60;

    // Add leading zeros if necessary
    hours = (hours < 10 ? '0' : '') + hours;
    minutes = (minutes < 10 ? '0' : '') + minutes;
    seconds = (seconds < 10 ? '0' : '') + seconds;

    return hours + ':' + minutes + ':' + seconds;
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


function fetchData(dateAwal,dateAkhir) {
    
    //Call Pie
    //updateChartData();
    //End


    
    //GET DATA Channel
    let NoUrutan = 1;
    console.log("Hai iwallboard Aux Agent");
    var Abandonrate = 0;
    fetch('https://crm.uidesk.id/roatex/apps/WebServiceGetDataMaster.asmx/UIDESK_TrmMasterCombo?TrxID='+ dateAwal +'&TrxUserName='+ dateAkhir +'&TrxAction=UIDESK128')
    .then(response => response.text())
    .then(xmlString => {
        // Parse the XML string into an XMLDocument
        const parser = new DOMParser();
        const xmlDoc = parser.parseFromString(xmlString, 'text/xml');

        // Use the xmlDoc as needed
        console.log(xmlDoc);

        const parserX = new DOMParser();
        const xmlDocX = parserX.parseFromString(xmlDoc, "text/xml");
        const jsonString = xmlDoc.getElementsByTagName("string")[0].textContent;

        // Parse the JSON string into a JavaScript object
        const jsonObject = JSON.parse(jsonString);

        console.log(jsonObject);
        let TotalClosedSolvedCall=0;
        let TotalOverSLACall=0;
        let TotalClosedSolvedEmail=0;
        let TotalOverSLAEmail=0;
        let TotalClosedSolvedWA=0;
        let TotalOverSLAWA=0;
        let WhatsappIncoming=0;
        let WhatsappIncomingFRT=0;
        let WhatsappIncomingAHT=0;
        let EmailIncoming=0;
        let EmailIncomingFRT=0;
        let EmailIncomingAHT=0;
        $("#listAuxAgent").empty();
        $.each(jsonObject, function (i, items) {
            console.log(items["LabelApp"]);   
            if(items["LabelApp"]=="TotalClosedSolvedCall"){
                TotalClosedSolvedCall =items["Jumlah"];
            }else if(items["LabelApp"]=="TotalOverSLACall"){
                TotalOverSLACall =items["Jumlah"];
            } else if(items["LabelApp"]=="TotalClosedSolvedEmail"){
                TotalClosedSolvedEmail =items["Jumlah"];
            } else if(items["LabelApp"]=="TotalOverSLAEmail"){
                TotalOverSLAEmail =items["Jumlah"];
            } else if(items["LabelApp"]=="TotalClosedSolvedWA"){
                TotalClosedSolvedWA =items["Jumlah"];
            } else if(items["LabelApp"]=="TotalOverSLAWA"){
                TotalOverSLAWA =items["Jumlah"];
            } else if(items["LabelApp"]=="WhatsappIncomingFRT"){
                WhatsappIncoming =items["Jumlah"];
                WhatsappIncomingFRT =items["FRT"];
                WhatsappIncomingAHT =items["AHT"];
            } else if(items["LabelApp"]=="EmailIncomingFRT"){
                EmailIncoming =items["Jumlah"];
                EmailIncomingFRT =items["FRT"];
                EmailIncomingAHT =items["AHT"];
            } 
            $('#totalcallsolvedclosed').html(TotalClosedSolvedCall); 
            $('#totalcalloversla').html(TotalOverSLACall); 
            $('#totalemail').html(EmailIncoming);  
            $('#totalemailsolvedclosed').html(TotalClosedSolvedEmail);  
            $('#totalemailoversla').html(TotalOverSLAEmail);  
            $('#totalemailaht').html(EmailIncomingAHT);  
            $('#totalwa').html(WhatsappIncoming);  
            $('#totalwasolvedclosed').html(TotalClosedSolvedWA);  
            $('#totalwaoversla').html(TotalOverSLAWA);  
            $('#totalemailfrt').html(EmailIncomingFRT);  
            $('#totalwafrt').html(WhatsappIncomingFRT);  
            $('#totalwaaht').html(WhatsappIncomingAHT);  
            //$('#totalcall').html(callTotal);  
        });
        

    })
    .catch(error => {
        console.error('Error fetching XML data:', error);
    });


  //Get Data Category Email
    let NoUrutanEmail = 1;
    console.log("Hai iwallboard Aux Agent");
    var Abandonrate = 0;
    fetch('https://crm.uidesk.id/roatex/apps/WebServiceGetDataMaster.asmx/UIDESK_TrmMasterCombo?TrxID='+ dateAwal +'&TrxUserName='+ dateAkhir +'&TrxAction=UIDESK129')
    .then(response => response.text())
    .then(xmlString => {
        // Parse the XML string into an XMLDocument
        const parser = new DOMParser();
        const xmlDoc = parser.parseFromString(xmlString, 'text/xml');

        // Use the xmlDoc as needed
        console.log(xmlDoc);

        const parserX = new DOMParser();
        const xmlDocX = parserX.parseFromString(xmlDoc, "text/xml");
        const jsonString = xmlDoc.getElementsByTagName("string")[0].textContent;

        // Parse the JSON string into a JavaScript object
        const jsonObject = JSON.parse(jsonString);

        console.log(jsonObject);
        let TotalClosedSolvedCall=0;
        let TotalOverSLACall=0;
        let TotalClosedSolvedEmail=0;
        let TotalOverSLAEmail=0;
        let TotalClosedSolvedWA=0;
        let TotalOverSLAWA=0;
        let WhatsappIncoming=0;
        let WhatsappIncomingFRT=0;
        let EmailIncoming=0;
        let EmailIncomingFRT=0;
        $("#listEmail").empty();
        $.each(jsonObject, function (i, items) {
            console.log(items["LabelApp"]);   
            $("#listEmail").append('<li class="list-group-item">' +
                '<div class="d-flex align-items-center">' +
                    '<div class="flex-grow-1">' +
                        '<small class="text-muted">'+ NoUrutanEmail +'. '+ items["UserAgent"] +' </small>' +
                    '</div>' +
                    '<div class="flex-shrink-0 align-self-end">' +
                        '<div class="badge badge-soft-success ms-2">'+ items["CreateTicket"] +' <i class="uil uil-arrow-up-right text-success ms-1"></i></div>' +
                    '</div>' +
                '</div>' +
            '</li>');
            NoUrutanEmail++;
            //$('#totalcall').html(callTotal);  
        });
        

    })
    .catch(error => {
        console.error('Error fetching XML data:', error);
    });

    //Get Data Category Call
    let NoUrutanCall = 1;
    console.log("Hai iwallboard Aux Agent");
    var Abandonrate = 0;
    fetch('https://crm.uidesk.id/roatex/apps/WebServiceGetDataMaster.asmx/UIDESK_TrmMasterCombo?TrxID='+ dateAwal +'&TrxUserName='+ dateAkhir +'&TrxAction=UIDESK130')
    .then(response => response.text())
    .then(xmlString => {
        // Parse the XML string into an XMLDocument
        const parser = new DOMParser();
        const xmlDoc = parser.parseFromString(xmlString, 'text/xml');

        // Use the xmlDoc as needed
        console.log(xmlDoc);

        const parserX = new DOMParser();
        const xmlDocX = parserX.parseFromString(xmlDoc, "text/xml");
        const jsonString = xmlDoc.getElementsByTagName("string")[0].textContent;

        // Parse the JSON string into a JavaScript object
        const jsonObject = JSON.parse(jsonString);

        console.log(jsonObject);
        let TotalClosedSolvedCall=0;
        let TotalOverSLACall=0;
        let TotalClosedSolvedEmail=0;
        let TotalOverSLAEmail=0;
        let TotalClosedSolvedWA=0;
        let TotalOverSLAWA=0;
        let WhatsappIncoming=0;
        let WhatsappIncomingFRT=0;
        let EmailIncoming=0;
        let EmailIncomingFRT=0;
        $("#listCall").empty();
        $.each(jsonObject, function (i, items) {
            console.log(items["LabelApp"]);   
            
            $("#listCall").append('<li class="list-group-item">' +
                '<div class="d-flex align-items-center">' +
                    '<div class="flex-grow-1">' +
                        '<small class="text-muted">'+ NoUrutanCall +'. '+ items["UserAgent"] +' </small>' +
                    '</div>' +
                    '<div class="flex-shrink-0 align-self-end">' +
                        '<div class="badge badge-soft-success ms-2">'+ items["CreateTicket"] +' <i class="uil uil-arrow-up-right text-success ms-1"></i></div>' +
                    '</div>' +
                '</div>' +
            '</li>');
            NoUrutanCall++;
        });
        

    })
    .catch(error => {
        console.error('Error fetching XML data:', error);
    });

    //Get Data Category Whatsapp
    let NoUrutanWhatsapp = 1;
    console.log("Hai iwallboard Aux Agent");
    var Abandonrate = 0;
    fetch('https://crm.uidesk.id/roatex/apps/WebServiceGetDataMaster.asmx/UIDESK_TrmMasterCombo?TrxID='+ dateAwal +'&TrxUserName='+ dateAkhir +'&TrxAction=UIDESK131')
    .then(response => response.text())
    .then(xmlString => {
        // Parse the XML string into an XMLDocument
        const parser = new DOMParser();
        const xmlDoc = parser.parseFromString(xmlString, 'text/xml');

        // Use the xmlDoc as needed
        console.log(xmlDoc);

        const parserX = new DOMParser();
        const xmlDocX = parserX.parseFromString(xmlDoc, "text/xml");
        const jsonString = xmlDoc.getElementsByTagName("string")[0].textContent;

        // Parse the JSON string into a JavaScript object
        const jsonObject = JSON.parse(jsonString);

        console.log(jsonObject);
        let TotalClosedSolvedCall=0;
        let TotalOverSLACall=0;
        let TotalClosedSolvedEmail=0;
        let TotalOverSLAEmail=0;
        let TotalClosedSolvedWA=0;
        let TotalOverSLAWA=0;
        let WhatsappIncoming=0;
        let WhatsappIncomingFRT=0;
        let EmailIncoming=0;
        let EmailIncomingFRT=0;
        $("#listWhatsapp").empty();
        $.each(jsonObject, function (i, items) {
            console.log(items["LabelApp"]);   
            $("#listWhatsapp").append('<li class="list-group-item">' +
                '<div class="d-flex align-items-center">' +
                    '<div class="flex-grow-1">' +
                        '<small class="text-muted">'+ NoUrutanWhatsapp +'. '+ items["UserAgent"] +' </small>' +
                    '</div>' +
                    '<div class="flex-shrink-0 align-self-end">' +
                        '<div class="badge badge-soft-success ms-2">'+ items["CreateTicket"] +' <i class="uil uil-arrow-up-right text-success ms-1"></i></div>' +
                    '</div>' +
                '</div>' +
            '</li>');
            NoUrutanWhatsapp++;
            //$('#totalcall').html(callTotal);  
        });
        

    })
    .catch(error => {
        console.error('Error fetching XML data:', error);
    });

  //Get data Other Channel
  function getJumlahChannel(channelName, jsonObject) {
    let jumlah = 0;
    for (let i = 0; i < jsonData.length; i++) {
        if (jsonData[i].Channel === channelName) {
            jumlah += jsonData[i].Jumlah;
        }
    }
    return jumlah;
}
let callTotal=0;
let callAnswered=0;
let callUnAnswered=0;
let callSL=0;
let callAvg=0;
let callAht=0;
  fetch('https://crm.uidesk.id/roatex/apps/WebServiceGetDataMaster.asmx/UIDESK_TrmMasterCombo?TrxID='+ dateAwal +'&TrxUserName='+ dateAkhir +'&TrxAction=UIDESK127')
    .then(response => response.text())
    .then(xmlString => {
        // Parse the XML string into an XMLDocument
        const parser = new DOMParser();
        const xmlDoc = parser.parseFromString(xmlString, 'text/xml');

        // Use the xmlDoc as needed
        console.log(xmlDoc);

        const parserX = new DOMParser();
        const xmlDocX = parserX.parseFromString(xmlDoc, "text/xml");
        const jsonString = xmlDoc.getElementsByTagName("string")[0].textContent;

        // Parse the JSON string into a JavaScript object
        console.log("Get Data Call!");
        const jsonObject = JSON.parse(jsonString);

        console.log(jsonObject);

        
        $.each(jsonObject, function (i, items) {
            console.log(items["lastapp"]);   
            if(items["lastapp"]=="Total Call"){
                callTotal =items["total_data"];
            }else if(items["lastapp"]=="Call Answered"){
                callAnswered =items["total_data"];
            } else if(items["lastapp"]=="Abnd. Queue"){
                callUnAnswered =items["total_data"];
            } 
            else if(items["lastapp"]=="AvgRinging"){
                callAvg =items["total_data"];
             } 

            callSL =Math.round((callAnswered-callUnAnswered)/(callTotal)*100,2);
            //$datas['Service Level'][$i] = round((($datas['Call Answered Within'][$i] > 0)? (($datas['Call Answered Within'][$i]-$datas['Abnd. Ringing'][$i]) / $datas['Total Call'][$i]) : 0), 2)*100;
            $('#totalcall').html(callTotal);  
            $('#totalcallanswered').html(callAnswered); 
            $('#totalcallunanswered').html(callUnAnswered);
            $('#totalsl').html(callSL+' %');
            var _callAvg = convertIntToTime(callAvg);
            var timeParts = _callAvg.split(':');
            var formattedTime = timeParts[0] + ':' + timeParts[1] + ':' + timeParts[2].split('.')[0];
            $('#totalcallfrt').html(formattedTime);
            callAht  = Math.round((callTotal/callAnswered)*100,2);
            //var _callAht = convertIntToTime(callAht);
            $('#totalcallaht').html(callAht);
            

        });

        
        
        // Get the number of occurrences of the "Whatsapp" channel
        

       

    })
    .catch(error => {
        console.error('Error fetching XML data:', error);
    });


  //End
  

  
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