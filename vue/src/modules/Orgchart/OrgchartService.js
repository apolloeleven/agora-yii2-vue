import httpService from "../../core/services/httpService";

class OrgchartService {
  getOrgChartData(countryId) {
    return httpService.get('/v1/setup/country/org-chart-data', {
      params: {
        country_id: countryId,
        expand: 'userDepartments.user'
      }
    })
  }
}

const orgchartService = new OrgchartService();

export default orgchartService;