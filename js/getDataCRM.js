var myVarX;
var myVarY;


function myFunction() {
  fetchData();
  fetchDataTotalTicket();
  fetchDataIncomingCall();
  fetchDataTotalEmail();
  fetchDataTotalAux();
  fetchDataCountCall();
  fetchDataAverage();
  fetchDataState();
  getSLA();
  //fetchDataComplaint()
  console.log("New PROV JS");
  //myVar = setInterval(llll, 3000);
  //myVarX = setInterval(fetchData, 8000);
  //myVarY = setInterval(agentList, 1000);
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

function addRowSubcategory(noUrut,Val_SubCategory,Val_Total,CategoryTable) {
  // Get the tbody element
  var tbody = document.getElementById(CategoryTable);
  //tbody.innerHTML = ''; // This removes all child nodes
  // Create a new row
  var newRow = document.createElement('tr');
  
  // Create cells for the row
  var cell1 = document.createElement('th');
  var cell2 = document.createElement('td');
  var cell3 = document.createElement('td');
  // Add content to the cells (you can modify this as needed)
  cell1.textContent = noUrut;
  cell2.textContent = Val_SubCategory;
  cell3.textContent = Val_Total;
  // Append cells to the row
  newRow.appendChild(cell1);
  newRow.appendChild(cell2);
  newRow.appendChild(cell3);
  // Append the row to the tbody
  tbody.appendChild(newRow);
}
function fetchData() {
    $("#myTableBodyInquiry").empty();
    $("#myTableBodyComplaint").empty();
    //GET DATA SUBCATEGORY INQUIRY
    let NoUrutan = 1;
    let NoUrutanX = 1;
    console.log("Hai iwallboard Aux Agent");
    var Abandonrate = 0;
    var selectedValue = $("#floatingSelect").val();
    console.log('https://kanmo.uidesk.id/crm/apps/WebServiceGetDataMaster.asmx/UIDESK_TrmMasterCombo?TrxID='+selectedValue+'&TrxUserName=Inquiry&TrxAction=UIDESK132');
    fetch('https://kanmo.uidesk.id/crm/apps/WebServiceGetDataMaster.asmx/UIDESK_TrmMasterCombo?TrxID='+selectedValue+'&TrxUserName=Inquiry&TrxAction=UIDESK132')
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

        $("#listAuxAgent").empty();
        $.each(jsonObject, function (i, items) {
          
            //console.log(items["AuxUserName"]);
              if(items["CategoryName"]==="Complaint"){
                addRowSubcategory(NoUrutanX,items["SubCategory"],items["Jumlah"],'myTableBodyComplaint')
                NoUrutanX++;
              }else{
                addRowSubcategory(NoUrutan,items["SubCategory"],items["Jumlah"],'myTableBodyInquiry')
                NoUrutan++;
              }
              
              
          });
          

    })
    .catch(error => {
        console.error('Error fetching XML data:', error);
    });
  
}

function fetchDataTotalTicket(){
  //var selectedValue = value;
  var selectedValue = $("#floatingSelect").val();
  var TotalTicket;
    $.ajax({
        type: "POST",
        url: "https://kanmo.uidesk.id/crm/apps/WebServiceGetDataMaster.asmx/UIDESK_TrmMasterCombo",
        data: "{TrxID:'"+ selectedValue +"', TrxUserName: 'Complaint', TrxAction: 'UIDESK133'}",
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function (data) {

            var json = JSON.parse(data.d);
            var i, x, resultSourceEnquiryReason = "";
            console.log(json);
            for (i = 0; i < json.length; i++) {

              console.log(json[i].Jenis);
              console.log(json[i].Jumlah);
              if(json[i].Jenis == "TotalTicket"){
                $("#TotalTicket").html(json[i].Jumlah);
              }else if(json[i].Jenis == "InProgress"){
                $("#TotalProgress").html(json[i].Jumlah);
              }else if(json[i].Jenis == "Open"){
                $("#TotalOpen").html(json[i].Jumlah);
              }else if(json[i].Jenis == "Closed"){
                $("#TotalSolved").html(json[i].Jumlah);
               
              }else if(json[i].Jenis == "Pending"){
                $("#TotalPending").html(json[i].Jumlah);
              }
             
            }
             //get sla closed
             
              var slaClosed =parseInt( $("#TotalSolved").html())/parseInt( $("#TotalTicket").html())*100;
              if (isNaN(slaClosed)) slaClosed = 0;
              
              $('#TotalSlaClosed').html((slaClosed)+" %");
          

        },
        error: function (xmlHttpRequest, textStatus, errorThrown) {
            console.log(xmlHttpRequest.responseText);
            console.log(textStatus);
            console.log(errorThrown);
        }
    })
}
//GET DATA EMAIL
function fetchDataTotalEmail(){
  //var selectedValue = value;
  var selectedValue = $("#floatingSelect").val();
    $.ajax({
        type: "POST",
        url: "https://kanmo.uidesk.id/crm/apps/WebServiceGetDataMaster.asmx/UIDESK_TrmMasterCombo",
        data: "{TrxID:'"+ selectedValue +"', TrxUserName: 'Complaint', TrxAction: 'UIDESK134'}",
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function (data) {

            var json = JSON.parse(data.d);
            var i, x, resultSourceEnquiryReason = "";
            console.log(json);
            for (i = 0; i < json.length; i++) {

              console.log(json[i].Jenis);
              console.log(json[i].Jumlah);
              if(json[i].Jenis == "AnsweredEmail"){
                $("#TotalAnsweredEmail").html(json[i].Jumlah);
              }else if(json[i].Jenis == "TotalEmail"){
                $("#TotalIncomingEmail").html(json[i].Jumlah);
              }else if(json[i].Jenis == "QueueEmail"){
                $("#TotalQueEmail").html(json[i].Jumlah);
              }else if(json[i].Jenis == "AbandonEmail"){
                $("#TotalAbnEmail").html(json[i].Jumlah);
              }
              else if(json[i].Jenis == "NotResponseEmail"){
                $("#TotalNotResponseEmail").html(json[i].Jumlah);
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

//GET DATA AUX
function fetchDataTotalAux(){
  //var selectedValue = value;
  var selectedValue = $("#floatingSelect").val();
    $.ajax({
        type: "POST",
        url: "https://kanmo.uidesk.id/crm/apps/WebServiceGetDataMaster.asmx/UIDESK_TrmMasterCombo",
        data: "{TrxID:'"+ selectedValue +"', TrxUserName: 'Complaint', TrxAction: 'UIDESK135'}",
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function (data) {

            var json = JSON.parse(data.d);
            var i, x, resultSourceEnquiryReason = "";
            console.log(json);
            
            var table = '<table class="table table-dark table-striped">';
            // Membuat baris header
            table += '<tr>' +                                               
            '<th scope="col">Break Down AUX</th>' +
            '<th scope="col">Login Time</th>' +
            '<th scope="col">Handle Market Place</th>' +
            '<th scope="col">Follow Up Email</th>' +
            '<th scope="col">Training</th>' +
            '<th scope="col">Prayer</th>' +
            '<th scope="col">Lunch</th>' +
            '<th scope="col">Toilet</th>' +
            '<th scope="col">Ready Time</th>' +
            '<th scope="col">Hold Time</th>' +
            '</tr>';
            // Loop melalui setiap objek dalam respons dan menambahkan baris untuk setiap objek
            for (i = 0; i < json.length; i++) {

             
                table += '<tr>';
                table += '<td>' + json[i].AuxUserName + '</td>';
                table += '<td>' + json[i].LoginTime + '</td>';
                table += '<td>' + json[i].HandleMarket + '</td>';
                table += '<td>' + json[i].FollowUp + '</td>';
                table += '<td>' + json[i].Training + '</td>';
                table += '<td>' + json[i].Prayer + '</td>';
                table += '<td>' + json[i].Lunch + '</td>';
                table += '<td>' + json[i].Toilet + '</td>';
                table += '<td>' + json[i].Toilet + '</td>';
                table += '<td>' + json[i].Toilet + '</td>';
                table += '</tr>';

            }
            // Menutup tabel HTML
            table += '</table>';
            
            // Menempatkan tabel ke dalam elemen dengan ID "table-container"
            $('#table-container').html(table);

        },
        error: function (xmlHttpRequest, textStatus, errorThrown) {
            console.log(xmlHttpRequest.responseText);
            console.log(textStatus);
            console.log(errorThrown);
        }
    })
}

function fetchDataAverage(){
  var jqxhr = $.getJSON("BE/r_dashboard.php", function (data) {
    console.log("Hai iwallboard dashboard");
    
    //Get Data Detail
    console.log(data["DataDetail"]);
    $.each(data["DataDetail"], function (i, items) {
        
          
          $("#avgtalking").html(items['Average_Inbound_Call_Duration'] +' Secs');
          $("#avgmaxwaiting").html(items['Total_Inbound_Calls'] +' Secs');

    })
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
  // Perform other work here ...
  // Set another completion function for the request above
  jqxhr.always(function () {
    //console.log( "second complete" );
  });
 
}

function fetchDataState(){
  var jqxhr = $.getJSON("BE/r_agent_state.php", function (data) {
    console.log("Hai iwallboard dashboard agent state");
    
    //Get Data Detail
    console.log(data["DataDetail"]);
    $.each(data["DataDetail"], function (i, items) {
      $("#stateready").html(items['READY']);
      $("#statetalking").html(items['ACD-IN']);
      $("#stateaux").html(items['UNAVAILABLE']);   
         
    })
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
  // Perform other work here ...
  // Set another completion function for the request above
  jqxhr.always(function () {
    //console.log( "second complete" );
  });
 
}

function fetchDataIncomingCall(){
  //var selectedValue = value;
  var selectedValue = $("#floatingSelect").val();
  var jqxhr = $.getJSON("BE/getsummary.php", function (data) {
    console.log("Hai iwallboard missed");
    
    //Get Data Detail
    console.log(data["DataDetail"]);
    
    $.each(data["DataDetail"], function (i, items) {
        console.log(items.TypeNya);
        if(items.TypeNya == "InboundMissed"){
          $("#TotalMissed").html(items.Jumlah);
        }

    })
  })


  
  var jqxhr = $.getJSON("BE/getssh_state.php", function (data) {
    $.each(data["DataDetail"], function (i, items) {
      console.log("getssh_state here...");
        console.log(items['ACD-IN']);
        console.log(items['QUE']);
      
        //$('#acdin').html(items['ACD-IN']);
        
        if(items['QUE']>0){
          $('#TotalQueue').html("<font style='color: red;  color='red'>"+items['QUE']+"</font>");
        }else{
          $('#TotalQueue').html(items['QUE']);
        }
        
       
        

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

  //get END

  var jqxhr = $.getJSON("BE/r_incoming.php", function (data) {
  
    $.each(data["DataDetail"], function (i, items) {
      console.log("Hai iwallboard summary call asternic");
        console.log(items['Total_Inbound_Calls']);
        console.log(items['Total_Complete_Calls']);
        $('#TotalOutgoing').html(items['Total_Outbound_Calls']);
        $('#TotalIncoming').html(items['Total_Inbound_Calls']);
        $("#avgwaiting").html(items['Average_Inbound_Ring_Duration'] +' Secs');
        $('#TotalAnswered').html(items['Total_Complete_Calls']);
        $('#TotalAbandon').html("<font style='color: red;  color='red'>"+parseInt(items['Total_Missed_Calls']*1)+"</font>");
      
        //$('#callabdn').html(items['early abandoned'][day]);

        var slRate = 0;
        var answerRate = 0;
        var abnRate = 0;
        var missRate = 0;
        var valSL=0;
        var valAR=0;
        var valABANR=0;
        var valMR=0;
        slRate=(parseInt(items['Total_Complete_Calls'])/parseInt(items['Total_Inbound_Calls']))*100
        answerRate=(parseInt(items['Total_Complete_Calls']-items['Total_Missed_Calls'])/parseInt(items['Total_Inbound_Calls']))*100
        abnRate=(parseInt(items['Total_Missed_Calls'])/parseInt(items['Total_Inbound_Calls']))*100
        missRate=(parseInt(items['Total_Missed_Calls'])/parseInt(items['Total_Inbound_Calls']))*100

        valSL=Math.ceil(slRate * 100) / 100;
        valAR=Math.ceil(answerRate * 100) / 100;
        valABANR=Math.ceil(abnRate * 100) / 100;
        valMR=Math.ceil(missRate * 100) / 100;

        if (valSL === Infinity) {
          valSL = 0;
        }else{
          valSL=Math.ceil(slRate * 100) / 100;
        }
        if (valAR === Infinity) {
          valAR = 0;
        }else{
          valAR=Math.ceil(answerRate * 100) / 100;
        }
        if (valABANR === Infinity) {
          valABANR = 0;
        }else{
          valABANR=Math.ceil(abnRate * 100) / 100;
        }
        if (valMR === Infinity) {
          valMR = 0;
        }else{
          valMR=Math.ceil(missRate * 100) / 100;
        }

        if (isNaN(valAR)) valAR = 0;
        if (isNaN(valABANR)) valABANR = 0;
        if (isNaN(valMR)) valMR = 0;

        $('#SLA').html(valSL);
        $('#AR').html(valAR+"%");
        $('#ABANR').html(valABANR+"%");
        $('#MR').html(valMR+"%");
         
       
        

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
    // Perform other work here ...
    // Set another completion function for the request above
    jqxhr.always(function () {
      //console.log( "second complete" );
    });
}
//Get Count Agent
function fetchDataCountCall(){
  //var selectedValue = value;
  var selectedValue = $("#floatingSelect").val();
  var jqxhr = $.getJSON("BE/getcountstatus.php", function (data) {
    console.log("Hai iwallboard Count");
    
    //Get Data Detail
    console.log(data["DataDetail"]);
    var table = '<table class="table table-info table-striped">';
            // Membuat baris header
    table += '<tr>' +                                               
        '<th scope="col">Agent Name</th>' +
        '<th scope="col">Incoming calls</th>' +
        '<th scope="col">Outgoing Calls</th>' +
        '<th scope="col">Waiting Time</th>' +
        '<th scope="col">Talking Time</th>' +
        '<th scope="col">Status</th>' +
        '</tr>';
    $.each(data["DataDetail"], function (i, items) {
      table += '<tr>';
      table += '<td>' + items.AgentName + '</td>';
      table += '<td>' + items.IncomingCall + '</td>';
      table += '<td>' + items.OutgoingCall + '</td>';
      table += '<td>' + items.WaitingTime + '</td>';
      table += '<td>' + items.TalkingTime + '</td>';
      table += '<td>' + items.StatusAgent + '</td>';
      table += '</tr>';

    })
    // Menutup tabel HTML
    table += '</table>';
                                         
    // Menempatkan tabel ke dalam elemen dengan ID "table-container"
    $('#table-count').html(table);
  })
}

function getSLA(){
  console.log("GET SLA");
 
  var Abandonrate = 0;
  var currentDate = new Date();

  // Get the current date components
  var year = currentDate.getFullYear(); // Get the full year (e.g., 2024)
  var month = currentDate.getMonth() + 1; // Get the month (0-11, add 1 to get the actual month number)
  //var day = 22; // Get the day of the month (1-31)
  var day = currentDate.getDate(); 
  var slRate = 0;
  var answerRate = 0;
  var abnRate = 0;
  var missRate = 0;
 
  var jqxhr = $.getJSON("BE/getsummary_v2.php", function (data) {
    console.log("DataDetail");
    console.log(data["DataDetail"]);
    $.each(data["DataDetail"], function (i, items) {
        console.log(items['Total Call'][day]);
        console.log(items['Call Answered'][day]);
        
        slRate=(parseInt(items['Call Answered'][day])/parseInt(items['Total Call'][day]))*100
        answerRate=(parseInt(items['Call Answered'][day])/parseInt(items['Total Call'][day]))*100
        abnRate=(parseInt(items['early abandoned'][day])/parseInt(items['Total Call'][day]))*100
        missRate=(parseInt(items['Abnd. Queue'][day])/parseInt(items['Total Call'][day]))*100
      
        //$('#SLA').html(Math.ceil(slRate * 100) / 100);
        //$('#calltotal').html(items['Total Call'][day]); Math.ceil(data * 100) / 100;
        
        /*var valAR=0;
        var valABANR=0;
        var valMR=0;
        valAR=Math.ceil(answerRate * 100) / 100;
        valABANR=Math.ceil(abnRate * 100) / 100;
        valMR=Math.ceil(missRate * 100) / 100;

        if (valAR === Infinity) {
          valAR = 0;
        }else{
          valAR=Math.ceil(answerRate * 100) / 100;
        }
        if (valABANR === Infinity) {
          valABANR = 0;
        }else{
          valABANR=Math.ceil(abnRate * 100) / 100;
        }
        if (valMR === Infinity) {
          valMR = 0;
        }else{
          valMR=Math.ceil(missRate * 100) / 100;
        }

        $('#AR').html(valAR);
        $('#ABANR').html(valABANR);
        $('#MR').html(valMR);*/
        //$('#abnivr').html(items['ivr terminated'][day]);
        //const seconds = items['Average Handling Time (AHT)'][day];
        //const formattedTime = secondsToMinutes(seconds);
        //$('#ahtcallanswer').html(formattedTime);
        //$('#callabdn').html(items['early abandoned'][day]);
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
    // Perform other work here ...
  
    // Set another completion function for the request above
    jqxhr.always(function () {
      //console.log( "second complete" );
    });
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
  //var selectedValue = value;
  
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