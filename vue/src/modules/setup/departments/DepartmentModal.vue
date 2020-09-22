<template>
  <ValidationObserver ref="departmentModal">
    <b-modal id="department-modal"
             :title="this.model.id ? $t('Edit department') : $t('Add new department')"
             :visible="departmentModal.show"
             @shown="onShown"
             @hidden="onHidden"
             @ok="onSubmit">
      <form v-on:submit.prevent="onSubmit">
        <input-widget ref="nameInputWidget" :model="model" attribute="name"/>
        <input-widget type="select" :model="model" attribute="country_id" :select-options="countries.map(c => ({value: c.id, text: c.name}))"/>
        <input-widget type="select" :model="model" attribute="parent_id" :select-options="computedDepartments"/>
      </form>
    </b-modal>
  </ValidationObserver>
</template>

<script>

import {createNamespacedHelpers} from 'vuex';
import DepartmentModel from "@/modules/setup/departments/DepartmentModel";
import InputWidget from "@/core/components/input-widget/InputWidget";
import i18n from "@/shared/i18n";

const {mapState, mapActions} = createNamespacedHelpers('setup');
export default {
  name: "DepartmentModal",
  components: {InputWidget},
  data() {
    return {
      model: new DepartmentModel()
    }
  },
  computed: {
    ...mapState({
      countries: state => state.countries.data,
      departments: state => state.departments.data,
      departmentModal: state => state.departmentModal,
      department: state => state.departmentModal.data
    }),
    computedDepartments () {
      return [{value: null, text: `--- ${i18n.t('Select parent department')} ---`}, ...this.departments.map(c => ({value: c.id, text: c.name}))]
    }
  },
  watch: {
    department() {
      if (this.department && this.department.id) {
        this.model = new DepartmentModel(this.department);
      }
    }
  },
  methods: {
    ...mapActions(['hideDepartmentModal', 'saveDepartment']),
    async onSubmit(ev) {
      ev.preventDefault()
      const {success, body} = await this.saveDepartment(this.model);
      if (success) {
        this.hideDepartmentModal();
      } else {
        this.model.setMultipleErrors(body)
      }
    },
    onShown() {
      this.$refs.nameInputWidget.focus()
    },
    onHidden() {
      this.model = new DepartmentModel();
      this.hideDepartmentModal();
    },
  }
}
</script>

<style lang="scss" scoped>

</style>