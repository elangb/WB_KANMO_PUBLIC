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

function myFunction() {
  console.log("New CUST JS");
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
function sortByNumericPropertyAsc(array, propertyName) {
  return array.sort(function (a, b) {
    return b[propertyName] - a[propertyName];
  });
}
function getTopRows(array, n) {
  return array.slice(0, n);
}
function fetchData() {
  var jqxhr = $.getJSON("getData.php", function (data) {
    console.log("Hai iwallboard");
    
    //Get Data Detail
    console.log(data["DataDetail"]);
    //End Get Data Detail
    $("#listAuxAgent").empty();
    
    $('#table-skill-split tbody').html('');
    let service_level = 0;
    let abandon = 0;
    let call_receive = 0;
    let que = 0;
    let talking = 0;
    let rateABD = 0;
    let notReady = 0;
    let answer = 0;
    let SL = 0;
    let totalRow = 0;
    let NoUrutan = 1;
    let JumlahTotalCallAnswered = 0;
   

    // Initialize a counter for AVAIL occurrences
    var availCount = 0;
    var auxCount = 0;
    var acdIN = 0;
    var agentOutboundStaffed = 0;
    $.each(sortByNumericPropertyAsc(data["DataDetail"],'Time'), function (i, items) {
      if (items["Split Skill"]  === "INOUT CUST SMG") {
        if (items["StateAgent"]  === "AVAIL") {
          availCount++;
        }
        if (items["StateAgent"]  === "AUX") {
          auxCount++;
        }
        if (items["StateAgent"]  === "ACD") {
          if (items["Direction"]  === "IN") {
            acdIN++;
          }
        }

        if (items["StateAgent"]  === "AUX") {
          if (items["AUX Reason"]  === "Isi Foam Discharge" || items["AUX Reason"]  === "Outgoing Call Adminis" || items["AUX Reason"]  === "Eskalasi Monitoring") {
            agentOutboundStaffed++;
          }
        }
        

      }
    });
    console.log("adada " +agentOutboundStaffed);
    $('#avail').html(availCount);
    $('#auxagent').html(auxCount);
    $('#acdin').html(acdIN);
    $('#staffedoutbound').html(agentOutboundStaffed);
    $('#calloutbound').html(0);
        
    $.each(getTopRows(sortByNumericPropertyAsc(data["DataDetail"],'Time'), 100), function (i, items) {
      console.log(items["Login ID"]);
        if(NoUrutan <= 5){
          if (items["Split Skill"]  === "INOUT CUST SMG") {
              if (items["AUX Reason"] === "Istirahat Makan" || items["AUX Reason"] === "Rest Room Toilet" || items["AUX Reason"] === "Sholat") {
                $("#listAuxAgent").append('<li class="px-4 py-2">'+
                  '<div class="d-flex align-items-center">'+
                      '<div class="flex-shrink-0 me-3">'+
                          '<div class="avatar-sm">'+
                              '<div class="avatar-title bg-light text-primary rounded-circle">'+
                              NoUrutan+
                              '</div>'+
                          '</div>'+
                      '</div>'+
                      '<div class="flex-grow-1 overflow-hidden">'+
                          '<p class="text-muted mb-1 text-truncate">'+ items["Login ID"] +'</p>'+
                          '<div class="badge badge-soft-success ms-2">'+ items["AUX Reason"] +'</div>'+
                      '</div>'+
                      '<div class="flex-shrink-0 align-self-start">'+
                          '<h6>'+ secondsToHHMMSS(items["Time"]) +'<i class="uil uil-arrow-up-right text-success ms-1"></i></h6>'+
                      '</div>'+
                  '</div>'+
              '</li>');
              NoUrutan++;
            }
        }
		  }
    });
   
    $('#service_level').val(service_level/(totalRow+1))
    .trigger('change');
    $('#abandon').val(abandon/(totalRow+1))
    .trigger('change');
    /*$('#call_receive').val(call_receive)
    .trigger('change');*/

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
  var jqxhr = $.getJSON("getData.php", function (data) {
			  	 	  
    $('#1_TampungListAgent').html('')
    $.each(data, function (i, item) {
      /*
		Code 1 System Aux
		Code 2 Istirahat
		Code 3 Eskalasi
		Code 4 Briefing
		Code 5 Outgoing call
		Code 6 Restroom
		Code 7 Sholat
		//Code 8 Isi form
		Code 8 System Error
		Code 0 System Aux
		*/
    //GET DATA WB Header
    //Code Data calloutbound staffedoutbound auxagent waiting acdin avail callabdn callanswer
    /*
    0: " ACD Calls " 
    1: " csplit INQUEUE "
    2: " Agents Staffed "
    3: " Agents in AUX "
    4: " % Within Service Level "
    5: " % Aban Calls "
    6: " Outbound Aban Calls "
    7: " Aban Calls"
    */
      console.log("Provider " + item["INOUT PROV SMG"]);
      console.log("Customer " + item["INOUT CUST SMG"]);
      //console.log(item["INOUT PROV SMG"][2]);
      //console.log(item["INOUT CUST SMG"][2]);
      
      
      
      
      $('#staffed').html(item["INOUT CUST SMG"][2]);
      $('#servicelevel').html(item["INOUT CUST SMG"][4]);
      
      $('#callabdn').html(item["INOUT CUST SMG"][7]);
      $('#callanswer').html(item["INOUT CUST SMG"][0]);
      if(item["INOUT CUST SMG"][1]==="0"){
        document.getElementById("waitingImg").src = "icon/Waiting1.png"; 
        $('#waiting').html(item["INOUT CUST SMG"][1]);
      }else{
        document.getElementById("waitingImg").src = "icon/Waiting2.png"; 
        $('#waiting').html("<font color='red' id='elem'>"+item["INOUT CUST SMG"][1]+"</font>");
        $("#waiting").blink(500);
      }
      
    //End GET DATA Header
    
	  //alert(item[4])
	  var auxReason="";
	  if(item[4]=="0"){
		auxReason = "System Aux";
	  }else if(item[4]=="1" || item[4]=="System Aux"){
		auxReason = "System Aux";
	  }else if(item[4]=="2" || item[4]=="Istirahat"){
		auxReason = "Istirahat";
	  }else if(item[4]=="3" || item[4]=="Eskalasi"){
		auxReason = "Eskalasi";    
	  }else if(item[4]=="4" || item[4]=="Briefing"){
		auxReason = "Briefing";    
	  }else if(item[4]=="5" || item[4]=="Outgoing Call"){
		auxReason = "Outgoing Call";    
	  }else if(item[4]=="6" || item[4]=="Restroom"){
		auxReason = "Rest Room";    
	  }else if(item[4]=="7" || item[4]=="Sholat"){
		auxReason = "Sholat";    
	  }else if(item[4]=="8" || item[4]=="System Error"){
		auxReason = "System Error";    
	  }else if(item[4]=="9" || item[4]=="Isi form"){
		auxReason = "Isi form";    
	  }else{
		  auxReason = "";
	  }
	  
      $('#1_TampungListAgent').append('<a href="#" class="list-item">' +
        '<div class="list-info">' +
        '<img src="Foto/' + item[2] + '.jpg" width="46px" class="img-thumbnail">' +
        '</div>' +
        '<div class="list-text">' +
        '<span style="font-size:18px;" class="list-text-name">' + item[1] + ' - ' + item[2] + '</span>' +
        '<div style="font-size:15px; color:rgb(255, 255, 255);" class="list-text-info">' +
        '<i class="icon-circle"></i>' +
        item[5] + ' - ' + auxReason  + ' - ' + String(item[7]).toHHMMSS() +
        '</div>' +
        '</div>' +
        '</a>')
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