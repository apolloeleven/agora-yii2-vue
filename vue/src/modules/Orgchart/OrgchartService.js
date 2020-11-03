import httpService from "../../core/services/httpService";

class OrgchartService {
  getOrgChartData(countryId) {
    return new Promise((resolve) => {
      setTimeout(() => {
        resolve({
          success: true,
          body: {
            name: 'dep1',
            employees: [
              {
                name: 'test',
                surname: 'testes',
                imgSource: 'https://www.iconfinder.com/data/icons/avatars-and-biometry-data/154/online-status-web-person-avatar-512.png'
              },
              {
                name: 'Nika Gelashvili',
                surname: 'tesasdasdtes',
                imgSource: 'https://www.iconfinder.com/data/icons/avatars-and-biometry-data/154/online-status-web-person-avatar-512.png'
              }],
            children: [
              {
                name: 'dep6',
                employees: [
                  {name: 'test1', surname: 'test'},
                  {name: 'test2', surname: 'test'},
                  {name: 'test3', surname: 'test'},
                  {name: 'test4', surname: 'test'}
                ],
                children: [
                  {name: 'dep3', employees: []}
                ]
              },
              {
                name: 'dep15',
                employees: [
                  {name: 'Nikusha Koki Gelashvili', surname: 'test'},
                  {name: 'test2', surname: 'test'},
                  {name: 'test3', surname: 'test'},
                  {name: 'test4', surname: 'test'}
                ],
                children: [
                  {name: 'dep3', employees: [], children: [{name: 'dep3', employees: []}]}
                ]
              },
              {
                name: 'dep25',
                employees: [
                  {name: 'test1', surname: 'test'},
                  {name: 'test2', surname: 'test'},
                  {name: 'test3', surname: 'test'},
                  {name: 'test4', surname: 'test'}
                ],
                children: [
                  {name: 'dep3', employees: []},
                  {name: 'dep3', employees: []},
                  {name: 'dep3', employees: []}
                ]
              }
            ]
          }
        })
      }, 1000)
    })
  }
}

const orgchartService = new OrgchartService();

export default orgchartService;