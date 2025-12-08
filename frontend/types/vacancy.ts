export interface Vacancy {
    id: number
    title: string
    description: string
    salary: number
    link: string
}

export interface CreateVacancyResponse {
    result: 'success' | 'error'
    id?: number
    errors?: Record<string, string[]>
}

export interface PaginationHeaders {
    total: number
    pageCount: number
    currentPage: number
    perPage: number
}

export interface VacancyResponse {
    vacancies: Vacancy[]
    pagination: PaginationHeaders
}