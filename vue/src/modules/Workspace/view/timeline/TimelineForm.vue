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

        <input-widget v-if="!model.id"
                      :model="model" attribute="file" type="file" :placeholder="$t('Choose a file or drop it here...')">
        </input-widget>

        <input-widget :model="model" attribute="description" type="richtext"/>
      </b-form>
    </b-modal>
  </ValidationObserver>
</template>

<script>
import {createNamespacedHelpers} from "vuex";
import ContentSpinner from "@/core/components/ContentSpinner";
import TimelineFormModel from "./TimelineFormModel";
import InputWidget from "@/core/components/input-widget/InputWidget";

const {mapState: mapWorkspaceState, mapActions: mapWorkspaceActions} = createNamespacedHelpers('workspace');

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
    ...mapWorkspaceState({
      loading: state => state.view.timeline.modal.loading,
      showModal: state => state.view.timeline.modal.show,
      modalTimeline: state => state.view.timeline.modal.object,
      workspaces: state => state.workspaces,
    }),
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
    ...mapWorkspaceActions(['hideTimelineModal', 'postOnTimeline', 'updateTimelinePost']),
    async onSubmit() {
      this.displayUrl();
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
              if (this.progress >= 100) {
                this.progress = 0;
              }
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
        this.model.setMultipleErrors(res.body);
      }
    },
    hideModal() {
      this.hideTimelineModal();
      this.model = new TimelineFormModel()
    },
    displayUrl() {
      let description = this.model.description;
      if(description === '') {
        return;
      }
      description = description.replace(/<[^>]*>/g, '\n'); //strip html tags and add \n for every tag
      let descriptions = description.split(/[\n\s,]+/g);

      const regex = /https?:\/\/[^\s]+/;
      for(let i of descriptions) {
        const match = regex.exec(i);
        if(match !== null) {
          this.model.description = this.model.description.replace(i, `<a href="${i}">${i}</a>`);
        }
      }
    }
  }
}
</script>

<style scoped>

</style>