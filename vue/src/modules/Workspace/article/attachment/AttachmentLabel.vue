<template>
  <ValidationObserver ref="form" v-slot="{ handleSubmit, invalid ,reset}">
    <b-modal
      :visible="showModal" id="article-form" ref="modal" size="lg"
      :title="$t(`Change label for '{attachmentName}'`, {attachmentName: file.label || file.name})"
      @hidden="hideModal" @ok.prevent="handleSubmit(onSubmit)" :ok-title="$t('Submit')" scrollable>
      <b-form @submit.prevent="handleSubmit(onSubmit)" novalidate>
        <input-widget :model="model" attribute="label"></input-widget>
      </b-form>
    </b-modal>
  </ValidationObserver>
</template>

<script>
import InputWidget from "../../../../core/components/input-widget/InputWidget";
import AttachmentLabelModel from "./AttachmentLabelModel";
import {createNamespacedHelpers} from "vuex";

const {mapState, mapActions} = createNamespacedHelpers('article');

export default {
  name: "AttachmentLabel",
  components: {InputWidget},
  data() {
    return {
      model: new AttachmentLabelModel(),
    }
  },
  computed: {
    ...mapState({
      showModal: state => state.articleFile.showModal,
      file: state => state.articleFile.file || {},
    }),
  },
  watch: {
    file() {
      if (this.file.id) {
        this.model = new AttachmentLabelModel(this.file)
      }
    }
  },
  methods: {
    ...mapActions(['hideEditLabelDialog', 'updateLabel']),
    async onSubmit() {
      const res = await this.updateLabel({id: this.model.id, label: this.model.label});
      if (res.success) {
        this.$toast(this.$t(`Label was successfully changed`));
        this.hideModal();
      } else {
        this.$toast(res.body.message, 'danger');
      }
    },
    hideModal() {
      this.hideEditLabelDialog();
      this.model = new AttachmentLabelModel();
    },
  },
}
</script>

<style scoped>

</style>