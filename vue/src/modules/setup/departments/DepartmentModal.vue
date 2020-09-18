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
      </form>
    </b-modal>
  </ValidationObserver>
</template>

<script>

import {createNamespacedHelpers} from 'vuex';
import DepartmentModel from "@/modules/setup/departments/DepartmentModel";
import InputWidget from "@/core/components/input-widget/InputWidget";

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
      departmentModal: state => state.departmentModal,
      department: state => state.departmentModal.data
    }),
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
      const {success} = await this.saveDepartment(this.model);
      if (success) {
        this.hideDepartmentModal();
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