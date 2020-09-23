<template>
  <div id="user" class="d-flex flex-column">
    <content-spinner :show="loading" :text="$t('Loading...')" class="h-100"/>
    <div v-if="!loading" id="employees" class="p-3">
      <b-card>
        <b-table responsive small striped hover id="users-table" :items="data.rows" :fields="fields">
          <template v-slot:table-busy>
            <div class="text-center text-danger my-2">
              <b-spinner class="align-middle"></b-spinner>
              <strong>{{ $t('Loading...') }}</strong>
            </div>
          </template>
          <template v-slot:cell(departments)="data">
            <div style="width: 100%">
            </div>
          </template>
<!--          <template v-slot:cell(created_at)="data">-->
<!--            {{ new Date(data.item.created_at) | toDatetime }}-->
<!--          </template>-->
<!--          <template v-slot:cell(created_by)="data">-->
<!--            {{ data.item.createdBy.displayName }}-->
<!--          </template>-->
<!--          <template v-slot:cell(statusLabel)="data">-->
<!--            <span class="badge" :class="{-->
<!--              'badge-warning': data.item.status === 2,-->
<!--              'badge-info': data.item.status === 1,-->
<!--              'badge-success': data.item.status === 3,-->
<!--            }">{{ data.value }}</span>-->
<!--          </template>-->
        </b-table>
      </b-card>
    </div>
  </div>

</template>

<script>
import {createNamespacedHelpers} from 'vuex';
import ContentSpinner from "../../../core/components/ContentSpinner";
const {mapState, mapActions} = createNamespacedHelpers('user/employees');

export default {
  name: "EmployeeList",
  components: {ContentSpinner},
  data() {
    return {}
  },
  computed: {
    ...mapState(['loading', 'data']),
    fields() {
      return [
        {key: 'id', label: this.$t('ID')},
        {key: 'fullName', label: this.$t('Full Name')},
        {key: 'email', label: this.$t('Email')},
        {key: 'departments', label: this.$t('Departments')},
        {key: 'country', label: this.$t('Country')},
        {key: 'position', label: this.$t('Position')},
        {key: 'actions', label: this.$t('Actions')},
      ]
    }
  },
  methods: {
    ...mapActions(['getData'])
  },
  mounted() {
    this.getData()
  }
}
</script>

<style scoped>

</style>