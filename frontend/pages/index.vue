<template>
  <div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1>Список вакансий</h1>
      <NuxtLink to="/create" class="btn btn-success">
        Добавить вакансию
      </NuxtLink>
    </div>
    
    <div class="card mb-4">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-md-4 mb-3 mb-md-0">
            <label class="form-label">Сортировка:</label>
            <select v-model="sortBy" class="form-select" @change="loadVacancies">
              <option value="-created_at">По дате (сначала новые)</option>
              <option value="created_at">По дате (сначала старые)</option>
              <option value="salary">По зарплате (по возрастанию)</option>
              <option value="-salary">По зарплате (по убыванию)</option>
            </select>
          </div>
        </div>
      </div>
    </div>

    <div v-if="loading" class="text-center my-5">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Загрузка...</span>
      </div>
    </div>

    <div v-else-if="vacancies.length" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
      <div class="col" v-for="vacancy in vacancies" :key="vacancy.id">
        <VacancyCard :vacancy="vacancy" />
      </div>
    </div>
    
    <div v-else class="alert alert-info">
      Вакансии не найдены
    </div>
    
    <nav v-if="pagination.pageCount > 1" aria-label="Page navigation" class="mt-4">
      <ul class="pagination justify-content-center">
        <li class="page-item" :class="{ disabled: pagination.currentPage === 1 }">
          <button class="page-link" @click="changePage(pagination.currentPage - 1)">
            Назад
          </button>
        </li>

        <li
            v-for="page in pagination.pageCount"
            :key="page"
            class="page-item"
            :class="{ active: page === pagination.currentPage }"
        >
          <button class="page-link" @click="changePage(page)">
            {{ page }}
          </button>
        </li>

        <li class="page-item" :class="{ disabled: pagination.currentPage === pagination.pageCount }">
          <button class="page-link" @click="changePage(pagination.currentPage + 1)">
            Вперед
          </button>
        </li>
      </ul>
    </nav>
    
    <div class="text-center text-muted mt-2">
      Показано {{ vacancies.length }} из {{ pagination.total }} вакансий
    </div>
  </div>
</template>

<script setup lang="ts">
import type { Vacancy } from '~/types/vacancy'

const vacancies = ref<Vacancy[]>([])
const loading = ref(true)
const sortBy = ref('-created_at')
const currentPage = ref(1)

const pagination = reactive({
  total: 0,
  pageCount: 0,
  currentPage: currentPage.value,
  perPage: 10
})

const { getVacancies } = useVacanciesApi()

const loadVacancies = async () => {
  try {
    loading.value = true
    const response = await getVacancies(currentPage.value, sortBy.value)
    vacancies.value = response.vacancies
    Object.assign(pagination, response.pagination)
  } catch (error) {
    console.error('Error loading vacancies:', error)
  } finally {
    loading.value = false
  }
}

const changePage = (page: number) => {
  if (page < 1 || page > pagination.pageCount) return
  currentPage.value = page
  loadVacancies()
}

onMounted(() => {
  loadVacancies()
})
</script>