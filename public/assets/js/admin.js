fetch('../actions/consultation-stats.php')
  .then(res => res.json())
  .then(data => {
    renderChart1(data.byHour);
    renderChart2(data.byWeekday);
    renderChart3(data.byDayOfWeek);
    renderChart4(data.byMonth);
  });

function renderChart1(byHour) {
  const ctx = document.getElementById('chart1').getContext('2d');
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: byHour.labels,
      datasets: [{
        label: 'Appointments',
        data: byHour.data,
        borderColor: 'rgba(75, 192, 192, 1)',
        backgroundColor: 'rgba(75, 192, 192, 0.2)',
        fill: true
      }]
    },
    options: { responsive: true }
  });
}

function renderChart2(byWeekday) {
  const ctx = document.getElementById('chart2').getContext('2d');
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: byWeekday.labels,
      datasets: [{
        label: 'Appointments',
        data: byWeekday.data,
        borderColor: 'rgba(255, 159, 64, 1)',
        backgroundColor: 'rgba(255, 159, 64, 0.2)',
        fill: true
      }]
    },
    options: { responsive: true }
  });
}

function renderChart3(byDayOfWeek) {
  const ctx = document.getElementById('chart3').getContext('2d');
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: byDayOfWeek.labels,
      datasets: [{
        label: 'Appointments',
        data: byDayOfWeek.data,
        backgroundColor: 'rgba(54, 162, 235, 0.5)',
        borderColor: 'rgba(54, 162, 235, 1)',
        borderWidth: 1
      }]
    },
    options: { responsive: true }
  });
}

function renderChart4(byMonth) {
  const ctx = document.getElementById('chart4').getContext('2d');
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: byMonth.labels,
      datasets: [{
        label: 'Appointments',
        data: byMonth.data,
        backgroundColor: 'rgba(153, 102, 255, 0.5)',
        borderColor: 'rgba(153, 102, 255, 1)',
        borderWidth: 1
      }]
    },
    options: { responsive: true }
  });
}
