'use strict';

// import { event } from 'jquery';
// Import Swiper JS dan CSS
import Swiper from 'swiper/bundle';
// import 'swiper/swiper-bundle.css';

(function () {
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
        events: {}
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
            categories: response.categories
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

            if (dataPointIndex !== -1) {
              if (dataPointIndex === 0) {
                document.getElementById('bulanInspeksi').innerHTML = 'Januari';
              } else if (dataPointIndex === 1) {
                document.getElementById('bulanInspeksi').innerHTML = 'Februari';
              } else if (dataPointIndex === 2) {
                document.getElementById('bulanInspeksi').innerHTML = 'Maret';
              } else if (dataPointIndex === 3) {
                document.getElementById('bulanInspeksi').innerHTML = 'April';
              } else if (dataPointIndex === 4) {
                document.getElementById('bulanInspeksi').innerHTML = 'Mei';
              } else if (dataPointIndex === 5) {
                document.getElementById('bulanInspeksi').innerHTML = 'Juni';
              } else if (dataPointIndex === 6) {
                document.getElementById('bulanInspeksi').innerHTML = 'Juli';
              } else if (dataPointIndex === 7) {
                document.getElementById('bulanInspeksi').innerHTML = 'Agustus';
              } else if (dataPointIndex === 8) {
                document.getElementById('bulanInspeksi').innerHTML = 'September';
              } else if (dataPointIndex === 9) {
                document.getElementById('bulanInspeksi').innerHTML = 'Oktober';
              } else if (dataPointIndex === 10) {
                document.getElementById('bulanInspeksi').innerHTML = 'November';
              } else if (dataPointIndex === 11) {
                document.getElementById('bulanInspeksi').innerHTML = 'Desember';
              }

              if ($.fn.DataTable.isDataTable('#inspeksiTable')) {
                $('#inspeksiTable').DataTable().destroy();
              }

              var tahun = document.getElementById('yearFilterLineChart').value;
              document.getElementById('tahunInspeksi').innerHTML = tahun;

              //tabel inspeksi
              $('#inspeksiTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                  url: route('inspeksi.dataperbulan'),
                  type: 'GET',
                  data: {
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
                    renderer: function (_, __, columns) {
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
        enabled: true
      },
      stroke: {
        curve: 'straight'
      },
      colors: [config.colors.warning],
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
      tooltip: {
        custom: function ({ series, seriesIndex, dataPointIndex, w }) {
          return '<div class="px-3 py-2">' + '<span>' + series[seriesIndex][dataPointIndex] + '</span>' + '</div>';
        }
      },
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
      }
    };

  if (typeof lineChartEl !== undefined && lineChartEl !== null) {
    const lineChart = new ApexCharts(lineChartEl, lineChartConfig);
    lineChart.render();

    // $.getJSON('/dashboard/linechart', function (response) {
    //   lineChart.updateOptions({
    //     xaxis: {
    //       categories: response.categories
    //     },
    //     series: [
    //       {
    //         name: 'Inspeksi',
    //         data: response.data
    //       }
    //     ]
    //   });
    // });

    // Function to initialize/update the chart
    const updateChart = year => {
      // Show loading animation
      const lineChartContainer = document.querySelector('#lineChart');
      if (lineChartContainer) {
        lineChartContainer.classList.add('chart-loading');
        // Create and add loading spinner if it doesn't exist
        if (!lineChartContainer.querySelector('.chart-spinner')) {
          const spinner = document.createElement('div');
          spinner.className = 'chart-spinner';
          spinner.innerHTML =
            '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>';
          lineChartContainer.appendChild(spinner);
        } else {
          lineChartContainer.querySelector('.chart-spinner').style.display = 'flex';
        }
      }

      fetch(`/dashboard/linechart/${year}`)
        .then(response => response.json())
        .then(data => {
          const options = {
            chart: {
              height: 350,
              type: 'line',
              zoom: {
                enabled: false
              },
              toolbar: {
                show: true
              }
            },
            series: [
              {
                name: 'Inspeksi',
                data: data.data
              }
            ],
            xaxis: {
              categories: data.categories
            }
          };

          if (lineChart) {
            lineChart.updateOptions(options);
          } else {
            lineChart = new ApexCharts(document.querySelector('#lineChart'), options);
            lineChart.render();
          }

          // Hide loading animation
          if (lineChartContainer) {
            setTimeout(() => {
              lineChartContainer.classList.remove('chart-loading');
              const spinner = lineChartContainer.querySelector('.chart-spinner');
              if (spinner) {
                spinner.style.display = 'none';
              }
            }, 500); // Small delay to ensure smooth transition
          }
        })
        .catch(error => {
          console.error('Error fetching line chart data:', error);
          // Hide loading on error too
          if (lineChartContainer) {
            lineChartContainer.classList.remove('chart-loading');
            const spinner = lineChartContainer.querySelector('.chart-spinner');
            if (spinner) {
              spinner.style.display = 'none';
            }
          }
        });
    };

    // Initialize chart with current year
    updateChart(new Date().getFullYear());

    // Add event listener for year change
    document.getElementById('yearFilterLineChart').addEventListener('change', function () {
      updateChart(this.value);
    });
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
          return parseInt(val, 10) + '%';
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
                  return parseInt(val, 10) + '%';
                }
              },
              total: {
                show: true,
                fontSize: '1.5rem',
                color: headingColor,
                label: 'Inspeksi',
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

  if (typeof donutChartEl !== undefined && donutChartEl !== null) {
    const donutChart = new ApexCharts(donutChartEl, donutChartConfig);
    donutChart.render();

    // Function to update the donut chart based on month and year
    const updateDonutChart = (month, year) => {
      // Show loading animation
      const donutChartContainer = document.querySelector('#donutChart');
      if (donutChartContainer) {
        donutChartContainer.classList.add('chart-loading');
        // Create and add loading spinner if it doesn't exist
        if (!donutChartContainer.querySelector('.chart-spinner')) {
          const spinner = document.createElement('div');
          spinner.className = 'chart-spinner';
          spinner.innerHTML =
            '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>';
          donutChartContainer.appendChild(spinner);
        } else {
          donutChartContainer.querySelector('.chart-spinner').style.display = 'flex';
        }
      }

      $.ajax({
        url: '/dashboard/donutchart',
        type: 'GET',
        dataType: 'json',
        data: {
          bulan: month,
          tahun: year
        },
        success: function (response) {
          // Remove any existing no-data messages first
          const existingMessage = donutChartContainer.querySelector('.no-data-message');
          if (existingMessage) {
            existingMessage.remove();
          }

          if (response.value && response.value.some(val => val > 0)) {
            // We have valid data, update the chart
            donutChart.updateOptions({
              labels: response.metode,
              series: response.value,
              colors: response.colors,
              metodeIds: response.metodeIds
            });
          } else {
            // Handle empty data or all zeroes
            if (donutChart) {
              donutChart.updateOptions({
                labels: [],
                series: [],
                colors: []
              });
            }

            // Create and add no-data message with better styling
            const noDataEl = document.createElement('div');
            noDataEl.className =
              'no-data-message position-absolute top-50 start-50 translate-middle text-center w-100 px-4';
            noDataEl.style.zIndex = '20'; // Higher zIndex to ensure visibility
            noDataEl.innerHTML = `
              <div class="card shadow-none bg-transparent">
                <div class="card-body">
                  <i class="bx bx-pie-chart-alt text-muted mb-3" style="font-size: 3rem;"></i>
                  <h5 class="text-muted">No Data Available</h5>
                  <p class="text-muted mb-0">There is no inspection data for ${month === 'all' ? 'all months in' : 'month ' + month + ' of'} ${year}</p>
                  <button class="btn btn-outline-primary btn-sm mt-3" onclick="document.getElementById('monthFilterDonutChart').value='all'; document.getElementById('monthFilterDonutChart').dispatchEvent(new Event('change'));">
                    View All Months
                  </button>
                </div>
              </div>
            `;
            donutChartContainer.appendChild(noDataEl);
          }
        },
        error: function (_, __, errorMsg) {
          console.error('Error fetching donut chart data:', errorMsg);

          // Remove existing no-data message if it exists
          if (donutChartContainer.querySelector('.no-data-message')) {
            donutChartContainer.querySelector('.no-data-message').remove();
          }

          // Clear any existing chart content first
          if (donutChart) {
            donutChart.updateOptions({
              labels: [],
              series: [],
              colors: []
            });
          }

          // Create and add error message with better styling
          const errorEl = document.createElement('div');
          errorEl.className =
            'no-data-message position-absolute top-50 start-50 translate-middle text-center w-100 px-4';
          errorEl.style.zIndex = '10'; // Ensure it appears on top
          errorEl.innerHTML = `
            <div class="card shadow-none bg-transparent">
              <div class="card-body">
                <i class="bx bx-error-circle text-danger mb-3" style="font-size: 3rem;"></i>
                <h5 class="text-danger">Failed to Load Data</h5>
                <p class="text-muted mb-0">An error occurred while retrieving the chart data</p>
                <button class="btn btn-outline-primary btn-sm mt-3" onclick="updateDonutChart('${month}', '${year}')">
                  Try Again
                </button>
              </div>
            </div>
          `;
          donutChartContainer.appendChild(errorEl);
        },
        complete: function () {
          // Wait a bit for the chart to render before checking for data
          setTimeout(() => {
            // Check if the chart has no data by looking for .apexcharts-series that has data
            const hasData = donutChartContainer.querySelectorAll('.apexcharts-series path[stroke-width]').length > 0;

            // Only show no-data message if we have no data AND don't already have a message
            if (!hasData && !donutChartContainer.querySelector('.no-data-message')) {
              const noDataEl = document.createElement('div');
              noDataEl.className =
                'no-data-message position-absolute top-50 start-50 translate-middle text-center w-100 px-4';
              noDataEl.style.zIndex = '20'; // Higher zIndex to ensure visibility
              noDataEl.innerHTML = `
                <div class="card shadow-none bg-transparent">
                  <div class="card-body">
                    <i class="bx bx-pie-chart-alt text-muted mb-3" style="font-size: 3rem;"></i>
                    <h5 class="text-muted">No Data Available</h5>
                    <p class="text-muted mb-0">There is no inspection data for ${month === 'all' ? 'all months in' : 'month ' + month + ' of'} ${year}</p>
                    <button class="btn btn-outline-primary btn-sm mt-3" onclick="document.getElementById('monthFilterDonutChart').value='all'; document.getElementById('monthFilterDonutChart').dispatchEvent(new Event('change'));">
                      View All Months
                    </button>
                  </div>
                </div>
              `;
              donutChartContainer.appendChild(noDataEl);
            }

            // Hide loading animation
            donutChartContainer.classList.remove('chart-loading');
            const spinner = donutChartContainer.querySelector('.chart-spinner');
            if (spinner) {
              spinner.style.display = 'none';
            }
          }, 500); // Small delay to ensure the chart has had time to render
        }
      });
    };

    // Initialize chart with current year and all months
    const currentDate = new Date();
    updateDonutChart('all', currentDate.getFullYear());

    // Add event listeners for filter changes
    document.getElementById('monthFilterDonutChart').addEventListener('change', function () {
      const year = document.getElementById('yearFilterDonutChart').value;
      updateDonutChart(this.value, year);
    });

    document.getElementById('yearFilterDonutChart').addEventListener('change', function () {
      const month = document.getElementById('monthFilterDonutChart').value;
      updateDonutChart(month, this.value);
    });
  }

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
              categories: data.categories
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
  const pieChartEl = document.querySelector('#pieChartTotal'),
    pieChartConfig = {
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

            // console.log(selectedLabel);

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
                          columns: [1, 2, 3, 4, 5],
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
                          columns: [1, 2, 3, 4, 5],
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
                          columns: [1, 2, 3, 4, 5],
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
                          columns: [1, 2, 3, 4, 5],
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
                          columns: [1, 2, 3, 4, 5],
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
                      var data = $.map(columns, function (col) {
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
                label: 'Total',
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

  const pieChartPGTEl = document.querySelector('#pieChartPGT'),
    pieChartPGTConfig = {
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
    const pieCharts = [
      { el: pieChartEl, config: pieChartConfig, url: '/dashboard/piecharttotal' },
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
})();
