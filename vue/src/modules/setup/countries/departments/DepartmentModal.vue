<template>
  <ValidationObserver ref="departmentModal">
    <b-modal id="department-modal"
             :title="this.model.id ? $t('Edit department') : $t('Add new department')"
             :visible="departmentModal.show"
             @shown="onShown"
             @hidden="onHidden"
             @ok="onSubmit">
      <form v-on:submit.prevent="onSubmit">
        <pre>{{ model }}</pre>
        <input-widget ref="nameInputWidget" :model="model" attribute="name"/>
        <input-widget type="select" :model="model" attribute="parent_id" :select-options="computedDepartments"/>
      </form>
    </b-modal>
  </ValidationObserver>
</template>

<script>

import {createNamespacedHelpers} from 'vuex';
import DepartmentModel from "./DepartmentModel";
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
  props: {
    country: Object
  },
  computed: {
    ...mapState({
      countries: state => state.countries.data,
      departments: state => state.departments.data,
      departmentModal: state => state.departmentModal,
      department: state => state.departmentModal.data
    }),
    computedDepartments() {
      const selectParent = {value: null, text: `--- ${i18n.t('Select parent department')} ---`};
      if (this.country) {
        return [selectParent, ...this.country.departments.map(c => ({value: c.id, text: c.name}))];
      }
      return [selectParent];
    }
  },
  watch: {
    department() {
      if (this.department && this.department.id) {
        this.model = new DepartmentModel(this.department);
      }
    },
    country() {
      if (this.country) {
        this.model.country_id = this.country.id;
      }
    }
  },
  methods: {
    ...mapActions(['hideDepartmentModal', 'saveDepartment', 'getCountryDepartments']),
    async onSubmit(ev) {
      ev.preventDefault()
      const {success, body} = await this.saveDepartment(this.model);
      if (success) {
        this.getCountryDepartments(this.countryId);
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

      if (this.country) {
        this.model.country_id = this.country.id;
      }
      this.hideDepartmentModal();
    },
  },
  mounted() {
    if (this.country) {
      this.model.country_id = this.country.id;
    }
  }
}
</script>

<style lang="scss" scoped>

</style>