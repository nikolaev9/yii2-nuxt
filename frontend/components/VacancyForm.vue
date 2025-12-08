<template>
  <div>
    <div v-if="error" class="alert alert-danger mb-3">
      <h6 class="alert-heading">Ошибки:</h6>
      <pre class="mb-0">{{ JSON.stringify(error, null, 2) }}</pre>
    </div>
    
    <form @submit.prevent="handleSubmit" class="needs-validation">
      <div class="mb-3">
        <label for="title" class="form-label">Название вакансии *</label>
        <input
            type="text"
            class="form-control"
            id="title"
            v-model="title"
            required
        />
      </div>

      <div class="mb-3">
        <label for="description" class="form-label">Описание *</label>
        <textarea
            class="form-control"
            id="description"
            v-model="description"
            rows="4"
            required
        ></textarea>
      </div>

      <div class="mb-3">
        <label for="salary" class="form-label">Зарплата (руб.) *</label>
        <input
            type="number"
            class="form-control"
            id="salary"
            v-model="salary"
            required
            min="0"
        />
      </div>

      <button type="submit" class="btn btn-primary" :disabled="loading">
        <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
        Создать вакансию
      </button>
    </form>
  </div>
</template>

<script setup lang="ts">
import { useVacanciesApi } from '~/composables/useVacanciesApi'

const router = useRouter()
const { createVacancy } = useVacanciesApi()

const title = ref('')
const description = ref('')
const salary = ref(0)

const error = ref<any>(null)
const loading = ref(false)

const handleSubmit = async () => {
  error.value = null
  loading.value = true

  try {
    const response = await createVacancy({
      title: title.value,
      description: description.value,
      salary: salary.value
    })

    if (response.result === 'success' && response.id) {
      await router.push(`/vacancies/${response.id}`)
    } else if (response.result === 'error') {
      error.value = response.errors
    }
  } catch (err: any) {
    error.value = { message: err.message || 'Ошибка при создании' }
  } finally {
    loading.value = false
  }
}
</script>