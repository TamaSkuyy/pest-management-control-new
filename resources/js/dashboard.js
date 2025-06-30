'use strict';

// import { event } from 'jquery';
// Import Swiper JS dan CSS
import Swiper from 'swiper/bundle';
// import 'swiper/swiper-bundle.css';

// Dashboard Enhancement Functions
const DashboardEnhancement = {
  // Show loading spinner on chart containers
  showChartLoading: function(chartElement) {
    if (chartElement) {
      chartElement.classList.add('chart-loading');
      const spinner = document.createElement('div');
      spinner.className = 'chart-spinner';
      spinner.innerHTML = '<div class="spinner-border spinner-border-custom" role="status"><span class="visually-hidden">Loading...</span></div>';
      chartElement.appendChild(spinner);
    }
  },

  // Hide loading spinner
  hideChartLoading: function(chartElement) {
    if (chartElement) {
      chartElement.classList.remove('chart-loading');
      const spinner = chartElement.querySelector('.chart-spinner');
      if (spinner) {
        spinner.remove();
      }
    }
  },

  // Add fade-in animation to charts
  addChartAnimation: function(chartElement) {
    if (chartElement) {
      chartElement.classList.add('chart-fade-in');
    }
  },

  // Show success notification
  showNotification: function(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
      ${message}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
      if (notification && notification.parentNode) {
        notification.remove();
      }
    }, 5000);
  },

  // Initialize tooltips
  initializeTooltips: function() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl);
    });
  }
};

(function () {
  // Initialize dashboard enhancements
  DashboardEnhancement.initializeTooltips();

  const swiperWithPagination = document.querySelector('#swiper-with-pagination');
  let galleryInstance;

  // With pagination
  // --------------------------------------------------------------------
  if (swiperWithPagination) {
    new Swiper(swiperWithPagination, {
      slidesPerView: 1,
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev'
      },
      pagination: {
        el: '.swiper-pagination',
        clickable: true
      },
      loop: true,
      touchEventsTarget: 'wrapper',
      preventInteractionOnTransition: true,
      simulateTouch: false
    });
  }

  let cardColor, headingColor, labelColor, borderColor, legendColor;

  cardColor = config.colors.cardColor;
  headingColor = config.colors.headingColor;
  labelColor = config.colors.textMuted;
  legendColor = config.colors.bodyColor;
  borderColor = config.colors.borderColor;

  // Color constant
  const chartColors = {
    column: {
      series1: '#826af9',
      series2: '#d2b0ff',
      bg: '#f8d3ff'
    },
    donut: {
      series1: '#fee802',
      series2: '#3fd0bd',
      series3: '#826bf8',
      series4: '#2b9bf4'
    },
    area: {
      series1: '#696cff',
      series2: '#94a3fd',
      series3: '#c3c9ff'
    }
  };

  // Heat chart data generator
  function generateDataHeat(count, yrange) {
    let i = 0;
    let series = [];
    while (i < count) {
      let x = 'w' + (i + 1).toString();
      let y = Math.floor(Math.random() * (yrange.max - yrange.min + 1)) + yrange.min;

      series.push({
        x: x,
        y: y
      });
      i++;
    }
    return series;
  }

  // Line Area Chart
  // --------------------------------------------------------------------
  const areaChartEl = document.querySelector('#lineAreaChart'),
    areaChartConfig = {
      chart: {
        height: 400,
        type: 'area',
        parentHeightOffset: 0,
        toolbar: {
          show: true,
          tools: {
            download: true,
            selection: true,
            zoom: true,
            zoomin: true,
            zoomout: true,
            pan: true,
            reset: true
          }
        },
        events: {
          // Chart events can be added here if needed
        }
      },
      dataLabels: {
        enabled: false
      },
      stroke: {
        show: false,
        curve: 'straight'
      },
      legend: {
        show: true,
        position: 'bottom',
        horizontalAlign: 'start',
        labels: {
          colors: legendColor,
          useSeriesColors: false
        }
      },
      grid: {
        borderColor: borderColor,
        xaxis: {
          lines: {
            show: true
          }
        }
      },
      colors: [chartColors.area.series3, chartColors.area.series2, chartColors.area.series1],
      series: [],
      xaxis: {
        axisBorder: {
          show: false
        },
        axisTicks: {
          show: false
        },
        labels: {
          style: {
            colors: labelColor,
            fontSize: '13px'
          }
        }
      },
      yaxis: {
        labels: {
          style: {
            colors: labelColor,
            fontSize: '13px'
          }
        }
      },
      fill: {
        opacity: 1,
        type: 'solid'
      }
    };

  if (typeof areaChartEl !== 'undefined' && areaChartEl !== null) {
    const areaChart = new ApexCharts(areaChartEl, areaChartConfig);
    areaChart.render();

    $.getJSON('/dashboard/areachart')
      .done(function (response) {
        areaChart.updateOptions({
          xaxis: {
            categories: response.categories,
            labels: {
              style: {
                colors: labelColor,
                fontSize: '13px'
              }
            }
          },
          series: response.data
        });
      })
      .fail(function (jqxhr, textStatus, error) {
        console.error('Request Failed: ' + textStatus + ', ' + error);
      });
  }

  // Line Chart
  // --------------------------------------------------------------------
  const lineChartEl = document.querySelector('#lineChart'),
    lineChartConfig = {
      chart: {
        height: 400,
        type: 'line',
        parentHeightOffset: 0,
        zoom: {
          enabled: false
        },
        toolbar: {
          show: true
        },
        events: {
          click: function (event, chartContext, config) {
            const dataPointIndex = config.dataPointIndex;
            const seriesIndex = config.seriesIndex;

            if (dataPointIndex !== -1) {
              // Calculate actual month and period from bi-weekly index
              const monthIndex = Math.floor(dataPointIndex / 2) + 1;
              const period = (dataPointIndex % 2) + 1;
              
              const monthNames = [
                '', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
              ];
              const periodText = period === 1 ? ' (Minggu 1-2)' : ' (Minggu 3-4)';
              
              document.getElementById('bulanInspeksi').innerHTML = monthNames[monthIndex] + periodText;

              if ($.fn.DataTable.isDataTable('#inspeksiTable')) {
                $('#inspeksiTable').DataTable().destroy();
              }

              var tahun = document.getElementById('yearFilterLineChart').value;
              document.getElementById('tahunInspeksi').innerHTML = tahun;
              
              // Set method name if a specific series was clicked
              if (seriesIndex >= 0 && chartContext.w.globals.seriesNames[seriesIndex]) {
                document.getElementById('namaMetodeInspeksi').innerHTML = '- Metode ' + chartContext.w.globals.seriesNames[seriesIndex] + ' - ';
              } else {
                document.getElementById('namaMetodeInspeksi').innerHTML = '';
              }

              //tabel inspeksi
              $('#inspeksiTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                  url: route('inspeksi.dataperbulan'),
                  type: 'GET',
                  data: {
                    metode_id: seriesIndex >= 0 ? (seriesIndex + 1) : null, // Assuming metode IDs start from 1
                    bulan: monthIndex,
                    tahun: tahun,
                    period: period
                  },
                  error: function (xhr) {
                    if (xhr.status === 401) {
                      window.location.href = '/login';
                    }
                  }
                },
                columns: [
                  {
                    data: 'id'
                  },
                  {
                    data: 'tanggal'
                  },
                  {
                    data: 'metode.metode_nama',
                    render: function (data, type, row) {
                      return row.metode ? row.metode.metode_nama : '';
                    }
                  },
                  {
                    data: 'lokasi.lokasi_nama',
                    render: function (data, type, row) {
                      return row.lokasi ? row.lokasi.lokasi_nama : '';
                    }
                  },
                  {
                    data: 'hama.hama_nama',
                    render: function (data, type, row) {
                      return row.hama ? row.hama.hama_nama : '-';
                    }
                  },
                  {
                    data: 'pegawai'
                  },
                  {
                    data: 'jumlah'
                  }
                ],
                columnDefs: [
                  {
                    // For Responsive
                    className: 'control',
                    orderable: false,
                    searchable: false,
                    responsivePriority: 2,
                    targets: 0,
                    render: function (data, type, full, meta) {
                      return '';
                    }
                  }
                ],
                order: [[0, 'desc']],
                dom: '<"card-header flex-column flex-md-row"<"head-label text-center"><"dt-action-buttons text-end pt-3 pt-md-0"B>><"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                displayLength: 10,
                lengthMenu: [10, 25, 50, 75, 100],
                buttons: [
                  {
                    extend: 'collection',
                    className: 'btn btn-label-primary dropdown-toggle me-2',
                    text: '<i class="bx bx-export me-sm-1"></i> <span class="d-none d-sm-inline-block">Export</span>',
                    buttons: [
                      {
                        extend: 'print',
                        text: '<i class="bx bx-printer me-1" ></i>Print',
                        className: 'dropdown-item',
                        title:
                          'Data Inspeksi Bulanan ' +
                          document.getElementById('bulanInspeksi').textContent +
                          ' ' +
                          new Date().getFullYear(),
                        exportOptions: {
                          columns: [1, 2, 3, 4, 5, 6],
                          // prevent avatar to be display
                          format: {
                            body: function (inner) {
                              if (inner.length <= 0) return inner;
                              if (typeof inner === 'number') return inner;
                              var el = $.parseHTML(inner);
                              var result = '';
                              $.each(el, function (_, item) {
                                if (item.classList !== undefined && item.classList.contains('user-name')) {
                                  result = result + item.lastChild.firstChild.textContent;
                                } else if (item.innerText === undefined) {
                                  result = result + item.textContent;
                                } else result = result + item.innerText;
                              });
                              return result;
                            }
                          }
                        },
                        customize: function (win) {
                          //customize print view for dark
                          $(win.document.body)
                            .css('color', config.colors.headingColor)
                            .css('border-color', config.colors.borderColor)
                            .css('background-color', config.colors.bodyBg);
                          $(win.document.body)
                            .find('table')
                            .addClass('compact')
                            .css('color', 'inherit')
                            .css('border-color', 'inherit')
                            .css('background-color', 'inherit');
                        }
                      },
                      {
                        extend: 'csv',
                        text: '<i class="bx bx-file me-1" ></i>Csv',
                        className: 'dropdown-item',
                        title:
                          'Data Inspeksi Bulanan ' +
                          document.getElementById('bulanInspeksi').textContent +
                          ' ' +
                          new Date().getFullYear(),
                        exportOptions: {
                          columns: [1, 2, 3, 4, 5, 6],
                          // prevent avatar to be display
                          format: {
                            body: function (inner, coldex, rowdex) {
                              if (inner.length <= 0) return inner;
                              if (typeof inner === 'number') return inner;
                              var el = $.parseHTML(inner);
                              var result = '';
                              $.each(el, function (_, item) {
                                if (item.classList !== undefined && item.classList.contains('user-name')) {
                                  result = result + item.lastChild.firstChild.textContent;
                                } else if (item.innerText === undefined) {
                                  result = result + item.textContent;
                                } else result = result + item.innerText;
                              });
                              return result;
                            }
                          }
                        }
                      },
                      {
                        extend: 'excel',
                        text: '<i class="bx bxs-file-export me-1"></i>Excel',
                        className: 'dropdown-item',
                        title:
                          'Data Inspeksi Bulanan ' +
                          document.getElementById('bulanInspeksi').textContent +
                          ' ' +
                          new Date().getFullYear(),
                        exportOptions: {
                          columns: [1, 2, 3, 4, 5, 6],
                          // prevent avatar to be display
                          format: {
                            body: function (inner, coldex, rowdex) {
                              if (inner.length <= 0) return inner;
                              if (typeof inner === 'number') return inner;
                              var el = $.parseHTML(inner);
                              var result = '';
                              $.each(el, function (_, item) {
                                if (item.classList !== undefined && item.classList.contains('user-name')) {
                                  result = result + item.lastChild.firstChild.textContent;
                                } else if (item.innerText === undefined) {
                                  result = result + item.textContent;
                                } else result = result + item.innerText;
                              });
                              return result;
                            }
                          }
                        }
                      },
                      {
                        extend: 'pdf',
                        text: '<i class="bx bxs-file-pdf me-1"></i>Pdf',
                        className: 'dropdown-item',
                        title:
                          'Data Inspeksi Bulanan ' +
                          document.getElementById('bulanInspeksi').textContent +
                          ' ' +
                          new Date().getFullYear(),
                        exportOptions: {
                          columns: [1, 2, 3, 4, 5, 6],
                          // prevent avatar to be display
                          format: {
                            body: function (inner, coldex, rowdex) {
                              if (inner.length <= 0) return inner;
                              if (typeof inner === 'number') return inner;
                              var el = $.parseHTML(inner);
                              var result = '';
                              $.each(el, function (_, item) {
                                if (item.classList !== undefined && item.classList.contains('user-name')) {
                                  result = result + item.lastChild.firstChild.textContent;
                                } else if (item.innerText === undefined) {
                                  result = result + item.textContent;
                                } else result = result + item.innerText;
                              });
                              return result;
                            }
                          }
                        }
                      },
                      {
                        extend: 'copy',
                        text: '<i class="bx bx-copy me-1" ></i>Copy',
                        className: 'dropdown-item',
                        title:
                          'Data Inspeksi Bulanan ' +
                          document.getElementById('bulanInspeksi').textContent +
                          ' ' +
                          new Date().getFullYear(),
                        exportOptions: {
                          columns: [1, 2, 3, 4, 5, 6],
                          // prevent avatar to be display
                          format: {
                            body: function (inner, coldex, rowdex) {
                              if (inner.length <= 0) return inner;
                              if (typeof inner === 'number') return inner;
                              var el = $.parseHTML(inner);
                              var result = '';
                              $.each(el, function (_, item) {
                                if (item.classList !== undefined && item.classList.contains('user-name')) {
                                  result = result + item.lastChild.firstChild.textContent;
                                } else if (item.innerText === undefined) {
                                  result = result + item.textContent;
                                } else result = result + item.innerText;
                              });
                              return result;
                            }
                          }
                        }
                      }
                    ]
                  }
                ],
                responsive: {
                  details: {
                    display: $.fn.dataTable.Responsive.display.modal({
                      header: function (row) {
                        var data = row.data();
                        return 'Details of ' + data['full_name'];
                      }
                    }),
                    type: 'column',
                    renderer: function (api, rowIdx, columns) {
                      var data = $.map(columns, function (col) {
                        return col.title !== '' // ? Do not show row in modal popup if title is blank (for check box)
                          ? '<tr data-dt-row="' +
                              col.rowIndex +
                              '" data-dt-column="' +
                              col.columnIndex +
                              '">' +
                              '<td>' +
                              col.title +
                              ':' +
                              '</td> ' +
                              '<td>' +
                              col.data +
                              '</td>' +
                              '</tr>'
                          : '';
                      }).join('');

                      return data ? $('<table class="table"/><tbody />').append(data) : false;
                    }
                  }
                }
              });
              $('#inspectionModal').modal('show');
            }
          }
        }
      },
      series: [],
      markers: {
        strokeWidth: 7,
        strokeOpacity: 1,
        strokeColors: [cardColor],
        colors: [config.colors.warning]
      },
      dataLabels: {
        enabled: false
      },
      stroke: {
        width: 3,
        curve: 'smooth'
      },
      colors: [config.colors.primary, config.colors.info, config.colors.warning, config.colors.success, config.colors.danger],
      grid: {
        borderColor: borderColor,
        xaxis: {
          lines: {
            show: true
          }
        },
        padding: {
          top: -20
        }
      },
      legend: {
        show: true,
        position: 'bottom',
        horizontalAlign: 'center'
      },
      tooltip: {
        custom: function ({ series, seriesIndex, dataPointIndex, w }) {
          const categoryIndex = dataPointIndex;
          const monthIndex = Math.floor(categoryIndex / 2);
          const period = (categoryIndex % 2) + 1;
          const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
          const monthName = monthNames[monthIndex];
          const periodText = period === 1 ? 'Minggu 1-2' : 'Minggu 3-4';
          
          return `
            <div class="px-3 py-2">
              <div class="fw-bold">${w.config.series[seriesIndex].name}</div>
              <div>${monthName} ${periodText}</div>
              <div class="text-primary fw-bold">${series[seriesIndex][dataPointIndex]} inspeksi</div>
            </div>
          `;
        }
      },
      xaxis: {
        categories: [],
        axisBorder: {
          show: false
        },
        axisTicks: {
          show: false
        },
        labels: {
          style: {
            colors: labelColor,
            fontSize: '13px'
          }
        }
      },
      yaxis: {
        labels: {
          style: {
            colors: labelColor,
            fontSize: '13px'
          }
        }
      }
    };

  if (typeof lineChartEl !== undefined && lineChartEl !== null) {
    const lineChart = new ApexCharts(lineChartEl, lineChartConfig);
    lineChart.render();
    window.lineChart = lineChart;

    // Function to load line chart data
    function loadLineChartData(year = null) {
      const lineChartContainer = document.querySelector('#lineChart');
      if (lineChartContainer) {
        lineChartContainer.classList.add('chart-loading');
        
        if (!lineChartContainer.querySelector('.chart-spinner')) {
          const spinner = document.createElement('div');
          spinner.className = 'chart-spinner';
          spinner.innerHTML = '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>';
          lineChartContainer.appendChild(spinner);
        } else {
          lineChartContainer.querySelector('.chart-spinner').style.display = 'flex';
        }
      }
      
      const url = year ? `/dashboard/linechart/${year}` : '/dashboard/linechart';
      
      fetch(url)
        .then(response => response.json())
        .then(data => {
          // Store metode IDs for click handling
          window.lineChartData = {
            metodeIds: data.data.map((item, index) => {
              return index + 1; // Assuming metode IDs are sequential starting from 1
            })
          };

          window.lineChart.updateOptions({
            series: data.data,
            xaxis: {
              categories: data.categories,
              labels: {
                style: {
                  colors: labelColor,
                  fontSize: '13px'
                }
              }
            }
          });
          
          // Hide loading animation
          if (lineChartContainer) {
            setTimeout(() => {
              lineChartContainer.classList.remove('chart-loading');
              const spinner = lineChartContainer.querySelector('.chart-spinner');
              if (spinner) {
                spinner.style.display = 'none';
              }
            }, 500);
          }
        })
        .catch(error => {
          console.error('Error loading line chart data:', error);
          if (lineChartContainer) {
            lineChartContainer.classList.remove('chart-loading');
            const spinner = lineChartContainer.querySelector('.chart-spinner');
            if (spinner) {
              spinner.style.display = 'none';
            }
          }
        });
    }

    // Load initial data
    loadLineChartData();

    // Year filter change handler
    const yearFilter = document.getElementById('yearFilterLineChart');
    if (yearFilter) {
      yearFilter.addEventListener('change', function() {
        loadLineChartData(this.value);
      });
    }
  }

  // Horizontal Bar Chart
  // --------------------------------------------------------------------
  const horizontalBarChartEl = document.querySelector('#horizontalBarChart'),
    horizontalBarChartConfig = {
      chart: {
        height: 400,
        type: 'bar',
        toolbar: {
          show: false
        }
      },
      plotOptions: {
        bar: {
          horizontal: true,
          barHeight: '30%',
          startingShape: 'rounded',
          borderRadius: 8
        }
      },
      grid: {
        borderColor: borderColor,
        xaxis: {
          lines: {
            show: false
          }
        },
        padding: {
          top: -20,
          bottom: -12
        }
      },
      colors: config.colors.info,
      dataLabels: {
        enabled: false
      },
      series: [
        {
          data: [700, 350, 480, 600, 210, 550, 150]
        }
      ],
      xaxis: {
        categories: ['MON, 11', 'THU, 14', 'FRI, 15', 'MON, 18', 'WED, 20', 'FRI, 21', 'MON, 23'],
        axisBorder: {
          show: false
        },
        axisTicks: {
          show: false
        },
        labels: {
          style: {
            colors: labelColor,
            fontSize: '13px'
          }
        }
      },
      yaxis: {
        labels: {
          style: {
            colors: labelColor,
            fontSize: '13px'
          }
        }
      }
    };
  if (typeof horizontalBarChartEl !== undefined && horizontalBarChartEl !== null) {
    const horizontalBarChart = new ApexCharts(horizontalBarChartEl, horizontalBarChartConfig);
    horizontalBarChart.render();
  }

  // Donut Chart
  // --------------------------------------------------------------------
  const donutChartEl = document.querySelector('#donutChart'),
    donutChartConfig = {
      chart: {
        height: 390,
        type: 'donut',
        events: {
          dataPointSelection: function (event, chartContext, config) {
            const dataPointIndex = config.dataPointIndex;
            const metodeId = config.w.config.metodeIds[dataPointIndex];
            const metodeNama = config.w.config.labels[dataPointIndex];

            $('#namaMetode').text(metodeNama);

            if (dataPointIndex !== -1) {
              if ($.fn.DataTable.isDataTable('#inspeksiMetodeTable')) {
                $('#inspeksiMetodeTable').DataTable().destroy();
              }

              var tahun = document.getElementById('yearFilterDonutChart').value;
              var bulan = document.getElementById('monthFilterDonutChart').value;

              //tabel inspeksi
              $('#inspeksiMetodeTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                  url: route('inspeksi.datapermetode'),
                  type: 'GET',
                  data: {
                    metode_id: metodeId,
                    bulan: bulan,
                    tahun: tahun
                  },
                  error: function (xhr) {
                    if (xhr.status === 401) {
                      window.location.href = '/login';
                    }
                  }
                },
                columns: [
                  {
                    data: 'id'
                  },
                  {
                    data: 'tanggal'
                  },
                  {
                    data: 'metode.metode_nama',
                    render: function (_, __, row) {
                      return row.metode ? row.metode.metode_nama : '';
                    }
                  },
                  {
                    data: 'lokasi.lokasi_nama',
                    render: function (data, type, row) {
                      return row.lokasi ? row.lokasi.lokasi_nama : '';
                    }
                  },
                  {
                    data: 'hama.hama_nama',
                    render: function (data, type, row) {
                      return row.hama ? row.hama.hama_nama : '-';
                    }
                  },
                  {
                    data: 'pegawai'
                  },
                  {
                    data: 'jumlah'
                  }
                ],
                columnDefs: [
                  {
                    // For Responsive
                    className: 'control',
                    orderable: false,
                    searchable: false,
                    responsivePriority: 2,
                    targets: 0,
                    render: function () {
                      return '';
                    }
                  }
                ],
                order: [[0, 'desc']],
                dom: '<"card-header flex-column flex-md-row"<"head-label text-center"><"dt-action-buttons text-end pt-3 pt-md-0"B>><"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                displayLength: 10,
                lengthMenu: [10, 25, 50, 75, 100],
                buttons: [
                  {
                    extend: 'collection',
                    className: 'btn btn-label-primary dropdown-toggle me-2',
                    text: '<i class="bx bx-export me-sm-1"></i> <span class="d-none d-sm-inline-block">Export</span>',
                    buttons: [
                      {
                        extend: 'print',
                        text: '<i class="bx bx-printer me-1" ></i>Print',
                        className: 'dropdown-item',
                        title: 'Data Inspeksi Metode ' + metodeNama + ' ' + tahun,
                        exportOptions: {
                          columns: [1, 2, 3, 4, 5, 6],
                          // prevent avatar to be display
                          format: {
                            body: function (inner, coldex, rowdex) {
                              if (inner.length <= 0) return inner;
                              if (typeof inner === 'number') return inner;
                              var el = $.parseHTML(inner);
                              var result = '';
                              $.each(el, function (_, item) {
                                if (item.classList !== undefined && item.classList.contains('user-name')) {
                                  result = result + item.lastChild.firstChild.textContent;
                                } else if (item.innerText === undefined) {
                                  result = result + item.textContent;
                                } else result = result + item.innerText;
                              });
                              return result;
                            }
                          }
                        },
                        customize: function (win) {
                          //customize print view for dark
                          $(win.document.body)
                            .css('color', config.colors.headingColor)
                            .css('border-color', config.colors.borderColor)
                            .css('background-color', config.colors.bodyBg);
                          $(win.document.body)
                            .find('table')
                            .addClass('compact')
                            .css('color', 'inherit')
                            .css('border-color', 'inherit')
                            .css('background-color', 'inherit');
                        }
                      },
                      {
                        extend: 'csv',
                        text: '<i class="bx bx-file me-1" ></i>Csv',
                        className: 'dropdown-item',
                        title: 'Data Inspeksi Metode ' + metodeNama + ' ' + tahun,
                        exportOptions: {
                          columns: [1, 2, 3, 4, 5, 6],
                          // prevent avatar to be display
                          format: {
                            body: function (inner, coldex, rowdex) {
                              if (inner.length <= 0) return inner;
                              if (typeof inner === 'number') return inner;
                              var el = $.parseHTML(inner);
                              var result = '';
                              $.each(el, function (_, item) {
                                if (item.classList !== undefined && item.classList.contains('user-name')) {
                                  result = result + item.lastChild.firstChild.textContent;
                                } else if (item.innerText === undefined) {
                                  result = result + item.textContent;
                                } else result = result + item.innerText;
                              });
                              return result;
                            }
                          }
                        }
                      },
                      {
                        extend: 'excel',
                        text: '<i class="bx bxs-file-export me-1"></i>Excel',
                        className: 'dropdown-item',
                        title: 'Data Inspeksi Metode ' + metodeNama + ' ' + tahun,
                        exportOptions: {
                          columns: [1, 2, 3, 4, 5, 6],
                          // prevent avatar to be display
                          format: {
                            body: function (inner, coldex, rowdex) {
                              if (inner.length <= 0) return inner;
                              if (typeof inner === 'number') return inner;
                              var el = $.parseHTML(inner);
                              var result = '';
                              $.each(el, function (_, item) {
                                if (item.classList !== undefined && item.classList.contains('user-name')) {
                                  result = result + item.lastChild.firstChild.textContent;
                                } else if (item.innerText === undefined) {
                                  result = result + item.textContent;
                                } else result = result + item.innerText;
                              });
                              return result;
                            }
                          }
                        }
                      },
                      {
                        extend: 'pdf',
                        text: '<i class="bx bxs-file-pdf me-1"></i>Pdf',
                        className: 'dropdown-item',
                        title: 'Data Inspeksi Metode ' + metodeNama + ' ' + tahun,
                        exportOptions: {
                          columns: [1, 2, 3, 4, 5, 6],
                          // prevent avatar to be display
                          format: {
                            body: function (inner, coldex, rowdex) {
                              if (inner.length <= 0) return inner;
                              if (typeof inner === 'number') return inner;
                              var el = $.parseHTML(inner);
                              var result = '';
                              $.each(el, function (_, item) {
                                if (item.classList !== undefined && item.classList.contains('user-name')) {
                                  result = result + item.lastChild.firstChild.textContent;
                                } else if (item.innerText === undefined) {
                                  result = result + item.textContent;
                                } else result = result + item.innerText;
                              });
                              return result;
                            }
                          }
                        }
                      },
                      {
                        extend: 'copy',
                        text: '<i class="bx bx-copy me-1" ></i>Copy',
                        className: 'dropdown-item',
                        title: 'Data Inspeksi Metode ' + metodeNama + ' ' + tahun,
                        exportOptions: {
                          columns: [1, 2, 3, 4, 5, 6],
                          // prevent avatar to be display
                          format: {
                            body: function (inner, coldex, rowdex) {
                              if (inner.length <= 0) return inner;
                              if (typeof inner === 'number') return inner;
                              var el = $.parseHTML(inner);
                              var result = '';
                              $.each(el, function (_, item) {
                                if (item.classList !== undefined && item.classList.contains('user-name')) {
                                  result = result + item.lastChild.firstChild.textContent;
                                } else if (item.innerText === undefined) {
                                  result = result + item.textContent;
                                } else result = result + item.innerText;
                              });
                              return result;
                            }
                          }
                        }
                      }
                    ]
                  }
                ],
                responsive: {
                  details: {
                    display: $.fn.dataTable.Responsive.display.modal({
                      header: function (row) {
                        var data = row.data();
                        return 'Details of ' + data['full_name'];
                      }
                    }),
                    type: 'column',
                    renderer: function (api, rowIdx, columns) {
                      var data = $.map(columns, function (col, i) {
                        return col.title !== '' // ? Do not show row in modal popup if title is blank (for check box)
                          ? '<tr data-dt-row="' +
                              col.rowIndex +
                              '" data-dt-column="' +
                              col.columnIndex +
                              '">' +
                              '<td>' +
                              col.title +
                              ':' +
                              '</td> ' +
                              '<td>' +
                              col.data +
                              '</td>' +
                              '</tr>'
                          : '';
                      }).join('');

                      return data ? $('<table class="table"/><tbody />').append(data) : false;
                    }
                  }
                }
              });
              $('#inspectionMetodeModal').modal('show');
            }
          }
        }
      },
      labels: [],
      series: [],
      colors: [],
      stroke: {
        show: false,
        curve: 'straight'
      },
      dataLabels: {
        enabled: true,
        formatter: function (val) {
          return val.toFixed(1) + '%';
        },
        style: {
          fontSize: '14px',
          fontFamily: 'Public Sans',
          fontWeight: 500,
          colors: ['#fff']
        }
      },
      legend: {
        show: true,
        position: 'bottom',
        markers: { offsetX: -3 },
        itemMargin: {
          vertical: 3,
          horizontal: 10
        },
        labels: {
          colors: legendColor,
          useSeriesColors: false
        }
      },
      plotOptions: {
        pie: {
          donut: {
            labels: {
              show: true,
              name: {
                fontSize: '2rem',
                fontFamily: 'Public Sans'
              },
              value: {
                fontSize: '1.2rem',
                color: legendColor,
                fontFamily: 'Public Sans',
                formatter: function (val) {
                  return parseInt(val);
                }
              },
              total: {
                show: true,
                fontSize: '1.5rem',
                color: headingColor,
                label: 'Total Hama',
                formatter: function (w) {
                  return w.globals.seriesTotals.reduce((a, b) => {
                    return a + b;
                  }, 0);
                }
              }
            }
          }
        }
      },
      tooltip: {
        custom: function ({ series, seriesIndex, dataPointIndex, w }) {
          const hamaName = w.globals.labels[seriesIndex];
          const value = series[seriesIndex];
          const percentage = ((value / w.globals.seriesTotals.reduce((a, b) => a + b, 0)) * 100).toFixed(1);
          
          return `
            <div class="tooltip-custom">
              <div class="tooltip-header">${hamaName}</div>
              <div class="tooltip-body">
                <div class="tooltip-item">
                  <span class="tooltip-label">Jumlah:</span>
                  <span class="tooltip-value">${value}</span>
                </div>
                <div class="tooltip-item">
                  <span class="tooltip-label">Persentase:</span>
                  <span class="tooltip-value">${percentage}%</span>
                </div>
              </div>
            </div>
          `;
        }
      },
      responsive: [
        {
          breakpoint: 992,
          options: {
            chart: {
              height: 380
            }
          }
        },
        {
          breakpoint: 576,
          options: {
            chart: {
              height: 320
            }
          }
        },
        {
          breakpoint: 420,
          options: {
            chart: {
              height: 280
            },
            legend: {
              show: false
            }
          }
        }
      ]
    };

  // if (typeof donutChartHamaEl !== undefined && donutChartHamaEl !== null) {
  //   const donutChartHama = new ApexCharts(donutChartHamaEl, donutChartHamaConfig);
  //   donutChartHama.render();
  //   window.donutChartHama = donutChartHama;

  //   // Function to load donut chart hama data
  //   function loadDonutChartHamaData(month = 'all', year = null, lapisanPengaman = 'all') {
  //     const donutChartHamaContainer = document.querySelector('#donutChartHama');
  //     if (donutChartHamaContainer) {
  //       DashboardEnhancement.showChartLoading(donutChartHamaContainer);
  //     }
      
  //     const params = new URLSearchParams({
  //       month: month,
  //       year: year || new Date().getFullYear(),
  //       lapisan_pengaman: lapisanPengaman
  //     });
      
  //     fetch(`/dashboard/donuthama?${params}`)
  //       .then(response => response.json())
  //       .then(data => {
  //         if (data.series.length === 0 || data.labels.length === 0) {
  //           donutChartHamaContainer.innerHTML = '<div class="text-center p-4"><p>Tidak ada data hama untuk periode yang dipilih.</p></div>';
  //         } else {
  //           // Reset container if it was showing no data message
  //           if (donutChartHamaContainer.innerHTML.includes('Tidak ada data')) {
  //             donutChartHamaContainer.innerHTML = '';
  //             donutChartHama.render();
  //           }
            
  //           window.donutChartHama.updateOptions({
  //             labels: data.labels,
  //             series: data.series,
  //             colors: data.colors
  //           });
  //         }
          
  //         // Hide loading animation
  //         if (donutChartHamaContainer) {
  //           setTimeout(() => {
  //             DashboardEnhancement.hideChartLoading(donutChartHamaContainer);
  //             DashboardEnhancement.addChartAnimation(donutChartHamaContainer);
  //           }, 500);
  //         }
  //       })
  //       .catch(error => {
  //         console.error('Error loading donut chart hama data:', error);
  //         if (donutChartHamaContainer) {
  //           DashboardEnhancement.hideChartLoading(donutChartHamaContainer);
  //         }
  //       });
  //   }

  //   // Function to load security layers for donut chart filter
  //   function loadSecurityLayersForDonut() {
  //     fetch('/dashboard/security-layers')
  //       .then(response => response.json())
  //       .then(layers => {
  //         const lapisanSelect = document.getElementById('lapisanPengamanFilterDonutHama');
  //         if (lapisanSelect) {
  //           // Clear existing options except "Semua Lapisan"
  //           lapisanSelect.innerHTML = '<option value="all" selected>Semua Lapisan</option>';
            
  //           // Add security layer options
  //           layers.forEach(layer => {
  //             const option = document.createElement('option');
  //             option.value = layer;
  //             option.textContent = `Lapisan ${layer}`;
  //             lapisanSelect.appendChild(option);
  //           });
  //         }
  //       })
  //       .catch(error => {
  //         console.error('Error loading security layers for donut:', error);
  //       });
  //   }

  //   // Load initial data and security layers
  //   loadSecurityLayersForDonut();
  //   loadDonutChartHamaData();

  //   // Security layer filter change handler
  //   const lapisanPengamanFilterDonut = document.getElementById('lapisanPengamanFilterDonutHama');
  //   if (lapisanPengamanFilterDonut) {
  //     lapisanPengamanFilterDonut.addEventListener('change', function() {
  //       const month = document.getElementById('monthFilterDonutHama')?.value || 'all';
  //       const year = document.getElementById('yearFilterDonutHama')?.value || new Date().getFullYear();
  //       loadDonutChartHamaData(month, year, this.value);
  //     });
  //   }

  //   // Month filter change handler
  //   const monthFilterDonutHama = document.getElementById('monthFilterDonutHama');
  //   if (monthFilterDonutHama) {
  //     monthFilterDonutHama.addEventListener('change', function() {
  //       const year = document.getElementById('yearFilterDonutHama')?.value || new Date().getFullYear();
  //       const lapisanPengaman = document.getElementById('lapisanPengamanFilterDonutHama')?.value || 'all';
  //       loadDonutChartHamaData(this.value, year, lapisanPengaman);
  //     });
  //   }

  //   // Year filter change handler
  //   const yearFilterDonutHama = document.getElementById('yearFilterDonutHama');
  //   if (yearFilterDonutHama) {
  //     yearFilterDonutHama.addEventListener('change', function() {
  //       const month = document.getElementById('monthFilterDonutHama')?.value || 'all';
  //       const lapisanPengaman = document.getElementById('lapisanPengamanFilterDonutHama')?.value || 'all';
  //       loadDonutChartHamaData(month, this.value, lapisanPengaman);
  //     });
  //   }
  // }

  // Bar Chart
  // --------------------------------------------------------------------
  const barChartEl = document.querySelector('#barChart'),
    barChartConfig = {
      chart: {
        height: 400,
        type: 'bar',
        toolbar: {
          show: false
        },
        events: {
          dataPointSelection: function (_, __, config) {
            const dataPointIndex = config.dataPointIndex;
            const seriesIndex = config.seriesIndex;
            const seriesName = config.w.config.series[seriesIndex].name;

            // Set modal content with selected data
            // $('#barChartModalLabel').text(`Data Detail: ${category}`);
            // $('#barChartModalContent').html(`
            //   <p><strong>Category:</strong> ${category}</p>
            //   <p><strong>Type:</strong> ${seriesName}</p>
            //   <p><strong>Value:</strong> ${value}</p>
            // `);
            $('#kondisiBait').text(seriesName);

            if (dataPointIndex !== -1) {
              if (dataPointIndex === 0) {
                document.getElementById('bulanKondisiBait').innerHTML = 'Januari';
              } else if (dataPointIndex === 1) {
                document.getElementById('bulanKondisiBait').innerHTML = 'Februari';
              } else if (dataPointIndex === 2) {
                document.getElementById('bulanKondisiBait').innerHTML = 'Maret';
              } else if (dataPointIndex === 3) {
                document.getElementById('bulanKondisiBait').innerHTML = 'April';
              } else if (dataPointIndex === 4) {
                document.getElementById('bulanKondisiBait').innerHTML = 'Mei';
              } else if (dataPointIndex === 5) {
                document.getElementById('bulanKondisiBait').innerHTML = 'Juni';
              } else if (dataPointIndex === 6) {
                document.getElementById('bulanKondisiBait').innerHTML = 'Juli';
              } else if (dataPointIndex === 7) {
                document.getElementById('bulanKondisiBait').innerHTML = 'Agustus';
              } else if (dataPointIndex === 8) {
                document.getElementById('bulanKondisiBait').innerHTML = 'September';
              } else if (dataPointIndex === 9) {
                document.getElementById('bulanKondisiBait').innerHTML = 'Oktober';
              } else if (dataPointIndex === 10) {
                document.getElementById('bulanKondisiBait').innerHTML = 'November';
              } else if (dataPointIndex === 11) {
                document.getElementById('bulanKondisiBait').innerHTML = 'Desember';
              }

              if ($.fn.DataTable.isDataTable('#inspeksiKondisiBaitTable')) {
                $('#inspeksiKondisiBaitTable').DataTable().destroy();
              }

              var tahun = document.getElementById('yearFilterBarChart').value;
              document.getElementById('tahunKondisiBait').innerHTML = tahun;

              console.log('seriesName:', seriesName);
              console.log('dataPointIndex:', dataPointIndex);
              console.log('tahun:', tahun);

              // tabel inspeksi kondisi bait
              $('#inspeksiKondisiBaitTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                  url: route('inspeksi.datahamaperkondisi'),
                  type: 'GET',
                  data: {
                    kondisi: seriesName,
                    bulan: dataPointIndex + 1,
                    tahun: tahun
                  },
                  error: function (xhr) {
                    if (xhr.status === 401) {
                      window.location.href = '/login';
                    }
                  }
                },
                columns: [
                  {
                    data: 'id'
                  },
                  {
                    data: 'tanggal'
                  },
                  {
                    data: 'lokasi.lokasi_nama',
                    render: function (data, type, row) {
                      return row.lokasi ? row.lokasi.lokasi_nama : '';
                    }
                  },
                  {
                    data: 'inspeksidetail.kondisi_rbs',
                    render: function (data, type, row) {
                      return row.inspeksidetail && row.inspeksidetail.length > 0
                        ? row.inspeksidetail[0].kondisi_rbs
                        : '';
                    }
                  },
                  {
                    data: 'inspeksidetail.tindakan_rbs',
                    render: function (data, type, row) {
                      return row.inspeksidetail && row.inspeksidetail.length > 0
                        ? row.inspeksidetail[0].tindakan_rbs
                        : '';
                    }
                  },
                  {
                    data: 'jumlah'
                  }
                ],
                columnDefs: [
                  {
                    // For Responsive
                    className: 'control',
                    orderable: false,
                    searchable: false,
                    responsivePriority: 2,
                    targets: 0,
                    render: function (data, type, full, meta) {
                      return '';
                    }
                  }
                ],
                order: [[0, 'desc']],
                dom: '<"card-header flex-column flex-md-row"<"head-label text-center"><"dt-action-buttons text-end pt-3 pt-md-0"B>><"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                displayLength: 10,
                lengthMenu: [10, 25, 50, 75, 100],
                buttons: [
                  {
                    extend: 'collection',
                    className: 'btn btn-label-primary dropdown-toggle me-2',
                    text: '<i class="bx bx-export me-sm-1"></i> <span class="d-none d-sm-inline-block">Export</span>',
                    buttons: [
                      {
                        extend: 'print',
                        text: '<i class="bx bx-printer me-1" ></i>Print',
                        className: 'dropdown-item',
                        title:
                          'Data Inspeksi Bulanan ' +
                          document.getElementById('bulanInspeksi').textContent +
                          ' ' +
                          new Date().getFullYear(),
                        exportOptions: {
                          columns: [1, 2, 3, 4, 5, 6],
                          // prevent avatar to be display
                          format: {
                            body: function (inner) {
                              if (inner.length <= 0) return inner;
                              if (typeof inner === 'number') return inner;
                              var el = $.parseHTML(inner);
                              var result = '';
                              $.each(el, function (_, item) {
                                if (item.classList !== undefined && item.classList.contains('user-name')) {
                                  result = result + item.lastChild.firstChild.textContent;
                                } else if (item.innerText === undefined) {
                                  result = result + item.textContent;
                                } else result = result + item.innerText;
                              });
                              return result;
                            }
                          }
                        },
                        customize: function (win) {
                          //customize print view for dark
                          $(win.document.body)
                            .css('color', config.colors.headingColor)
                            .css('border-color', config.colors.borderColor)
                            .css('background-color', config.colors.bodyBg);
                          $(win.document.body)
                            .find('table')
                            .addClass('compact')
                            .css('color', 'inherit')
                            .css('border-color', 'inherit')
                            .css('background-color', 'inherit');
                        }
                      },
                      {
                        extend: 'csv',
                        text: '<i class="bx bx-file me-1" ></i>Csv',
                        className: 'dropdown-item',
                        title:
                          'Data Inspeksi Bulanan ' +
                          document.getElementById('bulanInspeksi').textContent +
                          ' ' +
                          new Date().getFullYear(),
                        exportOptions: {
                          columns: [1, 2, 3, 4, 5, 6],
                          // prevent avatar to be display
                          format: {
                            body: function (inner, coldex, rowdex) {
                              if (inner.length <= 0) return inner;
                              if (typeof inner === 'number') return inner;
                              var el = $.parseHTML(inner);
                              var result = '';
                              $.each(el, function (_, item) {
                                if (item.classList !== undefined && item.classList.contains('user-name')) {
                                  result = result + item.lastChild.firstChild.textContent;
                                } else if (item.innerText === undefined) {
                                  result = result + item.textContent;
                                } else result = result + item.innerText;
                              });
                              return result;
                            }
                          }
                        }
                      },
                      {
                        extend: 'excel',
                        text: '<i class="bx bxs-file-export me-1"></i>Excel',
                        className: 'dropdown-item',
                        title:
                          'Data Inspeksi Bulanan ' +
                          document.getElementById('bulanInspeksi').textContent +
                          ' ' +
                          new Date().getFullYear(),
                        exportOptions: {
                          columns: [1, 2, 3, 4, 5, 6],
                          // prevent avatar to be display
                          format: {
                            body: function (inner, coldex, rowdex) {
                              if (inner.length <= 0) return inner;
                              if (typeof inner === 'number') return inner;
                              var el = $.parseHTML(inner);
                              var result = '';
                              $.each(el, function (_, item) {
                                if (item.classList !== undefined && item.classList.contains('user-name')) {
                                  result = result + item.lastChild.firstChild.textContent;
                                } else if (item.innerText === undefined) {
                                  result = result + item.textContent;
                                } else result = result + item.innerText;
                              });
                              return result;
                            }
                          }
                        }
                      },
                      {
                        extend: 'pdf',
                        text: '<i class="bx bxs-file-pdf me-1"></i>Pdf',
                        className: 'dropdown-item',
                        title:
                          'Data Inspeksi Bulanan ' +
                          document.getElementById('bulanInspeksi').textContent +
                          ' ' +
                          new Date().getFullYear(),
                        exportOptions: {
                          columns: [1, 2, 3, 4, 5, 6],
                          // prevent avatar to be display
                          format: {
                            body: function (inner, coldex, rowdex) {
                              if (inner.length <= 0) return inner;
                              if (typeof inner === 'number') return inner;
                              var el = $.parseHTML(inner);
                              var result = '';
                              $.each(el, function (_, item) {
                                if (item.classList !== undefined && item.classList.contains('user-name')) {
                                  result = result + item.lastChild.firstChild.textContent;
                                } else if (item.innerText === undefined) {
                                  result = result + item.textContent;
                                } else result = result + item.innerText;
                              });
                              return result;
                            }
                          }
                        }
                      },
                      {
                        extend: 'copy',
                        text: '<i class="bx bx-copy me-1" ></i>Copy',
                        className: 'dropdown-item',
                        title:
                          'Data Inspeksi Bulanan ' +
                          document.getElementById('bulanInspeksi').textContent +
                          ' ' +
                          new Date().getFullYear(),
                        exportOptions: {
                          columns: [1, 2, 3, 4, 5, 6],
                          // prevent avatar to be display
                          format: {
                            body: function (inner, coldex, rowdex) {
                              if (inner.length <= 0) return inner;
                              if (typeof inner === 'number') return inner;
                              var el = $.parseHTML(inner);
                              var result = '';
                              $.each(el, function (_, item) {
                                if (item.classList !== undefined && item.classList.contains('user-name')) {
                                  result = result + item.lastChild.firstChild.textContent;
                                } else if (item.innerText === undefined) {
                                  result = result + item.textContent;
                                } else result = result + item.innerText;
                              });
                              return result;
                            }
                          }
                        }
                      }
                    ]
                  }
                ],
                responsive: {
                  details: {
                    display: $.fn.dataTable.Responsive.display.modal({
                      header: function (row) {
                        var data = row.data();
                        return 'Details of ' + data['full_name'];
                      }
                    }),
                    type: 'column',
                    renderer: function (api, rowIdx, columns) {
                      var data = $.map(columns, function (col, i) {
                        return col.title !== '' // ? Do not show row in modal popup if title is blank (for check box)
                          ? '<tr data-dt-row="' +
                              col.rowIndex +
                              '" data-dt-column="' +
                              col.columnIndex +
                              '">' +
                              '<td>' +
                              col.title +
                              ':' +
                              '</td> ' +
                              '<td>' +
                              col.data +
                              '</td>' +
                              '</tr>'
                          : '';
                      }).join('');

                      return data ? $('<table class="table"/><tbody />').append(data) : false;
                    }
                  }
                }
              });


              // Show the modal
              $('#inspectionKondisiBaitModal').modal('show');
            }

            // If you need to load data from server based on selection:
            /*
            $.ajax({
              url: '/dashboard/bardata',
              type: 'GET',
              data: {
                category: category,
                type: seriesName,
                year: $('#yearFilterBarChart').val()
              },
              success: function(response) {
                // Process the data and display in modal
                $('#barChartModalContent').html(response);
              },
              error: function(xhr) {
                console.error('Error loading data:', xhr);
              }
            });
            */
          }
        }
      },
      plotOptions: {
        bar: {
          horizontal: false,
          columnWidth: '45%',
          endingShape: 'rounded',
          borderRadius: 8, // Set border radius to 8 for rounded corners
          distributed: false // Use fixed colors for bars
        }
      },
      grid: {
        borderColor: borderColor,
        xaxis: {
          lines: {
            show: false
          }
        },
        padding: {
          top: -20,
          bottom: -12
        }
      },
      colors: [
        '#00E396', // OK
        '#FEB019', // Not OK
        '#FF4560' // Broken
      ],
      dataLabels: {
        enabled: true,
        style: {
          fontSize: '12px',
          colors: ['#fff'] // White text for better contrast
        }
      },
      series: [],
      xaxis: {
        categories: [],
        axisBorder: {
          show: false
        },
        axisTicks: {
          show: false
        },
        labels: {
          style: {
            colors: labelColor,
            fontSize: '13px'
          }
        }
      },
      yaxis: {
        labels: {
          style: {
            colors: labelColor,
            fontSize: '13px'
          }
        }
      },
      theme: {
        monochrome: {
          enabled: false
        }
      },
      states: {
        hover: {
          filter: {
            type: 'darken',
            value: 0.9
          }
        }
      }
    };

  if (typeof barChartEl !== undefined && barChartEl !== null) {
    const barChart = new ApexCharts(barChartEl, barChartConfig);
    barChart.render();

    // $.getJSON('/dashboard/barchart', function (response) {
    //   barChart.updateOptions({
    //     xaxis: {
    //       categories: response.categories
    //     },
    //     series: response.data
    //   });
    // });

    // Function to initialize/update the chart
    const updateBarChart = year => {
      // Show loading animation
      const barChartContainer = document.querySelector('#barChart');
      if (barChartContainer) {
        barChartContainer.classList.add('chart-loading');
        // Create and add loading spinner if it doesn't exist
        if (!barChartContainer.querySelector('.chart-spinner')) {
          const spinner = document.createElement('div');
          spinner.className = 'chart-spinner';
          spinner.innerHTML =
            '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>';
          barChartContainer.appendChild(spinner);
        } else {
          barChartContainer.querySelector('.chart-spinner').style.display = 'flex';
        }
      }

      fetch(`/dashboard/barchart/${year}`)
        .then(response => response.json())
        .then(data => {
          const options = {
            chart: {
              height: 350,
              type: 'bar',
              zoom: {
                enabled: false
              },
              toolbar: {
                show: true
              }
            },
            series: data.data,
            xaxis: {
              categories: data.categories,
              labels: {
                style: {
                  colors: labelColor,
                  fontSize: '13px'
                }
              }
            },
            colors: ['#00E396', '#FEB019', '#FF4560']
          };

          if (barChart) {
            barChart.updateOptions(options);
          } else {
            barChart = new ApexCharts(document.querySelector('#barChart'), options);
            barChart.render();
          }

          // Hide loading animation
          if (barChartContainer) {
            setTimeout(() => {
              barChartContainer.classList.remove('chart-loading');
              const spinner = barChartContainer.querySelector('.chart-spinner');
              if (spinner) {
                spinner.style.display = 'none';
              }
            }, 500); // Small delay to ensure smooth transition
          }
        })
        .catch(error => {
          console.error('Error fetching bar chart data:', error);
          // Hide loading on error too
          if (barChartContainer) {
            barChartContainer.classList.remove('chart-loading');
            const spinner = barChartContainer.querySelector('.chart-spinner');
            if (spinner) {
              spinner.style.display = 'none';
            }
          }
        });
    };

    // Initialize chart with current year
    updateBarChart(new Date().getFullYear());

    // Add event listener for year change
    document.getElementById('yearFilterBarChart').addEventListener('change', function () {
      updateBarChart(this.value);
    });
  }

  // Pie Chart
  // --------------------------------------------------------------------
  const pieChartPGTEl = document.querySelector('#pieChartPGT'),
    pieChartPGTConfig = {
      chart: {
        height: 400,
        type: 'pie',
        toolbar: {
          show: false
        },
        events: {
          dataPointSelection: function (event, chartContext, config) {
            const dataPointIndex = config.dataPointIndex;
            const selectedLabel = config.w.config.labels[dataPointIndex];

            $('#namaLokasi').text(selectedLabel);

            if (dataPointIndex !== -1) {
              if ($.fn.DataTable.isDataTable('#inspeksiLokasiTable')) {
                $('#inspeksiLokasiTable').DataTable().destroy();
              }

              var tahun = document.getElementById('yearFilterPieChart').value;
              var bulan = document.getElementById('monthFilterPieChart').value;

              //tabel inspeksi lokasi
              $('#inspeksiLokasiTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                  url: route('inspeksi.datahamaperlokasi'),
                  type: 'GET',
                  data: {
                    lokasi: selectedLabel,
                    bulan: bulan,
                    tahun: tahun
                  },
                  error: function (xhr) {
                    if (xhr.status === 401) {
                      window.location.href = '/login';
                    }
                  }
                },
                columns: [
                  {
                    data: 'id'
                  },
                  {
                    data: 'tanggal'
                  },
                  {
                    data: 'metode.metode_nama',
                    render: function (data, type, row) {
                      return row.metode ? row.metode.metode_nama : '';
                    }
                  },
                  {
                    data: 'lokasi.lokasi_nama',
                    render: function (data, type, row) {
                      return row.lokasi ? row.lokasi.lokasi_nama : '';
                    }
                  },
                  {
                    data: 'inspeksidetail.tindakan_rbs',
                    render: function (data, type, row) {
                      return row.inspeksidetail && row.inspeksidetail.length > 0
                        ? row.inspeksidetail[0].tindakan_rbs
                        : '';
                    }
                  },
                  {
                    data: 'pegawai'
                  },
                  {
                    data: 'jumlah'
                  }
                ],
                columnDefs: [
                  {
                    className: 'control',
                    orderable: false,
                    searchable: false,
                    responsivePriority: 2,
                    targets: 0,
                    render: function () {
                      return '';
                    }
                  }
                ],
                order: [[0, 'desc']],
                dom: '<"card-header flex-column flex-md-row"<"head-label text-center"><"dt-action-buttons text-end pt-3 pt-md-0"B>><"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                displayLength: 10,
                lengthMenu: [10, 25, 50, 75, 100],
                buttons: [
                  {
                    extend: 'collection',
                    className: 'btn btn-label-primary dropdown-toggle me-2',
                    text: '<i class="bx bx-export me-sm-1"></i> <span class="d-none d-sm-inline-block">Export</span>',
                    buttons: [
                      {
                        extend: 'print',
                        text: '<i class="bx bx-printer me-1" ></i>Print',
                        className: 'dropdown-item',
                        title: 'Data Inspeksi Lokasi ' + selectedLabel,
                        exportOptions: {
                          columns: [1, 2, 3, 4, 5, 6],
                          format: {
                            body: function (inner) {
                              if (inner.length <= 0) return inner;
                              if (typeof inner === 'number') return inner;
                              var el = $.parseHTML(inner);
                              var result = '';
                              $.each(el, function (_, item) {
                                if (item.classList !== undefined && item.classList.contains('user-name')) {
                                  result = result + item.lastChild.firstChild.textContent;
                                } else if (item.innerText === undefined) {
                                  result = result + item.textContent;
                                } else result = result + item.innerText;
                              });
                              return result;
                            }
                          }
                        },
                        customize: function (win) {
                          $(win.document.body)
                            .css('color', config.colors.headingColor)
                            .css('border-color', config.colors.borderColor)
                            .css('background-color', config.colors.bodyBg);
                          $(win.document.body)
                            .find('table')
                            .addClass('compact')
                            .css('color', 'inherit')
                            .css('border-color', 'inherit')
                            .css('background-color', 'inherit');
                        }
                      },
                      {
                        extend: 'csv',
                        text: '<i class="bx bx-file me-1" ></i>Csv',
                        className: 'dropdown-item',
                        title: 'Data Inspeksi Lokasi ' + selectedLabel,
                        exportOptions: {
                          columns: [1, 2, 3, 4, 5, 6],
                          format: {
                            body: function (inner) {
                              if (inner.length <= 0) return inner;
                              if (typeof inner === 'number') return inner;
                              var el = $.parseHTML(inner);
                              var result = '';
                              $.each(el, function (_, item) {
                                if (item.classList !== undefined && item.classList.contains('user-name')) {
                                  result = result + item.lastChild.firstChild.textContent;
                                } else if (item.innerText === undefined) {
                                  result = result + item.textContent;
                                } else result = result + item.innerText;
                              });
                              return result;
                            }
                          }
                        }
                      },
                      {
                        extend: 'excel',
                        text: '<i class="bx bxs-file-export me-1"></i>Excel',
                        className: 'dropdown-item',
                        title: 'Data Inspeksi Lokasi ' + selectedLabel,
                        exportOptions: {
                          columns: [1, 2, 3, 4, 5, 6],
                          format: {
                            body: function (inner) {
                              if (inner.length <= 0) return inner;
                              if (typeof inner === 'number') return inner;
                              var el = $.parseHTML(inner);
                              var result = '';
                              $.each(el, function (_, item) {
                                if (item.classList !== undefined && item.classList.contains('user-name')) {
                                  result = result + item.lastChild.firstChild.textContent;
                                } else if (item.innerText === undefined) {
                                  result = result + item.textContent;
                                } else result = result + item.innerText;
                              });
                              return result;
                            }
                          }
                        }
                      },
                      {
                        extend: 'pdf',
                        text: '<i class="bx bxs-file-pdf me-1"></i>Pdf',
                        className: 'dropdown-item',
                        title: 'Data Inspeksi Lokasi ' + selectedLabel,
                        exportOptions: {
                          columns: [1, 2, 3, 4, 5, 6],
                          format: {
                            body: function (inner) {
                              if (inner.length <= 0) return inner;
                              if (typeof inner === 'number') return inner;
                              var el = $.parseHTML(inner);
                              var result = '';
                              $.each(el, function (_, item) {
                                if (item.classList !== undefined && item.classList.contains('user-name')) {
                                  result = result + item.lastChild.firstChild.textContent;
                                } else if (item.innerText === undefined) {
                                  result = result + item.textContent;
                                } else result = result + item.innerText;
                              });
                              return result;
                            }
                          }
                        }
                      },
                      {
                        extend: 'copy',
                        text: '<i class="bx bx-copy me-1" ></i>Copy',
                        className: 'dropdown-item',
                        title: 'Data Inspeksi Lokasi ' + selectedLabel,
                        exportOptions: {
                          columns: [1, 2, 3, 4, 5, 6],
                          format: {
                            body: function (inner) {
                              if (inner.length <= 0) return inner;
                              if (typeof inner === 'number') return inner;
                              var el = $.parseHTML(inner);
                              var result = '';
                              $.each(el, function (_, item) {
                                if (item.classList !== undefined && item.classList.contains('user-name')) {
                                  result = result + item.lastChild.firstChild.textContent;
                                } else if (item.innerText === undefined) {
                                  result = result + item.textContent;
                                } else result = result + item.innerText;
                              });
                              return result;
                            }
                          }
                        }
                      }
                    ]
                  }
                ],
                responsive: {
                  details: {
                    display: $.fn.dataTable.Responsive.display.modal({
                      header: function (row) {
                        var data = row.data();
                        return 'Details of Inspeksi';
                      }
                    }),
                    type: 'column',
                    renderer: function (api, rowIdx, columns) {
                      var data = $.map(columns, function (col, i) {
                        return col.title !== ''
                          ? '<tr data-dt-row="' +
                              col.rowIndex +
                              '" data-dt-column="' +
                              col.columnIndex +
                              '">' +
                              '<td>' +
                              col.title +
                              ':' +
                              '</td> ' +
                              '<td>' +
                              col.data +
                              '</td>' +
                              '</tr>'
                          : '';
                      }).join('');

                      return data ? $('<table class="table"/><tbody />').append(data) : false;
                    }
                  }
                }
              });

              // Show the modal
              $('#inspectionLokasiModal').modal('show');
            }
          }
        }
      },
      labels: [],
      series: [],
      colors: [
        '#4CAF50', // Green
        '#2196F3', // Blue
        '#FFC107', // Amber
        '#9C27B0', // Purple
        '#FF5722', // Deep Orange
        '#00BCD4', // Cyan
        '#795548', // Brown
        '#3F51B5', // Indigo
        '#FF9800', // Orange
        '#E91E63', // Pink
        '#607D8B', // Blue Grey
        '#8BC34A' // Light Green
      ],
      dataLabels: {
        enabled: true,
        formatter: function (val) {
          return parseInt(val, 10) + '%';
        },
        style: {
          fontSize: '14px',
          fontFamily: 'Public Sans',
          fontWeight: 500,
          colors: ['#fff']
        }
      },
      legend: {
        show: true,
        position: 'bottom',
        markers: {
          offsetX: -3,
          radius: 3,
          strokeWidth: 0
        },
        itemMargin: {
          vertical: 5,
          horizontal: 10
        },
        labels: {
          colors: legendColor,
          useSeriesColors: false
        }
      },
      plotOptions: {
        pie: {
          donut: {
            labels: {
              show: true,
              name: {
                fontSize: '2rem',
                fontFamily: 'Public Sans',
                fontWeight: 600,
                color: headingColor
              },
              value: {
                fontSize: '1.2rem',
                color: legendColor,
                fontFamily: 'Public Sans',
                fontWeight: 500,
                formatter: function (val) {
                  return parseInt(val, 10) + '%';
                }
              },
              total: {
                show: true,
                fontSize: '1.5rem',
                color: headingColor,
                label: 'PGT',
                formatter: function () {
                  return parseInt(100, 10) + '%';
                }
              }
            }
          }
        }
      },
      responsive: [
        {
          breakpoint: 992,
          options: {
            chart: {
              height: 380
            },
            legend: {
              position: 'bottom',
              labels: {
                colors: legendColor,
                useSeriesColors: false
              }
            }
          }
        },
        {
          breakpoint: 576,
          options: {
            chart: {
              height: 320
            },
            plotOptions: {
              pie: {
                donut: {
                  labels: {
                    show: true,
                    name: {
                      fontSize: '1.5rem'
                    },
                    value: {
                      fontSize: '1rem'
                    },
                    total: {
                      fontSize: '1.5rem'
                    }
                  }
                }
              }
            },
            legend: {
              position: 'bottom',
              labels: {
                colors: legendColor,
                useSeriesColors: false
              }
            }
          }
        },
        {
          breakpoint: 420,
          options: {
            chart: {
              height: 280
            },
            legend: {
              show: false
            }
          }
        },
        {
          breakpoint: 360,
          options: {
            chart: {
              height: 250
            },
            legend: {
              show: false
            }
          }
        }
      ]
    };

  const pieChartRBSEl = document.querySelector('#pieChartRbs'),
    pieChartRBSConfig = {
      chart: {
        height: 400,
        type: 'pie',
        toolbar: {
          show: false
        },
        events: {
          dataPointSelection: function (event, chartContext, config) {
            // console.log(config);
            const dataPointIndex = config.dataPointIndex;
            const selectedLabel = config.w.config.labels[dataPointIndex];

            $('#namaLokasi').text(selectedLabel);

            if (dataPointIndex !== -1) {
              if ($.fn.DataTable.isDataTable('#inspeksiLokasiTable')) {
                $('#inspeksiLokasiTable').DataTable().destroy();
              }

              var tahun = document.getElementById('yearFilterPieChart').value;
              var bulan = document.getElementById('monthFilterPieChart').value;

              // Show loading indicator if needed
              // $('#inspeksiLokasiTableContainer').addClass('loading');

              // tabel inspeksi lokasi
              $('#inspeksiLokasiTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                  url: route('inspeksi.datahamaperlokasi'),
                  type: 'GET',
                  data: {
                    lokasi: selectedLabel,
                    bulan: bulan,
                    tahun: tahun
                  },
                  error: function (xhr) {
                    if (xhr.status === 401) {
                      window.location.href = '/login';
                    }
                    // $('#inspeksiLokasiTableContainer').removeClass('loading');
                  },
                  complete: function () {
                    // $('#inspeksiLokasiTableContainer').removeClass('loading');
                  }
                },
                columns: [
                  {
                    data: 'id'
                  },
                  {
                    data: 'tanggal'
                  },
                  {
                    data: 'metode.metode_nama',
                    render: function (data, type, row) {
                      return row.metode ? row.metode.metode_nama : '';
                    }
                  },
                  {
                    data: 'lokasi.lokasi_nama',
                    render: function (data, type, row) {
                      return row.lokasi ? row.lokasi.lokasi_nama : '';
                    }
                  },
                  {
                    data: 'hama.hama_nama',
                    render: function (data, type, row) {
                      return row.hama ? row.hama.hama_nama : '-';
                    }
                  },
                  {
                    data: 'pegawai'
                  },
                  {
                    data: 'jumlah'
                  }
                ],
                columnDefs: [
                  {
                    className: 'control',
                    orderable: false,
                    searchable: false,
                    responsivePriority: 2,
                    targets: 0,
                    render: function () {
                      return '';
                    }
                  }
                ],
                order: [[0, 'desc']],
                dom: '<"card-header flex-column flex-md-row"<"head-label text-center"><"dt-action-buttons text-end pt-3 pt-md-0"B>><"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                displayLength: 10,
                lengthMenu: [10, 25, 50, 75, 100],
                buttons: [
                  {
                    extend: 'collection',
                    className: 'btn btn-label-primary dropdown-toggle me-2',
                    text: '<i class="bx bx-export me-sm-1"></i> <span class="d-none d-sm-inline-block">Export</span>',
                    buttons: [
                      {
                        extend: 'print',
                        text: '<i class="bx bx-printer me-1" ></i>Print',
                        className: 'dropdown-item',
                        title: 'Data Inspeksi Lokasi ' + selectedLabel,
                        exportOptions: {
                          columns: [1, 2, 3, 4, 5, 6],
                          format: {
                            body: function (inner) {
                              if (inner.length <= 0) return inner;
                              if (typeof inner === 'number') return inner;
                              var el = $.parseHTML(inner);
                              var result = '';
                              $.each(el, function (_, item) {
                                if (item.classList !== undefined && item.classList.contains('user-name')) {
                                  result = result + item.lastChild.firstChild.textContent;
                                } else if (item.innerText === undefined) {
                                  result = result + item.textContent;
                                } else result = result + item.innerText;
                              });
                              return result;
                            }
                          }
                        },
                        customize: function (win) {
                          $(win.document.body)
                            .css('color', config.colors.headingColor)
                            .css('border-color', config.colors.borderColor)
                            .css('background-color', config.colors.bodyBg);
                          $(win.document.body)
                            .find('table')
                            .addClass('compact')
                            .css('color', 'inherit')
                            .css('border-color', 'inherit')
                            .css('background-color', 'inherit');
                        }
                      },
                      {
                        extend: 'csv',
                        text: '<i class="bx bx-file me-1" ></i>Csv',
                        className: 'dropdown-item',
                        title: 'Data Inspeksi Lokasi ' + selectedLabel,
                        exportOptions: {
                          columns: [1, 2, 3, 4, 5, 6],
                          format: {
                            body: function (inner) {
                              if (inner.length <= 0) return inner;
                              if (typeof inner === 'number') return inner;
                              var el = $.parseHTML(inner);
                              var result = '';
                              $.each(el, function (_, item) {
                                if (item.classList !== undefined && item.classList.contains('user-name')) {
                                  result = result + item.lastChild.firstChild.textContent;
                                } else if (item.innerText === undefined) {
                                  result = result + item.textContent;
                                } else result = result + item.innerText;
                              });
                              return result;
                            }
                          }
                        }
                      },
                      {
                        extend: 'excel',
                        text: '<i class="bx bxs-file-export me-1"></i>Excel',
                        className: 'dropdown-item',
                        title: 'Data Inspeksi Lokasi ' + selectedLabel,
                        exportOptions: {
                          columns: [1, 2, 3, 4, 5, 6],
                          format: {
                            body: function (inner) {
                              if (inner.length <= 0) return inner;
                              if (typeof inner === 'number') return inner;
                              var el = $.parseHTML(inner);
                              var result = '';
                              $.each(el, function (_, item) {
                                if (item.classList !== undefined && item.classList.contains('user-name')) {
                                  result = result + item.lastChild.firstChild.textContent;
                                } else if (item.innerText === undefined) {
                                  result = result + item.textContent;
                                } else result = result + item.innerText;
                              });
                              return result;
                            }
                          }
                        }
                      },
                      {
                        extend: 'pdf',
                        text: '<i class="bx bxs-file-pdf me-1"></i>Pdf',
                        className: 'dropdown-item',
                        title: 'Data Inspeksi Lokasi ' + selectedLabel,
                        exportOptions: {
                          columns: [1, 2, 3, 4, 5, 6],
                          format: {
                            body: function (inner) {
                              if (inner.length <= 0) return inner;
                              if (typeof inner === 'number') return inner;
                              var el = $.parseHTML(inner);
                              var result = '';
                              $.each(el, function (_, item) {
                                if (item.classList !== undefined && item.classList.contains('user-name')) {
                                  result = result + item.lastChild.firstChild.textContent;
                                } else if (item.innerText === undefined) {
                                  result = result + item.textContent;
                                } else result = result + item.innerText;
                              });
                              return result;
                            }
                          }
                        }
                      },
                      {
                        extend: 'copy',
                        text: '<i class="bx bx-copy me-1" ></i>Copy',
                        className: 'dropdown-item',
                        title: 'Data Inspeksi Lokasi ' + selectedLabel,
                        exportOptions: {
                          columns: [1, 2, 3, 4, 5, 6],
                          format: {
                            body: function (inner) {
                              if (inner.length <= 0) return inner;
                              if (typeof inner === 'number') return inner;
                              var el = $.parseHTML(inner);
                              var result = '';
                              $.each(el, function (_, item) {
                                if (item.classList !== undefined && item.classList.contains('user-name')) {
                                  result = result + item.lastChild.firstChild.textContent;
                                } else if (item.innerText === undefined) {
                                  result = result + item.textContent;
                                } else result = result + item.innerText;
                              });
                              return result;
                            }
                          }
                        }
                      }
                    ]
                  }
                ],
                responsive: {
                  details: {
                    display: $.fn.dataTable.Responsive.display.modal({
                      header: function (row) {
                        var data = row.data();
                        return 'Details of Inspeksi';
                      }
                    }),
                    type: 'column',
                    renderer: function (api, rowIdx, columns) {
                      var data = $.map(columns, function (col, i) {
                        return col.title !== ''
                          ? '<tr data-dt-row="' +
                              col.rowIndex +
                              '" data-dt-column="' +
                              col.columnIndex +
                              '">' +
                              '<td>' +
                              col.title +
                              ':' +
                              '</td> ' +
                              '<td>' +
                              col.data +
                              '</td>' +
                              '</tr>'
                          : '';
                      }).join('');

                      return data ? $('<table class="table"/><tbody />').append(data) : false;
                    }
                  }
                }
              });

              $('#inspectionLokasiModal').modal('show');
            }
          }
        }
      },
      labels: [],
      series: [],
      colors: [
        '#4CAF50', // Green
        '#2196F3', // Blue
        '#FFC107', // Amber
        '#9C27B0', // Purple
        '#FF5722', // Deep Orange
        '#00BCD4', // Cyan
        '#795548', // Brown
        '#3F51B5', // Indigo
        '#FF9800', // Orange
        '#E91E63', // Pink
        '#607D8B', // Blue Grey
        '#8BC34A' // Light Green
      ],
      dataLabels: {
        enabled: true,
        formatter: function (val) {
          return parseInt(val, 10) + '%';
        },
        style: {
          fontSize: '14px',
          fontFamily: 'Public Sans',
          fontWeight: 500,
          colors: ['#fff']
        }
      },
      legend: {
        show: true,
        position: 'bottom',
        markers: {
          offsetX: -3,
          radius: 3,
          strokeWidth: 0
        },
        itemMargin: {
          vertical: 5,
          horizontal: 10
        },
        labels: {
          colors: legendColor,
          useSeriesColors: false
        }
      },
      plotOptions: {
        pie: {
          donut: {
            labels: {
              show: true,
              name: {
                fontSize: '2rem',
                fontFamily: 'Public Sans',
                fontWeight: 600,
                color: headingColor
              },
              value: {
                fontSize: '1.2rem',
                color: legendColor,
                fontFamily: 'Public Sans',
                fontWeight: 500,
                formatter: function (val) {
                  return parseInt(val, 10) + '%';
                }
              },
              total: {
                show: true,
                fontSize: '1.5rem',
                color: headingColor,
                label: 'FS',
                formatter: function () {
                  return parseInt(100, 10) + '%';
                }
              }
            }
          }
        }
      },
      responsive: [
        {
          breakpoint: 992,
          options: {
            chart: {
              height: 380
            },
            legend: {
              position: 'bottom',
              labels: {
                colors: legendColor,
                useSeriesColors: false
              }
            }
          }
        },
        {
          breakpoint: 576,
          options: {
            chart: {
              height: 320
            },
            plotOptions: {
              pie: {
                donut: {
                  labels: {
                    show: true,
                    name: {
                      fontSize: '1.5rem'
                    },
                    value: {
                      fontSize: '1rem'
                    },
                    total: {
                      fontSize: '1.5rem'
                    }
                  }
                }
              }
            },
            legend: {
              position: 'bottom',
              labels: {
                colors: legendColor,
                useSeriesColors: false
              }
            }
          }
        },
        {
          breakpoint: 420,
          options: {
            chart: {
              height: 280
            },
            legend: {
              show: false
            }
          }
        },
        {
          breakpoint: 360,
          options: {
            chart: {
              height: 250
            },
            legend: {
              show: false
            }
          }
        }
      ]
    };

  const pieChartFSEl = document.querySelector('#pieChartFS'),
    pieChartFSConfig = {
      chart: {
        height: 400,
        type: 'pie',
        toolbar: {
          show: false
        }
      },
      labels: [],
      series: [],
      colors: [
        '#4CAF50', // Green
        '#2196F3', // Blue
        '#FFC107', // Amber
        '#9C27B0', // Purple
        '#FF5722', // Deep Orange
        '#00BCD4', // Cyan
        '#795548', // Brown
        '#3F51B5', // Indigo
        '#FF9800', // Orange
        '#E91E63', // Pink
        '#607D8B', // Blue Grey
        '#8BC34A' // Light Green
      ],
      dataLabels: {
        enabled: true,
        formatter: function (val) {
          return parseInt(val, 10) + '%';
        },
        style: {
          fontSize: '14px',
          fontFamily: 'Public Sans',
          fontWeight: 500,
          colors: ['#fff']
        }
      },
      legend: {
        show: true,
        position: 'bottom',
        markers: {
          offsetX: -3,
          radius: 3,
          strokeWidth: 0
        },
        itemMargin: {
          vertical: 5,
          horizontal: 10
        },
        labels: {
          colors: legendColor,
          useSeriesColors: false
        }
      },
      plotOptions: {
        pie: {
          donut: {
            labels: {
              show: true,
              name: {
                fontSize: '2rem',
                fontFamily: 'Public Sans',
                fontWeight: 600,
                color: headingColor
              },
              value: {
                fontSize: '1.2rem',
                color: legendColor,
                fontFamily: 'Public Sans',
                fontWeight: 500,
                formatter: function (val) {
                  return parseInt(val, 10) + '%';
                }
              },
              total: {
                show: true,
                fontSize: '1.5rem',
                color: headingColor,
                label: 'FS',
                formatter: function () {
                  return parseInt(100, 10) + '%';
                }
              }
            }
          }
        }
      },
      responsive: [
        {
          breakpoint: 992,
          options: {
            chart: {
              height: 380
            },
            legend: {
              position: 'bottom',
              labels: {
                colors: legendColor,
                useSeriesColors: false
              }
            }
          }
        },
        {
          breakpoint: 576,
          options: {
            chart: {
              height: 320
            },
            plotOptions: {
              pie: {
                donut: {
                  labels: {
                    show: true,
                    name: {
                      fontSize: '1.5rem'
                    },
                    value: {
                      fontSize: '1rem'
                    },
                    total: {
                      fontSize: '1.5rem'
                    }
                  }
                }
              }
            },
            legend: {
              position: 'bottom',
              labels: {
                colors: legendColor,
                useSeriesColors: false
              }
            }
          }
        },
        {
          breakpoint: 420,
          options: {
            chart: {
              height: 280
            },
            legend: {
              show: false
            }
          }
        },
        {
          breakpoint: 360,
          options: {
            chart: {
              height: 250
            },
            legend: {
              show: false
            }
          }
        }
      ]
    };

  const updatePieCharts = (month, year) => {
    // Show loading on all pie chart containers
    const chartContainers = ['#pieChartRbs', '#pieChartPGT', '#pieChartFS'];
    chartContainers.forEach(selector => {
      const container = document.querySelector(selector);
      if (container) {
        DashboardEnhancement.showChartLoading(container);
      }
    });

    // First fetch all metode data
    $.getJSON('/dashboard/metodes', function (metodesData) {
      const metodeMap = {};
      metodesData.forEach(metode => {
        metodeMap[metode.id] = metode;
      });

      const pieCharts = [
        { el: pieChartRBSEl, config: pieChartRBSConfig, url: '/dashboard/piechartrbs', metodeId: 3 },
        { el: pieChartPGTEl, config: pieChartPGTConfig, url: '/dashboard/piechartpgt', metodeId: 1 },
        { el: pieChartFSEl, config: pieChartFSConfig, url: '/dashboard/piechartfs', metodeId: 2 }
      ];

      pieCharts.forEach(chart => {
        if (typeof chart.el !== undefined && chart.el !== null) {
          // Get the method data for this chart
          const metodeData = metodeMap[chart.metodeId];
          const dynamicLabel = metodeData ? metodeData.metode_nama : 'Unknown';
          
          // Update the chart config with dynamic label
          const updatedConfig = { ...chart.config };
          if (updatedConfig.plotOptions && updatedConfig.plotOptions.pie && 
              updatedConfig.plotOptions.pie.donut && updatedConfig.plotOptions.pie.donut.labels &&
              updatedConfig.plotOptions.pie.donut.labels.total) {
            updatedConfig.plotOptions.pie.donut.labels.total.label = dynamicLabel;
          }
          
          const pieChart = new ApexCharts(chart.el, updatedConfig);
          pieChart.render();

          // Fetch the chart data
          $.getJSON(chart.url, { month, year }, function (response) {
            // Hide loading
            DashboardEnhancement.hideChartLoading(chart.el);
            
            if (response.series.length === 0 || response.labels.length === 0) {
              chart.el.innerHTML = '<div class="no-data-message">No data available for the selected period.</div>';
            } else {
              pieChart.updateOptions({
                labels: response.labels,
                series: response.series
              });
              DashboardEnhancement.addChartAnimation(chart.el);
            }
          }).fail(function () {
            DashboardEnhancement.hideChartLoading(chart.el);
            chart.el.innerHTML = '<div class="no-data-message">Failed to load data.</div>';
            DashboardEnhancement.showNotification('Failed to load chart data', 'warning');
          });
        }
      });
    }).fail(function () {
      // Hide loading on all containers
      chartContainers.forEach(selector => {
        const container = document.querySelector(selector);
        if (container) {
          DashboardEnhancement.hideChartLoading(container);
        }
      });
      
      DashboardEnhancement.showNotification('Failed to load method data, using defaults', 'info');
      
      // Fallback to original hardcoded labels if metodes fetch fails
      const pieCharts = [
        { el: pieChartRBSEl, config: pieChartRBSConfig, url: '/dashboard/piechartrbs' },
        { el: pieChartPGTEl, config: pieChartPGTConfig, url: '/dashboard/piechartpgt' },
        { el: pieChartFSEl, config: pieChartFSConfig, url: '/dashboard/piechartfs' }
      ];

      pieCharts.forEach(chart => {
        if (typeof chart.el !== undefined && chart.el !== null) {
          const pieChart = new ApexCharts(chart.el, chart.config);
          pieChart.render();

          $.getJSON(chart.url, { month, year }, function (response) {
            if (response.series.length === 0 || response.labels.length === 0) {
              chart.el.innerHTML = '<p class="text-center">No data available for the selected period.</p>';
            } else {
              pieChart.updateOptions({
                labels: response.labels,
                series: response.series
              });
            }
          }).fail(function () {
            chart.el.innerHTML = '<p class="text-center">Failed to load data.</p>';
          });
        }
      });
    });
  };

  // Initialize charts with current month and year
  const currentDate = new Date();
  updatePieCharts('all', currentDate.getFullYear());

  // Add event listener for month and year change
  document.getElementById('monthFilterPieChart').addEventListener('change', function () {
    const year = document.getElementById('yearFilterPieChart').value;
    updatePieCharts(this.value, year);
  });

  document.getElementById('yearFilterPieChart').addEventListener('change', function () {
    const month = document.getElementById('monthFilterPieChart').value;
    updatePieCharts(month, this.value);
  });

  if (typeof pieChartRBSEl !== undefined && pieChartRBSEl !== null) {
    const pieChartRBS = new ApexCharts(pieChartRBSEl, pieChartRBSConfig);
    pieChartRBS.render();
  }

  if (typeof pieChartPGTEl !== undefined && pieChartPGTEl !== null) {
    const pieChartPGT = new ApexCharts(pieChartPGTEl, pieChartPGTConfig);
    pieChartPGT.render();
  }

  if (typeof pieChartFSEl !== undefined && pieChartFSEl !== null) {
    const pieChartFS = new ApexCharts(pieChartFSEl, pieChartFSConfig);
    pieChartFS.render();
  }

  // Line Chart Hama
  // --------------------------------------------------------------------
  const lineChartHamaEl = document.querySelector('#lineChartHama'),
    lineChartHamaConfig = {
      chart: {
        height: 400,
        type: 'line',
        parentHeightOffset: 0,
        zoom: {
          enabled: false
        },
        toolbar: {
          show: true
        },
        events: {
          click: function (event, chartContext, config) {
            // Handle chart click events for location data
            if (config.dataPointIndex !== -1) {
              console.log('Clicked on location data:', config.seriesIndex, config.dataPointIndex);
            }
          }
        }
      },
      series: [],
      markers: {
        strokeWidth: 7,
        strokeOpacity: 1,
        strokeColors: [cardColor],
        colors: [config.colors.warning]
      },
      dataLabels: {
        enabled: false
      },
      stroke: {
        width: 3,
        curve: 'smooth'
      },
      colors: [config.colors.primary, config.colors.info, config.colors.warning, config.colors.success, config.colors.danger, '#FF6B35', '#9C27B0', '#00BCD4', '#795548'],
      grid: {
        borderColor: borderColor,
        xaxis: {
          lines: {
            show: true
          }
        },
        padding: {
          top: -20
        }
      },
      legend: {
        show: true,
        position: 'bottom',
        horizontalAlign: 'center'
      },
      tooltip: {
        custom: function ({ series, seriesIndex, dataPointIndex, w }) {
          const locationName = w.globals.seriesNames[seriesIndex];
          const value = series[seriesIndex][dataPointIndex];
          const category = w.globals.labels[dataPointIndex];
          
          return `
            <div class="tooltip-custom">
              <div class="tooltip-header">${locationName}</div>
              <div class="tooltip-body">
                <div class="tooltip-item">
                  <span class="tooltip-label">Period:</span>
                  <span class="tooltip-value">${category}</span>
                </div>
                <div class="tooltip-item">
                  <span class="tooltip-label">Jumlah Inspeksi:</span>
                  <span class="tooltip-value">${value}</span>
                </div>
              </div>
            </div>
          `;
        }
      },
      xaxis: {
        categories: [],
        axisBorder: {
          show: false
        },
        axisTicks: {
          show: false
        },
        labels: {
          style: {
            colors: labelColor,
            fontSize: '13px'
          }
        }
      },
      yaxis: {
        labels: {
          style: {
            colors: labelColor,
            fontSize: '13px'
          }
        }
      }
    };

  if (typeof lineChartHamaEl !== undefined && lineChartHamaEl !== null) {
    const lineChartHama = new ApexCharts(lineChartHamaEl, lineChartHamaConfig);
    lineChartHama.render();
    window.lineChartHama = lineChartHama;

    // Function to load location line chart data
    function loadLineChartHamaData(month = 'all', year = null, lapisanPengaman = 'all') {
      const lineChartHamaContainer = document.querySelector('#lineChartHama');
      if (lineChartHamaContainer) {
        DashboardEnhancement.showChartLoading(lineChartHamaContainer);
      }
      
      const params = new URLSearchParams({
        month: month,
        year: year || new Date().getFullYear(),
        lapisan_pengaman: lapisanPengaman
      });
      
      fetch(`/dashboard/linecharthama?${params}`)
        .then(response => response.json())
        .then(data => {
          window.lineChartHama.updateOptions({
            series: data.data,
            xaxis: {
              categories: data.categories,
              labels: {
                style: {
                  colors: labelColor,
                  fontSize: '13px'
                }
              }
            }
          });
          
          // Hide loading animation
          if (lineChartHamaContainer) {
            setTimeout(() => {
              DashboardEnhancement.hideChartLoading(lineChartHamaContainer);
              DashboardEnhancement.addChartAnimation(lineChartHamaContainer);
            }, 500);
          }
        })
        .catch(error => {
          console.error('Error loading location line chart data:', error);
          if (lineChartHamaContainer) {
            DashboardEnhancement.hideChartLoading(lineChartHamaContainer);
          }
        });
    }

    // Function to load security layers for filter
    function loadSecurityLayers() {
      fetch('/dashboard/security-layers')
        .then(response => response.json())
        .then(layers => {
          const lapisanSelect = document.getElementById('lapisanPengamanFilterLineChartHama');
          if (lapisanSelect) {
            // Clear existing options except "Semua Lapisan"
            lapisanSelect.innerHTML = '<option value="all" selected>Semua Lapisan</option>';
            
            // Add security layer options
            layers.forEach(layer => {
              const option = document.createElement('option');
              option.value = layer;
              option.textContent = `Lapisan ${layer}`;
              lapisanSelect.appendChild(option);
            });
          }
        })
        .catch(error => {
          console.error('Error loading security layers:', error);
        });
    }

    // Load initial data and security layers
    loadSecurityLayers();
    loadLineChartHamaData();

    // Security layer filter change handler
    const lapisanPengamanFilter = document.getElementById('lapisanPengamanFilterLineChartHama');
    if (lapisanPengamanFilter) {
      lapisanPengamanFilter.addEventListener('change', function() {
        const month = document.getElementById('monthFilterLineChartHama')?.value || 'all';
        const year = document.getElementById('yearFilterLineChartHama')?.value || new Date().getFullYear();
        loadLineChartHamaData(month, year, this.value);
      });
    }

    // Month filter change handler
    const monthFilterHama = document.getElementById('monthFilterLineChartHama');
    if (monthFilterHama) {
      monthFilterHama.addEventListener('change', function() {
        const year = document.getElementById('yearFilterLineChartHama')?.value || new Date().getFullYear();
        const lapisanPengaman = document.getElementById('lapisanPengamanFilterLineChartHama')?.value || 'all';
        loadLineChartHamaData(this.value, year, lapisanPengaman);
      });
    }

    // Year filter change handler
    const yearFilterHama = document.getElementById('yearFilterLineChartHama');
    if (yearFilterHama) {
      yearFilterHama.addEventListener('change', function() {
        const month = document.getElementById('monthFilterLineChartHama')?.value || 'all';
        const lapisanPengaman = document.getElementById('lapisanPengamanFilterLineChartHama')?.value || 'all';
        loadLineChartHamaData(month, this.value, lapisanPengaman);
      });
    }
  }

  // Add event listener for the "Show All Methods" button
  document.addEventListener('DOMContentLoaded', function() {
    document.body.addEventListener('click', function(e) {
      if (e.target && e.target.id === 'showAllInspeksiBtn') {
        if ($.fn.DataTable.isDataTable('#inspeksiTable')) {
          $('#inspeksiTable').DataTable().destroy();
        }
        
        var bulanText = document.getElementById('bulanInspeksi').textContent;
        var tahun = document.getElementById('tahunInspeksi').textContent;
        var bulanIndex = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                         'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']
                         .findIndex(month => month === bulanText) + 1;
        
        // Update title to indicate all methods are shown
        document.getElementById('namaMetodeInspeksi').innerHTML = '- Semua Metode - ';
        
        // Reinitialize datatable without method filter
        $('#inspeksiTable').DataTable({
          processing: true,
          serverSide: true,
          ajax: {
            url: route('inspeksi.dataperbulan'),
            type: 'GET',
            data: {
              bulan: bulanIndex,
              tahun: tahun
              // No metodeId here, to show all methods
            },
            error: function (xhr) {
              if (xhr.status === 401) {
                window.location.href = '/login';
              }
            }
          },
          columns: [
            { data: 'id' },
            { data: 'tanggal' },
            { data: 'metode.metode_nama' },
            { data: 'lokasi.lokasi_nama' },
            { data: 'hama.hama_nama' },
            { data: 'user.name' },
            { data: 'jumlah' }
          ],
          dom: 'Bfrtip',
          buttons: [
            // Keep existing buttons configuration
            'print', 'csv', 'excel', 'pdf'
          ]
        });
      }
    });
  });

  // Donut Chart Hama
  // --------------------------------------------------------------------
  const donutChartHamaEl = document.querySelector('#donutChartHama'),
    donutChartHamaConfig = {
      chart: {
        height: 390,
        type: 'donut',
        events: {
          dataPointSelection: function (event, chartContext, config) {
            // Handle donut chart click events for hama data
            if (config.dataPointIndex !== -1) {
              console.log('Clicked on hama donut:', config.dataPointIndex);
            }
          }
        }
      },
      labels: [],
      series: [],
      colors: [],
      stroke: {
        show: false,
        curve: 'straight'
      },
      dataLabels: {
        enabled: true,
        formatter: function (val) {
          return val.toFixed(1) + '%';
        },
        style: {
          fontSize: '14px',
          fontFamily: 'Public Sans',
          fontWeight: 500,
          colors: ['#fff']
        }
      },
      legend: {
        show: true,
        position: 'bottom',
        markers: { offsetX: -3 },
        itemMargin: {
          vertical: 3,
          horizontal: 10
        },
        labels: {
          colors: legendColor,
          useSeriesColors: false
        }
      },
      plotOptions: {
        pie: {
          donut: {
            labels: {
              show: true,
              name: {
                fontSize: '2rem',
                fontFamily: 'Public Sans'
              },
              value: {
                fontSize: '1.2rem',
                color: legendColor,
                fontFamily: 'Public Sans',
                formatter: function (val) {
                  return parseInt(val);
                }
              },
              total: {
                show: true,
                fontSize: '1.5rem',
                color: headingColor,
                label: 'Total Hama',
                formatter: function (w) {
                  return w.globals.seriesTotals.reduce((a, b) => {
                    return a + b;
                  }, 0);
                }
              }
            }
          }
        }
      },
      tooltip: {
        custom: function ({ series, seriesIndex, dataPointIndex, w }) {
          const hamaName = w.globals.labels[seriesIndex];
          const value = series[seriesIndex];
          const percentage = ((value / w.globals.seriesTotals.reduce((a, b) => a + b, 0)) * 100).toFixed(1);
          
          return `
            <div class="tooltip-custom">
              <div class="tooltip-header">${hamaName}</div>
              <div class="tooltip-body">
                <div class="tooltip-item">
                  <span class="tooltip-label">Jumlah:</span>
                  <span class="tooltip-value">${value}</span>
                </div>
                <div class="tooltip-item">
                  <span class="tooltip-label">Persentase:</span>
                  <span class="tooltip-value">${percentage}%</span>
                </div>
              </div>
            </div>
          `;
        }
      },
      responsive: [
        {
          breakpoint: 992,
          options: {
            chart: {
              height: 380
            }
          }
        },
        {
          breakpoint: 576,
          options: {
            chart: {
              height: 320
            }
          }
        },
        {
          breakpoint: 420,
          options: {
            chart: {
              height: 280
            },
            legend: {
              show: false
            }
          }
        }
      ]
    };

  if (typeof donutChartHamaEl !== undefined && donutChartHamaEl !== null) {
    const donutChartHama = new ApexCharts(donutChartHamaEl, donutChartHamaConfig);
    donutChartHama.render();
    window.donutChartHama = donutChartHama;

    // Function to load donut chart hama data
    function loadDonutChartHamaData(month = 'all', year = null, lapisanPengaman = 'all') {
      const donutChartHamaContainer = document.querySelector('#donutChartHama');
      if (donutChartHamaContainer) {
        DashboardEnhancement.showChartLoading(donutChartHamaContainer);
      }
      
      const params = new URLSearchParams({
        month: month,
        year: year || new Date().getFullYear(),
        lapisan_pengaman: lapisanPengaman
      });
      
      fetch(`/dashboard/donuthama?${params}`)
        .then(response => response.json())
        .then(data => {
          if (data.series.length === 0 || data.labels.length === 0) {
            donutChartHamaContainer.innerHTML = '<div class="text-center p-4"><p>Tidak ada data hama untuk periode yang dipilih.</p></div>';
          } else {
            // Reset container if it was showing no data message
            if (donutChartHamaContainer.innerHTML.includes('Tidak ada data')) {
              donutChartHamaContainer.innerHTML = '';
              donutChartHama.render();
            }
            
            window.donutChartHama.updateOptions({
              labels: data.labels,
              series: data.series,
              colors: data.colors
            });
          }
          
          // Hide loading animation
          if (donutChartHamaContainer) {
            setTimeout(() => {
              DashboardEnhancement.hideChartLoading(donutChartHamaContainer);
              DashboardEnhancement.addChartAnimation(donutChartHamaContainer);
            }, 500);
          }
        })
        .catch(error => {
          console.error('Error loading donut chart hama data:', error);
          if (donutChartHamaContainer) {
            DashboardEnhancement.hideChartLoading(donutChartHamaContainer);
          }
        });
    }

    // Function to load security layers for donut chart filter
    function loadSecurityLayersForDonut() {
      fetch('/dashboard/security-layers')
        .then(response => response.json())
        .then(layers => {
          const lapisanSelect = document.getElementById('lapisanPengamanFilterDonutHama');
          if (lapisanSelect) {
            // Clear existing options except "Semua Lapisan"
            lapisanSelect.innerHTML = '<option value="all" selected>Semua Lapisan</option>';
            
            // Add security layer options
            layers.forEach(layer => {
              const option = document.createElement('option');
              option.value = layer;
              option.textContent = `Lapisan ${layer}`;
              lapisanSelect.appendChild(option);
            });
          }
        })
        .catch(error => {
          console.error('Error loading security layers for donut:', error);
        });
    }

    // Load initial data and security layers
    loadSecurityLayersForDonut();
    loadDonutChartHamaData();

    // Security layer filter change handler
    const lapisanPengamanFilterDonut = document.getElementById('lapisanPengamanFilterDonutHama');
    if (lapisanPengamanFilterDonut) {
      lapisanPengamanFilterDonut.addEventListener('change', function() {
        const month = document.getElementById('monthFilterDonutHama')?.value || 'all';
        const year = document.getElementById('yearFilterDonutHama')?.value || new Date().getFullYear();
        loadDonutChartHamaData(month, year, this.value);
      });
    }

    // Month filter change handler
    const monthFilterDonutHama = document.getElementById('monthFilterDonutHama');
    if (monthFilterDonutHama) {
      monthFilterDonutHama.addEventListener('change', function() {
        const year = document.getElementById('yearFilterDonutHama')?.value || new Date().getFullYear();
        const lapisanPengaman = document.getElementById('lapisanPengamanFilterDonutHama')?.value || 'all';
        loadDonutChartHamaData(this.value, year, lapisanPengaman);
      });
    }

    // Year filter change handler
    const yearFilterDonutHama = document.getElementById('yearFilterDonutHama');
    if (yearFilterDonutHama) {
      yearFilterDonutHama.addEventListener('change', function() {
        const month = document.getElementById('monthFilterDonutHama')?.value || 'all';
        const lapisanPengaman = document.getElementById('lapisanPengamanFilterDonutHama')?.value || 'all';
        loadDonutChartHamaData(month, this.value, lapisanPengaman);
      });
    }
  }
})();
