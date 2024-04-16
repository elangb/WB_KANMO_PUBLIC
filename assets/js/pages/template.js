
  var optionsCircle = {
    chart: {
      type: 'radialBar',
      width: 360
     
    },
    plotOptions: {
      radialBar: {
        size: undefined,
        inverseOrder: false,
        hollow: {
          margin: 5,
          size: '48%',
          background: 'transparent',
        },
        track: {
          show: true,
          background: '#40475D',
          strokeWidth: '10%',
          opacity: 1,
          margin: 4, // margin is in pixels
        },
  
  
      },
    },
    series: [71, 63],
    labels: ['SL', 'ABN'],
    legend: {
      show: true,
      position: 'left',
      offsetX: -20,
      offsetY: 30,
      formatter: function (val, opts) {
        return val + " - " + opts.w.globals.series[opts.seriesIndex] + '%'
      }
    },
    fill: {
      type: 'gradient',
      gradient: {
        shade: 'dark',
        type: 'horizontal',
        shadeIntensity: 0.5,
        inverseColors: true,
        opacityFrom: 1,
        opacityTo: 1,
        stops: [0, 100]
      }
    }
  }
  
  var chartCircle = new ApexCharts(document.querySelector('#gradient_chart'), optionsCircle);
  chartCircle.render();
  

  var optionsProgress1 = {
    chart: {
      height: 70,
      type: "bar",
      stacked: true,
      sparkline: {
        enabled: true
      }
    },
    plotOptions: {
      bar: {
        horizontal: true,
        barHeight: "20%",
        colors: {
          backgroundBarColors: ["#40475D"]
        }
      }
    },
    colors: ["green"],
    stroke: {
      width: 0
    },
    series: [
      {
        name: "Agent Login",
        data: [40]
      }
    ],
    title: {
      floating: true,
      offsetX: -10,
      offsetY: 5,
      text: "Agent Login"
    },
    subtitle: {
      floating: true,
      align: "right",
      offsetY: 0,
      text: "40",
      style: {
        fontSize: "20px"
      }
    },
    tooltip: {
      enabled: false
    },
    xaxis: {
      categories: ["Agent Not Login"]
    },
    yaxis: {
      max: 100
    },
    fill: {
      type: "gradient",
      gradient: {
        inverseColors: false,
        gradientToColors: ["green"]
      }
    }
  };
  
  var chartProgress1 = new ApexCharts(
    document.querySelector("#progress1"),
    optionsProgress1
  );
  chartProgress1.render();




  var optionsProgress2 = {
    chart: {
      height: 70,
      type: "bar",
      stacked: true,
      sparkline: {
        enabled: true
      }
    },
    plotOptions: {
      bar: {
        horizontal: true,
        barHeight: "20%",
        colors: {
          backgroundBarColors: ["#40475D"]
        }
      }
    },
    colors: ["red"],
    stroke: {
      width: 0
    },
    series: [
      {
        name: "Agent Not Login",
        data: [80]
      }
    ],
    title: {
      floating: true,
      offsetX: -10,
      offsetY: 5,
      text: "Agent Not Login"
    },
    subtitle: {
      floating: true,
      align: "right",
      offsetY: 0,
      text: "80",
      style: {
        fontSize: "20px"
      }
    },
    tooltip: {
      enabled: false
    },
    xaxis: {
      categories: ["Agent Not Login"]
    },
    yaxis: {
      max: 100
    },
    fill: {
      type: "gradient",
      gradient: {
        inverseColors: false,
        gradientToColors: ["red"]
      }
    }
  };
  
  var chartProgress2 = new ApexCharts(
    document.querySelector("#progress2"),
    optionsProgress2
  );
  chartProgress2.render();