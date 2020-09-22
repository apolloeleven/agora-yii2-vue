<template>
  <div id="users" class="d-flex flex-column">
    <div class="page-header">
      <b-nav pills>
        <b-nav-item :to="{name: 'user.list'}" active-class="active">{{ $t('Employees') }}</b-nav-item>
      </b-nav>
<!--      <b-button @click="showDialog" variant="info">-->
<!--        <i class="fa fa-plus-circle "></i>-->
<!--        {{ buttonText }}-->
<!--      </b-button>-->
    </div>
    <div class="page-content">
      <div v-if="loading" class="content-spinner text-center text-info my-2">
        <b-spinner class="align-middle"/>
        <strong>{{ $t('Loading...') }}</strong>
      </div>
      <router-view/>
    </div>
  </div>
</template>

<script>
import {createNamespacedHelpers} from 'vuex';

const {mapState, mapActions} = createNamespacedHelpers('user');

export default {
  name: "User",
  computed: {
    ...mapState(['loading']),
    buttonText() {
      return this.$t('Add new');
    }
  },
  methods: {
    ...mapActions(['showModal']),
    showDialog() {
      if (this.$route.name === 'user.list') {
        this.showModal();
      }
    }
  }
}
</script>

<style lang="scss" scoped>
#users {
  height: 100%;
}

.page-header {
  padding: 0 20px 0 0;
  display: flex;
  justify-content: space-between;

  & /deep/ .nav-pills {
    box-shadow: none;
    flex-wrap: nowrap;
  }

  & /deep/ .nav-link {
    padding-top: 1.1rem;
    padding-bottom: 1.1rem;
  }

  @media (max-width: 576px) {
    & /deep/ .nav-link {
      padding-left: 0.5rem;
      padding-right: 0.5rem;
    }
  }
}
</style>
