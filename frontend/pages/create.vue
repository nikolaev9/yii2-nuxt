<template>
  <div class="container mt-4">
    <div class="mb-4">
      <NuxtLink to="/" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Назад к списку
      </NuxtLink>
    </div>

    <div class="card">
      <div class="card-header">
        <h2 class="mb-0">Создание новой вакансии</h2>
      </div>
      <div class="card-body">
        <VacancyForm />
      </div>
    </div>
  </div>
</template>