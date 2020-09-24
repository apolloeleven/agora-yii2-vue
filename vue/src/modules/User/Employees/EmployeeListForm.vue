<template>
  <ValidationObserver ref="form" v-slot="{ handleSubmit, invalid ,reset}">
    <b-modal
        :visible="showModal" id="user-form" ref="modal" :title='$t(`New Invitation`)' @hidden="onHideModal"
        @ok.prevent="handleSubmit(onSubmit)" :ok-disabled="loading" :ok-title="$t('Submit')" scrollable>
      <content-spinner :show="loading" :text="$t('Please wait...')" :fullscreen="true" class="h-100"/>
      <b-form @submit.prevent="handleSubmit(onSubmit)" novalidate>
        <input-widget :model="model" attribute="firstName"/>
        <input-widget :model="model" attribute="lastName"/>
        <input-widget :model="model" attribute="email"/>
      </b-form>
    </b-modal>
  </ValidationObserver>
</template>

<script>

import {createNamespacedHelpers} from "vuex";
import ContentSpinner from "../../../core/components/ContentSpinner";
import InputWidget from "../../../core/components/input-widget/InputWidget";
import EmployeeListFormModel from "./EmployeeListFormModel";

const {mapState, mapActions} = createNamespacedHelpers('user/employees');

export default {
  name: "EmployeeListForm",
  components: {ContentSpinner, InputWidget},
  data() {
    return {
      loading: false,
      model: new EmployeeListFormModel(),
    }
  },
  computed: {
    ...mapState({
      showModal: state => state.showModal,
      employeeData: state => state.modalEmployee
    })
  },
  watch: {
    employeeData() {
      this.model = new EmployeeListFormModel(this.employeeData.email, this.employeeData.firstName, this.employeeData.lastName);
    }
  },
  methods: {
    onHideModal() {
      this.model.email = null;
      this.model.email = null;
      this.hideModal();
    },
    async onSubmit() {

    }
  }
}
</script>

<style scoped>

</style>