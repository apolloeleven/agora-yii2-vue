<template>
  <div id="invitations" class="page page-with-table">
    <page-header :title="$t('Invitations')">
      <b-button @click="showInvitationModal" variant="info">
        <i class="fas fa-plus-circle"></i>
        {{ $t('Invite') }}
      </b-button>
    </page-header>
    <div class="content-wrapper p-3">
      <content-spinner :show="invitations.loading" :text="$t('Loading...')" class="h-100"/>
      <div v-if="!invitations.loading">
        <b-card no-body>
          <b-table v-if="invitations.data.length" responsive small striped hover id="users-table"
                   :items="invitations.data" :fields="fields">
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
            <template v-slot:cell(actions)="data">
              <i class="fas fa-pencil-alt mx-3 mx-sm-0 mx-md-3 mx-lg-0 text-primary hover-pointer" @click="editUser(data.item)" v-if="data.item.status !== PENDING_STATUS"/>
              <i class="far fa-trash-alt mx-3 text-danger hover-pointer" @click="deleteUser(data.item)"/>
            </template>
          </b-table>
          <no-data-available v-if="!invitations.data.length" height="100"/>
        </b-card>
      </div>
    </div>
    <user-invitation-form/>
  </div>
</template>

<script>
import {createNamespacedHelpers} from 'vuex';
import ContentSpinner from "../../../core/components/ContentSpinner";
import PageHeader from "@/core/components/PageHeader";
import UserInvitationForm from "@/modules/setup/invitations/UserInvitationForm";
import NoDataAvailable from "@/core/components/NoDataAvailable";
import {PENDING_STATUS} from "../../../constants";

const {mapState, mapActions} = createNamespacedHelpers('setup');
const {mapActions: mapEmployeeActions} = createNamespacedHelpers('employee');

export default {
  name: "UserInvitations",
  components: {NoDataAvailable, UserInvitationForm, PageHeader, ContentSpinner},
  data() {
    return {
      PENDING_STATUS: PENDING_STATUS
    }
  },
  computed: {
    ...mapState(['invitations']),
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
    ...mapActions(['getInvitations', 'showInvitationModal', 'deleteInvitation']),
    ...mapEmployeeActions(['showEmployeeModal', 'getModalDropdownData']),
    editUser(invitation) {
      this.showEmployeeModal(invitation.user);
    },
    async deleteUser(invitation) {
      const result = await this.$confirm(this.$t('Are you sure you want to delete this user?'),
        this.$t('This operation can not be undone'))
      if (result) {
        const {success, body} = await this.deleteInvitation(invitation);
        if (success) {
          this.$toast(this.$t(`User was successfully deleted`));
        } else {
          this.$toast(body.message, 'danger');
        }
      }
    },
  },
  mounted() {
    this.getModalDropdownData();
    this.getInvitations();
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
