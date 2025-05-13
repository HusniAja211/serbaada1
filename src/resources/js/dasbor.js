const bulanLabels = keuntunganData.map(item => item.bulan);
const totalKeuntungan = keuntunganData.map(item => item.total);

const ctx = document.getElementById('keuntunganChart').getContext('2d');

new Chart(ctx, {
   type: 'pie',
   data: {
      labels: bulanLabels,
      datasets: [{
         label: 'Total Keuntungan',
         data: totalKeuntungan,
         backgroundColor: [
            'rgba(59, 130, 246, 0.5)',
            'rgba(34, 197, 94, 0.5)',
            'rgba(239, 68, 68, 0.5)',
         ],
         borderColor: [
            'rgba(59, 130, 246, 1)',
            'rgba(34, 197, 94, 1)',
            'rgba(239, 68, 68, 1)',
         ],
         borderWidth: 1
      }]
   },
   options: {
      responsive: true,
      plugins: {
         legend: {
            display: true,
            position: 'top'
         }
      }
   }
});
