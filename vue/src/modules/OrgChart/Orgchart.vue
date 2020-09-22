<template>
  <div style="width: 100%">
    <div style="width: 100%; align-content: center">
      <ul class="nav nav-tabs">
        <li class="nav-item" v-for="country in countries" :key="country.id">
          <button class="nav-link active" v-on:click.prevent="getDataForChart('US')">
            {{ country.name }}
          </button>
        </li>
      </ul>
    </div>
    <div style="align-content: center; width: 100%">
      <vo-basic :data="chartData" :verticalDepth="3" :pan="true" :zoom="true"></vo-basic>
    </div>
  </div>
</template>

<script>
import {VoBasic} from '../../core/components/org-chart/src/index'
import chart from '../../core/services/org-chart.service'

export default {
  name: "orgchart",
  components: {VoBasic},
  data() {
    return {
      countries: [
        {
          id: 1,
          name: 'US'
        },
        {
          id: 2,
          name: 'GB'
        },
        {
          id: 3,
          name: 'GE'
        }
      ],
      chartData: {
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
    }
  },
  created() {
  },
  methods: {
    async getDataForChart(code) {
      chart.getChartDataByCountry(code)
    }
  }
}
</script>

<style scoped>

</style>