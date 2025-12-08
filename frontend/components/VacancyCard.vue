<template>
  <div class="card vacancy-card h-100">
    <div class="card-body">
      <h5 class="card-title">{{ vacancy.title }}</h5>
      <p class="card-text text-muted">{{ vacancy.description.substring(0, 100) }}...</p>
      <div class="d-flex justify-content-between align-items-center">
        <span class="badge bg-success fs-6">{{ formatSalary(vacancy.salary) }}</span>
        <NuxtLink :to="`/vacancies/${vacancy.id}`" class="btn btn-primary">
          Подробнее
        </NuxtLink>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { Vacancy } from '~/types/vacancy'

interface Props {
  vacancy: Vacancy
}

const props = defineProps<Props>()

const formatSalary = (salary: number) => {
  return new Intl.NumberFormat('ru-RU', {
    style: 'currency',
    currency: 'RUB',
    minimumFractionDigits: 0
  }).format(salary)
}
</script>

<style scoped>
.vacancy-card {
  transition: transform 0.2s;
}

.vacancy-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}
</style>