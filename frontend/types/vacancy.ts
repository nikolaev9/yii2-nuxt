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

export interface PaginationResponse {
    totalCount: number
    pageCount: number
    currentPage: number
    perPage: number
}

export interface VacancyListResponse {
    vacancies: Vacancy[]
    pagination: PaginationResponse
}