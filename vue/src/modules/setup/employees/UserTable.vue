<template>
  <div id="user-table" class="content-wrapper p-3">
    <content-spinner :show="loading" :text="$t('Loading...')" class="h-100"/>
    <div v-if="!loading">
      <b-card no-body>
        <b-table class="mb-0" responsive small hover id="users-table" :items="data" :fields="fields">
          <template v-slot:table-busy>
            <div class="text-center text-danger my-2">
              <b-spinner class="align-middle"></b-spinner>
              <strong>{{ $t('Loading...') }}</strong>
            </div>
          </template>
          <template v-slot:cell(avatar)="data">
            <b-img :src="data.item.image_url || '/assets/img/avatar.svg'" rounded="circle" width="32" height="32"/>
          </template>
          <template v-slot:cell(fullName)="data">
            <div style="width: 100%">
              {{ data.item.display_name }}
            </div>
          </template>
          <template v-slot:cell(role)="data">
            <b-form-select  style="width: auto;" v-model="data.item.role" @change="onRoleChange(data.item)"
                           :options="dropdownData.userRoles"></b-form-select>
          </template>
          <template v-slot:cell(verified)="data">
            <b-form-checkbox v-model="data.item.status" name="check-button" switch size="lg"
                             @change="$emit('status-change', data.item)"/>
          </template>
          <template v-slot:cell(actions)="data">
              <span v-b-tooltip.hover data-placement="top" :title="$t('Edit')">
                <i id="edit-tooltip"
                   class="fas fa-pencil-alt mr-3 text-primary hover-pointer"
                   @click="$emit('edit-click', data.item)"/>
              </span>
            <span v-b-tooltip.hover data-placement="top" :title="$t('Delete')">
                <i id="delete-tooltip"
                   class="far fa-trash-alt mr-3 text-danger hover-pointer"
                   @click="$emit('delete-click', data.item)"/>
                </span>
          </template>
        </b-table>
      </b-card>
    </div>
  </div>
</template>

<script>
import ContentSpinner from "../../../core/components/ContentSpinner";
import {createNamespacedHelpers} from "vuex";

const {mapState, mapActions} = createNamespacedHelpers('employee');
export default {
  name: "UserTable",
  components: {ContentSpinner},
  props: {
    loading: {
      type: Boolean,
      default: false
    },
    data: {
      type: Array,
      required: true
    },
    visibleFields: {
      type: Array,
      default: () => []
    },
    workspaceId: {
      type: Number
    }
  },
  data() {
    return {
      allFields: [
        {key: 'id', label: this.$t('ID')},
        {key: 'avatar', label: this.$t('Image')},
        {key: 'fullName', label: this.$t('Full Name')},
        {key: 'email', label: this.$t('Email')},
        {key: 'role', label: this.$t('Role')},
        {key: 'verified', label: this.$t('Verified')},
        {key: 'actions', label: this.$t('Actions')},
      ]
    }
  },
  computed: {
    ...mapState({

    }),
    fields() {
      return this.allFields.filter(f => this.visibleFields.length === 0 || this.visibleFields.includes(f.key));
    }
  },
  methods: {
    ...mapActions(['changeRole']),
    async onRoleChange(item) {
      const success = await this.changeRole({userId: item.id, workspaceId: this.workspaceId, role: item.role});
      if (success) {
        this.$successToast(this.$t(`{user} role has been changed into "{role}"`, {user: item.display_name, role: item.role}));
      }
    }
  }
}
</script>

<style lang="scss">
#user-table {
  td {
    vertical-align: middle;
  }
}
</style>