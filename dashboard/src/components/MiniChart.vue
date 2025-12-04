<script setup>
import { onMounted, ref } from 'vue'
import { Chart } from 'chart.js/auto'

const props = defineProps({
  data: { type: Array, required: true },
  color: { type: String, default: '#06b6d4' }
})

const canvas = ref(null)

onMounted(() => {
  new Chart(canvas.value, {
    type: 'line',
    data: {
      labels: props.data.map((_, i) => i + 1),
      datasets: [{
        data: props.data,
        borderColor: props.color,
        borderWidth: 2,
        tension: 0.4,
        pointRadius: 0
      }]
    },
    options: {
      plugins: { legend: { display: false } },
      scales: { x: { display: false }, y: { display: false } },
      responsive: true,
      maintainAspectRatio: false
    }
  })
})
</script>

<template>
  <div class="h-12 mt-4">
    <canvas ref="canvas"></canvas>
  </div>
</template>
