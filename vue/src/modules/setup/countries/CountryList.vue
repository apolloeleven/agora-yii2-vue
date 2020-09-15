<template>
  <div id="country-list" class="p-3">
    <h1>Countries</h1>

    <b-table striped hover :items="countries.data" :fields="fields">
      <template v-slot:cell(created_at)="data">
        {{ data.item.created_at | toDatetime }}
      </template>
      <template v-slot:cell(createdBy)="data">
        {{ data.item.createdBy.email }}
      </template>
      <template v-slot:cell(actions)="data">
        <b-button variant="outline-primary" size="sm" class="mr-2"  v-b-tooltip.hover :title="$t('Edit Country')">
          <i class="fas fa-edit"></i>
        </b-button>
        <b-button variant="outline-danger" size="sm" v-b-tooltip.hover :title="$t('Delete Country')">
          <i class="fas fa-trash-alt"></i>
        </b-button>
      </template>
    </b-table>
  </div>
</template>

<script>
import {createNamespacedHelpers} from 'vuex';

const {mapActions, mapState} = createNamespacedHelpers('setup');
export default {
  name: "CountryList",
  data() {
    return {
      fields: [
        'name',
        'created_at',
        'createdBy',
        'actions'
      ],
    }
  },
  computed: {
    ...mapState(['countries'])
  },
  methods: {
    ...mapActions(['getCountries'])
  },
  mounted() {
    this.getCountries();
  }
}
</script>

<style scoped>

</style>