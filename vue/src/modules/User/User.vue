<template>
  <div id="users" class="d-flex flex-column">
    <div class="page-header">
      <b-nav pills>
        <b-nav-item :to="{name: 'user.invitations'}" active-class="active">
          {{ $t('Invitations') }}
        </b-nav-item>
      </b-nav>
      <b-button @click="showDialog" variant="info">
        <i class="fas fa-plus-circle"></i>
        {{ buttonText }}
      </b-button>
    </div>
    <div class="page-content">
      <content-spinner :show="loading" :text="$t('Loading...')" class="h-100"/>
      <router-view/>
    </div>
  </div>
</template>

<script>
import {createNamespacedHelpers} from 'vuex';
import ContentSpinner from "../../core/components/ContentSpinner";

const {mapState, mapActions} = createNamespacedHelpers('invitations');

export default {
  name: "User",
  components: {ContentSpinner},
  computed: {
    ...mapState(['loading']),
    buttonText() {
      if (this.$route.name === 'invitations') {
        return this.$t('New Invitation');
      }
      return this.$t('Add new');
    }
  },
  methods: {
    ...mapActions(['showInvitationModal']),
    showDialog() {
      this.showInvitationModal();
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
