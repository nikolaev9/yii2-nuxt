<template>
  <div>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
      <div class="container">
        <NuxtLink to="/" class="navbar-brand">
          Вакансии
        </NuxtLink>
        <div class="navbar-nav">
          <NuxtLink to="/" class="nav-link">Главная</NuxtLink>
          <NuxtLink to="/create" class="nav-link">Добавить вакансию</NuxtLink>
        </div>
      </div>
    </nav>

    <main>
      <NuxtPage />
    </main>
  </div>
</template>

<style>
body {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

main {
  flex: 1;
}

.navbar-nav .nav-link.router-link-active {
  font-weight: bold;
}
</style>