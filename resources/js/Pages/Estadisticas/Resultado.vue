<script setup>
import { onMounted, ref } from "vue";
import { Chart, registerables } from "chart.js";
import AppLayout from "@/Layouts/AppLayout.vue";

Chart.register(...registerables);

const props = defineProps({
    datos: Array,
});

const chartRef = ref(null);
let chartInstance = null;

onMounted(() => {
    const nombres = props.datos.map(d => d.nombre);
    const visitas = props.datos.map(d => d.visitas);

    const colores = [
        '#FF5733', '#2CC6E2', '#2CA8E2', '#E2DA2C', '#E29A2C',
        '#582CE2', '#2CE276', '#2C3FE2', '#E22C66'
    ];

    const ctx = chartRef.value.getContext("2d");

    chartInstance = new Chart(ctx, {
        type: "bar",
        data: {
            labels: nombres,
            datasets: [
                {
                    label: "Visitas",
                    data: visitas,
                    backgroundColor: colores,
                    borderWidth: 1,
                },
            ],
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    precision: 0,
                },
            },
        },
    });
});
</script>

<template>
    <AppLayout>
        <div class="container">
            <h2 class="mb-4">Número de Visitas por Caso de Uso</h2>
            <canvas ref="chartRef"></canvas>
        </div>
    </AppLayout>
</template>
