import ApexCharts from 'apexcharts';

try {
  window.ApexCharts = ApexCharts;
} catch (e) {
  console.log(e);
}

export { ApexCharts };
