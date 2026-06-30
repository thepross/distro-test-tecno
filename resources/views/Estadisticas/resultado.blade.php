<!-- resources/views/reporte_visitas.blade.php -->

@extends('dashboard')

@section('content')
  <div class="container">
    <h2 class="mb-4">Número de Visitas por Caso de Uso</h2>
    <canvas id="graficoBarras"></canvas>
  </div>

  <script src="{{ asset('js/chart.js') }}"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      var data = @json($datos); // Convertir los datos de PHP a un objeto JavaScript
      var nombres = data.map(dato => dato.nombre);
      var visitas = data.map(dato => dato.visitas);

      var colores = [
        '#FF5733', '#2CC6E2', '#2CA8E2', '#E2DA2C', '#E29A2C', '#582CE2', '#2CE276', '#2C3FE2', '#E22C66'
      ];

      var ctx = document.getElementById('graficoBarras').getContext('2d');
      var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: nombres,
          datasets: [{
            label: 'Visitas',
            data: visitas,
            backgroundColor: colores, // Color cyan con opacidad
            borderWidth: 1,
          }]
        },
        options: {
          scales: {
            y: {
              beginAtZero: true,
              precision: 0
            }
          }
        }
      });
    });
  </script>
@endsection