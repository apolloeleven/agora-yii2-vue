<template>
  <div class="list-group-item d-flex flex-column">
    <b-collapse v-model="visible" v-if="department.children && department.children.length > 0" class="order-2">
      <department-list-group-item v-for="department in department.children"
                                  :edit-handler="editHandler" :delete-handler="deleteHandler"
                                  :department="department" :key="`department-row-${department.id}`"/>
    </b-collapse>
    <div class="d-flex justify-content-between align-items-center order-1 tree-item">
      <div>
        <h5 class="mb-0 d-inline-block mr-2" @click="onItemClick">
          <span class="tree-icon">
            <i v-if="department.children.length" class="fas fa-caret-right"></i>
          </span>
          {{ department.name }}
        </h5>
<!--        <b-badge pill variant="success">{{ department.children.length }}</b-badge>-->
      </div>
      <div>
        <b-button variant="outline-primary" size="sm" class="mr-2" v-b-tooltip.hover :title="$t('Edit Department')"
                  @click="onEditDepartment($event, department)">
          <i class="fas fa-edit"></i>
        </b-button>
        <b-button variant="outline-danger" size="sm" v-b-tooltip.hover :title="$t('Delete Department')"
                  @click="onDeleteDepartment($event, department)">
          <i class="fas fa-trash-alt"></i>
        </b-button>
      </div>
    </div>

  </div>
</template>

<script>
export default {
  name: "DepartmentListGroupItem",
  props: {
    department: {
      type: Object,
      required: true
    },
    deleteHandler: Function,
    editHandler: Function
  },
  data() {
    return {
      visible: false
    }
  },
  methods: {
    onItemClick($event) {
      $event.stopPropagation();
      if (this.department.children.length > 0) {
        this.visible = !this.visible;
      }
    },
    onEditDepartment($event, department) {
      this.editHandler(department)
    },
    onDeleteDepartment($event, department) {
      this.deleteHandler(department)
    }
  }
}
</script>

<style scoped lang="scss">
.list-group-item {
  padding: 0.25rem 0 0.25rem 1rem;
  h5 {
    &:hover {
      cursor: pointer;
    }
  }

  .collapse {
    & + .tree-item {
      .tree-icon {
        i {
          transition: all 0.3s;
        }
      }
    }
  }

  .collapse.show {
    & + .tree-item {
      .tree-icon {
        i {
          transform: rotate(90deg);
        }
      }
    }
  }
}

.tree-icon {
  display: inline-block;
  width: 16px;
}
</style>
