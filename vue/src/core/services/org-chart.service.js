import httpService from "./httpService";

export default {
    async getChartDataByCountry(countryId) {
        //TODO: Add url when backend will be ready
        return await httpService.get('', countryId);
    },
}