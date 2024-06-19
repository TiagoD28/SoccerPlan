// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

function number_format(number, decimals, dec_point, thousands_sep) {
  // *     example: number_format(1234.56, 2, ',', ' ');
  // *     return: '1 234,56'
  number = (number + '').replace(',', '').replace(' ', '');
  var n = !isFinite(+number) ? 0 : +number,
    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
    s = '',
    toFixedFix = function(n, prec) {
      var k = Math.pow(10, prec);
      return '' + Math.round(n * k) / k;
    };
  // Fix for IE parseFloat(0.55).toFixed(0) = 0;
  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
  if (s[0].length > 3) {
    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
  }
  if ((s[1] || '').length < prec) {
    s[1] = s[1] || '';
    s[1] += new Array(prec - s[1].length + 1).join('0');
  }
  return s.join(dec);
}

var idClub = document.querySelector('script[src$="chart-bar-demo.js"]').getAttribute('data-id-club');

// Now you can use idClub in your JavaScript code
console.log(idClub);

// Bar Chart Example
var ctx = document.getElementById("myBarChart");
var myBarChart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: [], // Initialize with an empty array
    datasets: [{
      label: "Revenue",
      backgroundColor: "#f50443",
      hoverBackgroundColor: "#e4003d",
      borderColor: "#4e73df",
      data: [],
    }],
  },
  options: {
    maintainAspectRatio: false,
    layout: {
      padding: {
        left: 10,
        right: 25,
        top: 25,
        bottom: 0,
      },
    },
    scales: {
      x: {
        time: {
          unit: 'month',
        },
        grid: {
          display: false,
          drawBorder: false,
        },
        ticks: {
          maxTicksLimit: 6,
        },
        maxBarThickness: 25,
      },
      y: {
        beginAtZero: true,
        ticks: {
          min: 0,
          max: 100,
          maxTicksLimit: 5,
          stepSize: 0,
          precision: 0,
          padding: 10,
          callback: function (value, index, values) {
            return '' + number_format(value, 0);
          },
        },
        grid: {
          color: 'rgb(234, 236, 244)',
          zeroLineColor: 'rgb(234, 236, 244)',
          drawBorder: false,
          borderDash: [2],
          zeroLineBorderDash: [2],
        },
      },
    },
    legend: {
      display: false,
    },
    tooltips: {
      titleMarginBottom: 10,
      titleFontColor: '#6e707e',
      titleFontSize: 14,
      backgroundColor: "rgb(255,255,255)",
      bodyFontColor: "#858796",
      borderColor: '#dddfeb',
      borderWidth: 1,
      xPadding: 15,
      yPadding: 15,
      displayColors: false,
      caretPadding: 10,
      callbacks: {
        label: function(tooltipItem, chart) {
          return 'Points: ' + number_format(tooltipItem.yLabel, 0);
        }
      }
    },
  }
});

document.addEventListener("DOMContentLoaded", function() {
  // Construct the URL with the idClub parameter
  const apiUrl = 'https://esan-tesp-ds-paw.web.ua.pt/tesp-ds-g30/SoccerPlan/api/Teams/index.php';
  const url = new URL(apiUrl);
  url.searchParams.append('route', 'getTeamsPoints');
  url.searchParams.append('idClub', idClub);

  fetch(url, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({ idClub: idClub }),
  })
    .then(response => response.json())
    .then(response => {
      const data = response.data;

      console.log(data);
      if (Array.isArray(data)) {
        // Filter out null entries and map teams and points
        const validData = data.filter(entry => entry !== null);
        const teams = validData.map(entry => entry.nameTeam);
        const points = validData.map(entry => parseInt(entry.points));

        // Log teams and points
        console.log('Teams:', teams);
        console.log('Points:', points);

        // Update myBarChart data
        myBarChart.data.labels = teams;
        myBarChart.data.datasets[0].data = points;

        // Update the chart
        myBarChart.update();
      } else {
        console.error('Error: Data is not an array');
      }
    })
    .catch(error => console.error('Error fetching data:', error));
});
