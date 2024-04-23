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
  console.log("New PROV JS");
  //myVar = setInterval(llll, 3000);
  fetchData();
  
  //myVarX = setInterval(fetchData, 8000);
  //myVarY = setInterval(agentList, 8000);
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

function chartPie(){
  // Retrieve data from local storage under the key 'user'
  var storedDataAUX = localStorage.getItem('DATAAUX');
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


//Function Get Data Asternic
function getDataAsternic(){
  console.log("Hai iwallboard summary call asternic");
}

//End

// Function to update chart data
function updateChartData() {
  // Generate new random data
  var newData = [];
  /*for (var i = 0; i < pieData.series.length; i++) {
      newData.push(Math.floor(Math.random() * 100) + 1);
  }*/
  
// Define initial chart data
var storedDataACDIN = parseInt(localStorage.getItem('DATAACDIN'));
var storedDataAUX = parseInt(localStorage.getItem('DATAAUX'));
var storedDataREADY = parseInt(localStorage.getItem('DATAAVAIL'));
var pieData = {
  series: [storedDataACDIN, storedDataAUX, storedDataREADY],
  labels: ["ACD IN", "NOT READY", "AVAIL"]
};

// Define chart options
var pieOptions = {
  chart: {
    height: 485,
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
function generateDetailsString(data,name) {
  let detailsString = "";
  const filteredPersons = data.filter(item => item.name.toLowerCase().includes(name.toLowerCase()));
       
            data.forEach((person, index) => {
              const firstName = person.name.split(' ')[0];
              if(firstName==name){
                //detailsString += `Person ${index + 1}:\n`;
                //detailsString += `Name: ${person.name}\n`;
                //detailsString += `Local: ${person.local}\n`;
                detailsString += `Call State : ${person.statuscall}\n`;
                //detailsString += `Calls Taken: ${person.callstaken}\n`;
                //detailsString += `Last Call Time: ${person.lastcalltime}\n\n`;
              }
              
            });
        
  

  return detailsString;
}

function getStateAgent(NameAgent){
  //GET Agent State
  var NoUrutanACD=1;
  var name="";
  let detailsString;
  var jqxhr = $.getJSON("BE/r_agent_state.php", function (data) {
 
   
      name = NameAgent;
      detailsString = generateDetailsString(data,name);
      
      //name = NameAgent;
        
    })
    .done(function () {
    //console.log( "done" );
     // Push the new data into the array
     console.log(detailsString);
    })
    .fail(function () {
    //console.log( "error" );
    })
    .always(function () {
    //console.log( "complete" );
    });

  //END
}

function fetchData() {
	getDateTime();
  
    
    //GET DATA AUX  
    let NoUrutan = 0;
    console.log("Hai iwallboard Agent All Monitoring");
    var Abandonrate = 0;
    fetch('https://crm.uidesk.id/roatex/apps/WebServiceGetDataMaster.asmx/UIDESK_TrmMasterCombo?TrxID=ALL&TrxUserName=Hilyatus&TrxAction=UIDESK132')
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


        // Sample JSON data
        const jsonData = [
          // Your JSON data here
        ];

        // Function to group data by agent_name
        const groupDataByAgentName = (data) => {
          const groupedData = {};
          
          // Iterate through each item in the data
          data.forEach(item => {
              const { agent_name, ChannelName } = item;
              
              // If agent_name already exists in groupedData, push ChannelName to its array
              if (groupedData.hasOwnProperty(agent_name)) {
                  groupedData[agent_name].push(ChannelName);
              } else {
                  // Otherwise, create a new array with ChannelName for the agent_name
                  groupedData[agent_name] = [ChannelName];
              }
          });
          
          return groupedData;
        };
        

        // Group the data by agent_name
        const groupedByAgentName = groupDataByAgentName(jsonObject);

        // Output the result
        //console.log(groupedByAgentName);
        const groupAndSetLabel = (data) => {
          const groupedData = {};
      
          // Iterate through each object in the data array
          data.forEach((agent) => {
              const { agent_name, ...rest } = agent;
      
              // If the agent_name doesn't exist in groupedData, create a new key with agent_name as label
              if (!groupedData[agent_name]) {
                  groupedData[agent_name] = {
                      label: agent_name,
                      data: [rest] // Create an array with the agent's data
                  };
              } else {
                  // If the agent_name already exists, push the data to the existing array
                  groupedData[agent_name].data.push(rest);
              }
          });
      
          return groupedData;
      };
      
      // Group the data and set label
      const groupedDataWithLabel = groupAndSetLabel(jsonObject);
      function getAgentNames(data) {
          const agentNames = [];
          for (const agent in data) {
              agentNames.push(data[agent].label);
          }
          return agentNames;
      }
      
      // Get dynamic agent names
      const agentNames = getAgentNames(groupedDataWithLabel);
      // Output the result
      //console.log(agentNames);
      function getAgentDetail(agentName) {
        return groupedByAgentName[agentName];
      }
        var emailNya="";
        var inboundNya="";
        var WANya="";
        $("#listAgent").empty();
        //console.log(groupedByAgentName);
        function getConditionResult(agentName,getNya) {
          const agentDetail = groupedDataWithLabel[agentName];
          if (agentDetail !== undefined && agentDetail.data.length > 0) {
              // Assuming you want the first data's ConditionResult
              return agentDetail.data[getNya].ConditionResult;
          } else {
              return "Agent not found or no data available.";
          }
      }
        $.each(agentNames, function (i, items) {
          
            //console.log(items["AuxUserName"]);
            const agentName = items;
            const agentDetail = getAgentDetail(agentName);
           
            dataChannelDetail="";
            $.each(agentDetail, function (x, xtems) {
              const conditionResult = getConditionResult(agentName,x);
              const agentDetail = getAgentDetail(agentName);
              if (agentDetail[x] !== undefined) {
                emailNya=agentDetail[x];
              } else {
                emailNya="";
              }
               if (emailNya =="E-mail") {
                imageState = "email";
                if (conditionResult == "READYLOGIN") {
                    imageState = imageState+"on";
                } else if (conditionResult == "READYLOGOUT") {
                    imageState = imageState + "off";
                } else if (conditionResult == "OFFLOGOUT") {
                    imageState = imageState + "off";
                } else if (conditionResult == "OFFLOGIN") {
                    imageState = imageState + "max";
                }
            } else if (emailNya == "INBOUND") {
                imageState = "call";
                if (conditionResult == "READYLOGIN") {
                    imageState = imageState + "on";
                } else if (conditionResult == "READYLOGOUT") {
                    imageState = imageState + "off";
                } else if (conditionResult == "OFFLOGOUT") {
                    imageState = imageState + "off";
                } else if (conditionResult == "OFFLOGIN") {
                    imageState = imageState + "max";
                }
            } else if (emailNya == "WhatsApp") {
                imageState = "wa";
                if (conditionResult == "READYLOGIN") {
                    imageState = imageState + "on";
                } else if (conditionResult == "READYLOGOUT") {
                    imageState = imageState + "off";
                } else if (conditionResult == "OFFLOGOUT") {
                    imageState = imageState + "off";
                } else if (conditionResult == "OFFLOGIN") {
                    imageState = imageState + "max";
                }
            }
              dataChannelDetail+='<button type="button" style="background-color:white;" class="btn btn-outline-light"	data-bs-toggle="tooltip" data-bs-placement="top" title="Profile">'+
                            '<!--'+emailNya+'---><img src="icon/state/' + imageState+'.png" width="32" alt=""></button>';
              //console.log("Condition Result for", agentName, "is:", conditionResult);
            });
            
            //getStateAgent(items);
            //document.getElementById("dynamicContainer_"+i).innerHTML=
            //GET Agent State
            var NoUrutanACD=1;
            var name="";
            let detailsString;
            var jqxhr = $.getJSON("BE/r_agent_state.php", function (data) {
          
            
                name = items;
                detailsString = generateDetailsString(data,name);
                
                //name = NameAgent;
                  
              })
              .done(function () {
              //console.log( "done" );
              // Push the new data into the array
              console.log(detailsString);
              $("#listAgent").append('<div class="col-xl-2 col-sm-2">'+
                '<div class="card border shadow-none">'+
                  '<div class="card-body p-4">'+
                    '<div class="d-flex align-items-start">'+
                      '<div class="flex-shrink-0 avatar rounded-circle me-3">'+
                        '<img src="https://crm.uidesk.id/roatex/Images/agent/'+items+'.png" alt="" class="img-fluid rounded-circle">'+
                      '</div>'+
                      '<div class="flex-grow-1 overflow-hidden">'+
                        '<h5 class="font-size-15 mb-1 text-truncate"><a href="pages-profile.html" class="text-dark">'+ items +'</a></h5>'+
                        '<p class="text-muted text-truncate mb-0">'+detailsString+'</p>'+
                      '</div>'+
                      '<div class="flex-shrink-0 dropdown">'+
                        '<a class="text-body dropdown-toggle font-size-16" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true">'+
                          '<i class="icon-xs" data-feather="more-horizontal"></i>'+
                        '</a>'+
                        '<div class="dropdown-menu dropdown-menu-end">'+
                          '<a class="dropdown-item" href="#">Edit</a>'+
                          '<a class="dropdown-item" href="#">Action</a>'+
                          '<a class="dropdown-item" href="#">Remove</a>'+
                        '</div>'+
                      '</div>'+
                    '</div>'+
                  '</div>'+
                  '<div class="btn-group btn-icon" role="group">'+
                    dataChannelDetail+
                  '</div>'+
                '</div>'+
              '</div>');  
              })
              .fail(function () {
              //console.log( "error" );
              })
              .always(function () {
              //console.log( "complete" );
              });

            //END
                
            
              NoUrutan++;
        });
        $('#totalAgent').text(NoUrutan);
    })
    .catch(error => {
        console.error('Error fetching XML data:', error);
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