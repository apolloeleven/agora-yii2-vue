<template>
  <div id="user" class="d-flex flex-column">
    <content-spinner :show="loading" :text="$t('Loading...')" class="h-100"/>
    <div v-if="!loading" id="invitations" class="p-3">
      <b-card>
        <b-table responsive small striped hover id="users-table" :items="data.rows" :fields="fields">
          <template v-slot:table-busy>
            <div class="text-center text-danger my-2">
              <b-spinner class="align-middle"></b-spinner>
              <strong>{{ $t('Loading...') }}</strong>
            </div>
          </template>
          <template v-slot:cell(created_at)="data">
            {{ new Date(data.item.created_at) | toDatetime }}
          </template>
          <template v-slot:cell(created_by)="data">
            {{ data.item.createdBy.displayName }}
          </template>
          <template v-slot:cell(statusLabel)="data">
            <span class="badge" :class="{
              'badge-warning': data.item.status === 2,
              'badge-info': data.item.status === 1,
              'badge-success': data.item.status === 3,
            }">{{ data.value }}</span>
          </template>
        </b-table>
      </b-card>
    </div>
  </div>
</template>

<script>
import {createNamespacedHelpers} from 'vuex';
import ContentSpinner from "../../../core/components/ContentSpinner";

const {mapState, mapActions} = createNamespacedHelpers('invitation');

export default {
  name: "UserInvitations",
  components: {ContentSpinner},
  data() {
    return {}
  },
  computed: {
    ...mapState(['loading', 'data']),
    fields() {
      return [
        {key: 'id', label: this.$t('ID')},
        {key: 'email', label: this.$t('Email')},
        {key: 'statusLabel', label: this.$t('Status'), sortable: true},
        {key: 'created_at', label: this.$t('Invitation Date')},
        {key: 'created_by', label: this.$t('Invited By')},
        {key: 'use_date', label: this.$t('Registration Date')},
        {key: 'actions', label: this.$t('Actions')},
      ]
    }
  },
  methods: {
    ...mapActions(['getAll']),
  },
  mounted() {
    this.getAll();
  }
}
</script>

<style lang="scss">

@import "../../../core/scss/variables";

#invitations {
  .user-image {
    width: 48px;
    height: 48px;
  }

  td {
    vertical-align: middle;
  }
}

.pagination-table-info {
  font-size: 13.6px;
  font-weight: 400;
  line-height: 21px;

  span {
    font-weight: 700;
  }
}
</style>
