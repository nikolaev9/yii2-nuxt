import type {CreateVacancyResponse, Vacancy, VacancyListResponse} from '~/types/vacancy'

export const useVacanciesApi = () => {
    const config = useRuntimeConfig()
    const apiBaseUrl = config.public.apiBaseUrl

    const getVacancies = async (
        page: number = 1,
        sort: string = '-created_at'
    ) => {
        try {
            return await $fetch<VacancyListResponse>(`${apiBaseUrl}/vacancies`, {
                method: 'GET',
                query: {
                    page,
                    sort
                },
                headers: {
                    'Accept': 'application/json'
                }
            })
        } catch (error) {
            console.error('Error fetching vacancies:', error)
            throw error
        }
    }

    const getVacancy = async (id: number) => {
        try {
            return await $fetch<Vacancy>(`${apiBaseUrl}/vacancies/${id}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                }
            })
        } catch (error) {
            console.error('Error fetching vacancy:', error)
            throw error
        }
    }

    const createVacancy = async (vacancyData: Omit<Vacancy, 'id' | 'link'>) => {
        try {
            return await $fetch<CreateVacancyResponse>(`${apiBaseUrl}/vacancies`, {
                method: 'POST',
                body: vacancyData,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
        } catch (error: any) {
            if (error.status === 400 && error.data) {
                return error.data as CreateVacancyResponse
            }

            console.error('Error creating vacancy:', error)
            throw error
        }
    }


    return {
        getVacancies,
        getVacancy,
        createVacancy
    }
}