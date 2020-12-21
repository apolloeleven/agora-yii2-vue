<template>
  <ValidationObserver ref="form" v-slot="{ handleSubmit, invalid ,reset}">
    <b-modal :visible="showModal"
             id="timeline-form"
             ref="modal"
             size="lg"
             no-enforce-focus
             :title="$t('Write Something on Timeline')" @hidden="hideModal" @ok.prevent="handleSubmit(onSubmit)"
             :ok-disabled="loading || !model.description && !model.file || !model.workspace_id" :ok-title="$t('Submit')"
             scrollable>
      <content-spinner :show="loading" :text="`${progress}% ${$t('Uploaded...')}`" class="h-100" :fullscreen="true"/>
      <b-form @submit.prevent="handleSubmit(onSubmit)" novalidate>

        <b-form-group :disabled="!!model.id" v-if="showWorkspaceField">
          <b-form-select v-model="model.workspace_id" :options="userWorkspaceOptions">
            <template #first>
              <b-form-select-option :value="null" disabled> {{ $t('Select a workspace') }}</b-form-select-option>
            </template>
          </b-form-select>
        </b-form-group>
        <input-widget :model="model" :label="$t(`What's up in you mind?`)" attribute="description" type="richtext"/>
        <content-spinner :show="filesLoading"></content-spinner>
        <div class="preview-container" v-if="files.length">
          <b-img class="image-preview" v-for="url in files" :src="url" fluid :key="url"/>
        </div>
        <div class="row my-3" v-if="!model.id">
          <div class="col-9">
            <h5>
              {{ $t('Add to your post') }}
            </h5>
          </div>
          <div class="col-3">
            <photo-video-input @change="onFileChoose"/>
          </div>
        </div>
      </b-form>
    </b-modal>
  </ValidationObserver>
</template>

<script>
import {createNamespacedHelpers} from "vuex";
import ContentSpinner from "../../../../core/components/ContentSpinner";
import TimelineFormModel from "./TimelineFormModel";
import InputWidget from "../../../../core/components/input-widget/InputWidget";
import PhotoVideoInput from "../../../../core/components/PhotoVideoInput";

const {mapState: mapWorkspaceState, mapActions: mapWorkspaceActions} = createNamespacedHelpers('workspace');

export default {
  name: "TimelineForm",
  components: {PhotoVideoInput, InputWidget, ContentSpinner},
  data() {
    return {
      model: new TimelineFormModel(),
      progress: 0,
      files: [],
      filesLoading: false,
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
    showWorkspaceField() {
      return this.modalTimeline ? this.modalTimeline.showWorkspaceField : false;
    }
  },
  watch: {
    modalTimeline() {
      if (this.modalTimeline) {
        let file = null;
        if (this.modalTimeline.files) {
          file = this.modalTimeline.files[0];
        }
        this.model = new TimelineFormModel({
          ...this.modalTimeline,
          file
        });
        if (this.$route.params.id) {
          this.model.workspace_id = this.$route.params.id;
        }

        if (this.modalTimeline.files) {
          this.filesLoading = true;
          const promises = [];
          Array.from(this.modalTimeline.files).forEach(f => {
            promises.push(this.readImage(f));
          });
          Promise.all(promises).then((urls) => {
            this.filesLoading = false;
            this.files = urls;
          })
        }

      }
    },
  },
  methods: {
    ...mapWorkspaceActions(['hideTimelineModal', 'postOnTimeline', 'updateTimelinePost']),
    async onSubmit() {
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
      this.files = [];
      this.filesLoading = false;
      this.hideTimelineModal();
      this.model = new TimelineFormModel()
    },
    readImage(file) {
      return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onload = (ev) => {
          resolve(ev.target.result);
        }
        reader.onerror = reject;
        reader.readAsDataURL(file)
      })
    },
    async onFileChoose(ev) {
      this.model.file = ev.target.files[0];
      this.files = [await this.readImage(this.model.file)];
    }
  }
}
</script>

<style lang="scss" scoped>
.image-preview {
  margin: 0 auto;
  display: block;
  max-height: 380px;
}
</style>
<style lang="scss">
#timeline-form {
  .modal-body {
    overflow-x: hidden;
  }
}
</style>