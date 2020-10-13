<template>
  <ValidationObserver ref="form" v-slot="{ handleSubmit, invalid ,reset}">
    <b-modal :visible="showModal" id="timeline-form" ref="modal" size="lg"
             :title="$t('Write Something on Timeline')" @hidden="hideModal" @ok.prevent="handleSubmit(onSubmit)"
             :ok-disabled="loading || !model.description && !model.file" :ok-title="$t('Submit')" scrollable>
      <content-spinner :show="loading" :text="`${progress}% ${$t('Uploaded...')}`" class="h-100" :fullscreen="true"/>
      <b-form @submit.prevent="handleSubmit(onSubmit)" novalidate>

        <b-form-group :disabled="!!model.id" v-if="model.id">
          <b-form-select v-model="model.workspace_id" :options="userWorkspaceOptions"/>
        </b-form-group>

        <b-form-group v-if="!model.id" :label="$t('Select Image or Video File')">
          <b-form-file v-model="model.file"/>
        </b-form-group>

        <input-widget :model="model" attribute="description" type="richtext"/>
      </b-form>
    </b-modal>
  </ValidationObserver>
</template>

<script>
import {createNamespacedHelpers} from "vuex";
import ContentSpinner from "../../core/components/ContentSpinner";
import TimelineFormModel from "./TimelineFormModel";
import InputWidget from "../../core/components/input-widget/InputWidget";

const {mapState, mapActions} = createNamespacedHelpers('timeline');
const {mapState: mapStateWorkspace} = createNamespacedHelpers('workspace');

export default {
  name: "TimelineForm",
  components: {InputWidget, ContentSpinner},
  data() {
    return {
      model: new TimelineFormModel(),
      progress: 0,
    }
  },
  computed: {
    ...mapState(['loading', 'showModal', 'modalTimeline']),
    ...mapStateWorkspace(['workspaces']),
    userWorkspaceOptions() {
      return this.workspaces.map(function (w) {
        return {value: w.id, text: w.name}
      });
    },
  },
  watch: {
    modalTimeline() {
      if (this.modalTimeline) {
        this.model = new TimelineFormModel(this.modalTimeline);
      }
    },
  },
  methods: {
    ...mapActions(['hideTimelineModal', 'postOnTimeline', 'updateTimelinePost']),
    async onSubmit() {
      this.model.workspace_id = this.$route.params.id;

      let res;
      if (this.model.id) {
        res = await this.updateTimelinePost(this.model)
      } else {
        res = await this.postOnTimeline({
          data: this.model,
          config: {
            onUploadProgress: (progressEvent) => {
              this.progress = Math.round((progressEvent.loaded * 100) / progressEvent.total);
            }
          }
        });
      }

      if (res.success) {
        if (this.model.id) {
          delete this.model.file;
          this.$toast(this.$t(`Your timeline record was updated`));
        } else {
          this.$toast(this.$t(`Thanks for posting on timeline`));
        }
        this.hideModal()
      } else {
        this.$toast(res.body.message, 'danger');
      }
    },
    hideModal() {
      this.hideTimelineModal();
      this.model = new TimelineFormModel()
    }
  }
}
</script>

<style scoped>

</style>