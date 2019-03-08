<?php require_once(APPPATH.'views/header/head.php'); ?>

<div id="container2"></div>

<script type="text/javascript">
  $(function () {
  // $.getJSON('<?php //echo base_url("ot/ajax_ot_graph_bulan/")?>', function(data) {

  //   for (i = 0; i < data.length; i++){
  //     processed_json.push([data[i].name, data[i].y]);

  //     if (data[i].name == null) {
  //       notif.style.display = "block";
  //     }
  //   }

  $('#container2').highcharts({
    chart: {
      type: 'line'
    },
    title: {
      text: 'Overtime'
    },
    xAxis: {
      categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
    },
    yAxis: {
      title: {
        text: 'Total Jam (Jam)'
      }
    },
    plotOptions: {
      line: {
        dataLabels: {
          enabled: true
        },
        enableMouseTracking: false
      }
    },
    credits: {
      enabled: false
    },
    series: [{
      name: 'MIS',
      data: [7.0, 6.9, 9.5, 14.5, 18.4, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6]
    }, {
      name: 'PE',
      data: [3.9, 4.2, 5.7, 8.5, 11.9, 15.2, 17.0, 16.6, 14.2, 10.3, 6.6, 4.8]
    }]

  });
})
</script>


<?php require_once(APPPATH.'views/footer/foot.php'); ?>