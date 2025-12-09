# Yii2 + Nuxt3

## Запуск проекта

В корне
```bash
docker compose up -d
```

Запустятся mysql, backend, миграции для backend, frontend.

http://localhost:3000 (фронт) (открывать его)

http://localhost:8000 (бекенд)

Я не стал заморачиваться и писать docker-compose.yml для прода,
например фронт сейчас стартует через npm run dev.