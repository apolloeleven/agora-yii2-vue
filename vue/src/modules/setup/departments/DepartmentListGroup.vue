<template>
  <div class="list-group">
    <department-list-group-item v-for="department in departments" :department="department"
                                :edit-handler="editHandler" :delete-handler="deleteHandler"
                                :key="`department-row-${department.id}`"/>
  </div>
</template>

<script>
  import DepartmentListGroupItem from "@/modules/setup/departments/DepartmentListGroupItem";

  export default {
    name: "DepartmentListGroup",
    components: {DepartmentListGroupItem},
    props: {
      departments: {
        type: Array,
        default: () => []
      },
      deleteHandler: Function,
      editHandler: Function
    }
  }
</script>

<style scoped lang="scss">
  /deep/ .list-group-item {
    padding-right: 1em;
  }
</style>
