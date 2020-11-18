<template>
  <div>
    <b-button v-if="model.length" class="float-right mr-2" id="show-employees" variant="default"
              @click="showPopover = !showPopover">
      <i class="fas fa-users"/>
      {{ $t('Users') }}
    </b-button>
    <b-popover custom-class="users-popover" ref="popover" :show="showPopover"
               target="show-employees" placement="bottom">
      <template v-slot:title>
        {{ $t('Workspace Users') }}
        <b-button @click="showPopover=false" class="close" aria-label="Close">
          <span class="d-inline-block" aria-hidden="true">&times;</span>
        </b-button>
      </template>
      <div class="contacts">
        <b-media v-for="(employee, index) in model" :key="index" class="contact">
          <h5 class="mt-0">{{ employee.first_name + ' ' + employee.last_name }}</h5>
        </b-media>
      </div>
    </b-popover>
  </div>
</template>

<script>
export default {
  name: "WorkspaceUsers",
  props: {
    model: Array,
  },
  data() {
    return {
      showPopover: false,
    }
  },
  watch: {
    '$route.params.id': function () {
      this.showPopover = false;
    },
  },
  destroyed() {
    this.showPopover = false;
  },
}
</script>

<style lang="scss" scoped>
@import "../../core/scss/variables";

.users-popover {
  width: 320px;
  max-height: 70vh;
  outline: 0;
  display: flex;
  flex-direction: column;
  z-index: 0;

  .contacts {
    width: 100%;
    overflow-y: auto;
  }

  .contact {
    padding: 0.5rem 1rem;

    &.selected {
      background-color: $info;
      color: white;
    }
  }

  & /deep/ .popover-body {
    padding: 0;
    overflow: hidden;
    display: flex;
    flex-direction: column;
  }
}
</style>