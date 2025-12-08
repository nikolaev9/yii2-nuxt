<template>
  <div class="container mt-4">
    <div class="mb-4">
      <NuxtLink to="/" class="btn btn-outline-secondary">
        Назад к списку
      </NuxtLink>
    </div>

    <div v-if="loading" class="text-center my-5">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Загрузка...</span>
      </div>
    </div>

    <div v-else-if="vacancy" class="card">
      <div class="card-body">
        <h1 class="card-title mb-4">{{ vacancy.title }}</h1>

        <div class="mb-4">
          <h5 class="text-muted">Описание:</h5>
          <p class="card-text">{{ vacancy.description }}</p>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="card bg-light">
              <div class="card-body">
                <h6 class="card-subtitle mb-2 text-muted">Зарплата</h6>
                <h4 class="card-title text-success">{{ formatSalary(vacancy.salary) }}</h4>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="card bg-light">
              <div class="card-body">
                <h6 class="card-subtitle mb-2 text-muted">ID вакансии</h6>
                <h4 class="card-title">#{{ vacancy.id }}</h4>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div v-else class="alert alert-danger">
      Вакансия не найдена
    </div>
  </div>
</template>

<script setup lang="ts">
import type { Vacancy } from '~/types/vacancy'

const route = useRoute()
const vacancy = ref<Vacancy | null>(null)
const loading = ref(true)

const { getVacancy } = useVacanciesApi()

const formatSalary = (salary: number) => {
  return new Intl.NumberFormat('ru-RU', {
    style: 'currency',
    currency: 'RUB',
    minimumFractionDigits: 0
  }).format(salary)
}

onMounted(async () => {
  try {
    const id = parseInt(route.params.id as string)
    if (!isNaN(id)) {
      vacancy.value = await getVacancy(id)
    }
  } catch (error) {
    console.error('Error fetching vacancy:', error)
  } finally {
    loading.value = false
  }
})
</script>