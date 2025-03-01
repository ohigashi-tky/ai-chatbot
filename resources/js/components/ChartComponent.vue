<template>
  <div class="chart-container" style="position: relative; height: 300px; width: 100%;">
    <canvas ref="chartCanvas"></canvas>
  </div>
</template>

<script setup>
import { ref, onMounted, watch, nextTick } from 'vue';
import Chart from 'chart.js/auto';

const props = defineProps({
  chartData: {
    type: Object,
    required: true
  },
  chartType: {
    type: String,
    default: 'bar'
  },
  chartOptions: {
    type: Object,
    default: () => ({
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          labels: {
            font: {
              size: 14
            }
          }
        },
        title: {
          font: {
            size: 16
          }
        }
      },
      scales: {
        x: {
          ticks: {
            font: {
              size: 14
            }
          }
        },
        y: {
          ticks: {
            font: {
              size: 14
            }
          }
        }
      }
    })
  }
});

const chartCanvas = ref(null);
let chartInstance = null;

const createChart = () => {
  if (chartInstance) {
    chartInstance.destroy();
  }
  
  const ctx = chartCanvas.value.getContext('2d');
  chartInstance = new Chart(ctx, {
    type: props.chartType,
    data: props.chartData,
    options: {
      ...props.chartOptions,
      responsive: true,
      maintainAspectRatio: false
    }
  });
};

onMounted(() => {
  createChart();
});

watch(() => props.chartData, () => {
  nextTick(() => {
    createChart();
  });
}, { deep: true });

watch(() => props.chartType, () => {
  nextTick(() => {
    createChart();
  });
});
</script>

<style scoped>
.chart-container {
  margin: 0 auto;
}
</style>
