<template>
  <ValidationObserver ref="form" v-slot="{ handleSubmit, invalid ,reset}">
    <b-modal :visible='showModal' size="s" id="user-form" ref="modal" :title="$t('This operation can not be undone.')"
             @hidden="hideModal" @ok.prevent="onSubmit" :ok-title="$t('Delete')" ok-variant="danger"
             :cancel-title="$t('Cancel')" no-enforce-focus scrollable>
      <b-form @submit.prevent="onSubmit" @reset="onReset" novalidate>
        <div class="row">
          <div class="col">
            <b-alert show variant="danger">
              {{ $t(`Are you sure you want to delete this user {name}?`, {name: `"${user.displayName}"`}) }}
            </b-alert>
          </div>
        </div>
      </b-form>
    </b-modal>
  </ValidationObserver>
</template>

<script>
import {createNamespacedHelpers} from 'vuex';

const {mapState, mapActions} = createNamespacedHelpers('user');

export default {
  name: "UserDeleteForm",
  components: {},
  data() {
    return {
      //TODO select user
      error: false
    }
  },
  computed: {
    ...mapState({
      showModal: state => state.deleteForm.showModal,
      user: state => state.deleteForm.deletedUser
    }),
  },
  methods: {
    ...mapActions(['closeDeleteForm', 'delete']),
    async onSubmit(evt) {
      if (evt) {
        evt.preventDefault();
      }
      const response = await this.delete({userId: this.user.id});
      if (response.success) {
        this.$notify({
          group: 'success',
          type: 'success',
          title: this.$t('Success'),
          text: this.$t(`The user "{user}" was successfully deleted`, {user: this.user.displayName})
        });
      } else {
        this.$notify({
          group: 'error',
          type: 'error',
          title: this.$t('Error'),
          text: this.$t(`The user "{user}" was not deleted`, {user: this.user.displayName})
        });
      }
      this.$nextTick(() => {
        this.hideModal();
      });

    },
    hideModal() {
      this.error = false;
      this.closeDeleteForm();
      this.selectedId = null;
    },
    onReset(evt) {
      evt.preventDefault();
      this.form = {};
    },
  }
}

</script>


<style lang="scss" scoped>
</style>

