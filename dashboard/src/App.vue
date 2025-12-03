<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import Chart from 'chart.js/auto'

const API_URL = 'https://mediflow-analytics.onrender.com/api'

const stats = ref({
  today: 0,
  trend: 0,
  nhif: 0,
  copay: 0,
  inpatients: 0
})

onMounted(async () => {
  const res = await axios.get(`${API_URL}/analytics/revenue-kpi`)
  stats.value = {
    today: res.data.today_revenue.toLocaleString(),
    trend: res.data.trend_percent,
    nhif: res.data.total_nhif_covered.toLocaleString(),
    copay: res.data.total_patient_copay.toLocaleString(),
    inpatients: res.data.total_inpatients
  }
})
</script>

<template>
  <div class="grid grid-cols-4 gap-6">
    <div class="bg-white p-8 rounded-xl shadow-lg">
      <h3 class="text-gray-600">Today's Revenue</h3>
      <p class="text-4xl font-bold text-emerald-600">KES {{ stats.today }}</p>
      <p :class="stats.trend > 0 ? 'text-green-600' : 'text-red-600'">
        {{ stats.trend > 0 ? '+' : '' }}{{ stats.trend }}% vs yesterday
      </p>
    </div>
    <div class="bg-white p-8 rounded-xl shadow-lg">
      <h3 class="text-gray-600">Inpatients</h3>
      <p class="text-4xl font-bold text-blue-600">{{ stats.inpatients }}</p>
    </div>
    <div class="bg-white p-8 rounded-xl shadow-lg">
      <h3 class="text-gray-600">NHIF Covered</h3>
      <p class="text-4xl font-bold text-indigo-600">KES {{ stats.nhif }}</p>
    </div>
    <div class="bg-white p-8 rounded-xl shadow-lg">
      <h3 class="text-gray-600">Patient Co-pay</h3>
      <p class="text-4xl font-bold text-amber-600">KES {{ stats.copay }}</p>
    </div>
  </div>
</template>