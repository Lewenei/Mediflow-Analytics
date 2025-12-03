<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">MediFlow Patients Dashboard</h1>
        <p class="mt-2 text-gray-600">Real-time hospital patient management system • 550+ active records</p>
      </div>

      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-blue-600 text-white p-6 rounded-lg shadow">
          <h3 class="text-lg font-semibold">Total Patients</h3>
          <p class="text-3xl font-bold mt-2">{{ patients.length }}</p>
        </div>
        <div class="bg-green-600 text-white p-6 rounded-lg shadow">
          <h3 class="text-lg font-semibold">Admitted</h3>
          <p class="text-3xl font-bold mt-2">{{ admittedCount }}</p>
        </div>
        <div class="bg-purple-600 text-white p-6 rounded-lg shadow">
          <h3 class="text-lg font-semibold">Male</h3>
          <p class="text-3xl font-bold mt-2">{{ maleCount }}</p>
        </div>
        <div class="bg-pink-600 text-white p-6 rounded-lg shadow">
          <h3 class="text-lg font-semibold">Female</h3>
          <p class="text-3xl font-bold mt-2">{{ femaleCount }}</p>
        </div>
      </div>

      <!-- Search & Filter -->
      <div class="bg-white p-4 rounded-lg shadow mb-6 flex flex-col sm:flex-row gap-4">
        <input
          v-model="search"
          type="text"
          placeholder="Search by name, phone or NHIF..."
          class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
        />
        <select v-model="wardFilter" class="px-4 py-2 border border-gray-300 rounded-lg">
          <option value="">All Wards</option>
          <option v-for="ward in wards" :key="ward" :value="ward">{{ ward }}</option>
        </select>
      </div>

      <!-- Patients Table -->
      <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-800 text-white">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Phone</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">NHIF</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Ward</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Age</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Status</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="patient in filteredPatients" :key="patient.id" class="hover:bg-gray-50 transition">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold">
                      {{ patient.first_name[0] }}
                    </div>
                    <div class="ml-4">
                      <div class="text-sm font-medium text-gray-900">
                        {{ patient.first_name }} {{ patient.last_name }}
                      </div>
                      <div class="text-sm text-gray-500">{{ patient.gender }}</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ patient.phone }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-700">{{ patient.nhif_number }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                    {{ patient.ward }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ calculateAge(patient.date_of_birth) }} yrs
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span :class="patient.is_admitted ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'"
                        class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full">
                    {{ patient.is_admitted ? 'Admitted' : 'Discharged' }}
                  </span>
                </td>
              </tr>
            </tbody>
          </table>

          <!-- Loading & Empty State -->
          <div v-if="loading" class="text-center py-12">
            <p class="text-gray-500">Loading patients from hospital database...</p>
          </div>
          <div v-else-if="filteredPatients.length === 0" class="text-center py-12">
            <p class="text-gray-500">No patients found matching your search.</p>
          </div>
        </div>
      </div>

      <div class="mt-8 text-center text-sm text-gray-500">
        MediFlow Analytics • Built with Laravel Lumen + Vue 3 + Tailwind • Deployed on Render
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'

const patients = ref([])
const loading = ref(true)
const search = ref('')
const wardFilter = ref('')

const wards = [
  'General Ward', 'Maternity', 'Surgical', 'Pediatric', 'ICU',
  'Oncology', 'Orthopedic', 'ENT', 'Eye Unit'
]

// Fetch patients from your Lumen API
onMounted(async () => {
  try {
    const response = await axios.get('/api/patients')
    patients.value = response.data
  } catch (error) {
    alert('Failed to load patients. Check your API route!')
  } finally {
    loading.value = false
  }
})

// Computed
const admittedCount = computed(() => patients.value.filter(p => p.is_admitted).length)
const maleCount = computed(() => patients.value.filter(p => p.gender === 'Male').length)
const femaleCount = computed(() => patients.value.filter(p => p.gender === 'Female').length)

const filteredPatients = computed(() => {
  return patients.value.filter(patient => {
    const matchesSearch = `${patient.first_name} ${patient.last_name} ${patient.phone} ${patient.nhif_number}`
      .toLowerCase()
      .includes(search.value.toLowerCase())

    const matchesWard = !wardFilter.value || patient.ward === wardFilter.value

    return matchesSearch && matchesWard
  })
})

const calculateAge = (dob) => {
  const birthDate = new Date(dob)
  const diff = Date.now() - birthDate.getTime()
  const ageDate = new Date(diff)
  return Math.abs(ageDate.getUTCFullYear() - 1970)
}
</script>