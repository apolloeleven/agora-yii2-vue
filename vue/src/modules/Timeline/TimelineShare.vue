<template>
  <ValidationObserver ref="form" v-slot="{ handleSubmit, invalid ,reset}">
    <b-modal :visible="show" id="timeline-form" ref="modal" size="lg" scrollable @hidden="hideModal"
             :title="modalTitle" :cancel-title="$t('No')" :ok-title="$t('Yes')" @ok.prevent="handleSubmit(onSubmit)">
      <b-form @submit.prevent="handleSubmit(onSubmit)" novalidate>
        <input-widget :model="model" attribute="description" type="richtext"/>
      </b-form>
    </b-modal>
  </ValidationObserver>
</template>

<script>
import InputWidget from "../../core/components/input-widget/InputWidget";
import TimelineFormModel from "./TimelineFormModel";
import {mapActions, createNamespacedHelpers} from 'vuex';
import {EDIT_TIMELINE, eventBus, SHARE_ARTICLE, SHARE_FILE, SHARE_TO_TIMELINE} from "../../core/services/event-bus";

const {mapState, mapActions: mapTimelineActions} = createNamespacedHelpers('timeline');

export default {
  name: "TimelineShare",
  components: {InputWidget},
  data() {
    return {
      show: false,
      edit: false,
      selectedIds: [],
      title: '',
      model: new TimelineFormModel(),
    }
  },
  computed: {
    ...mapState(['timelineData']),
    modalTitle() {
      if (this.edit) {
        return this.$t('Update timeline shared post');
      }
      return this.$t('Do you want to share this on workspace timeline?')
    }
  },
  methods: {
    ...mapTimelineActions(['updateTimelinePost']),
    ...mapActions(['shareToTimeline']),
    async onSubmit() {
      if (this.edit) {
        const {success, body} = await this.updateTimelinePost(this.model);
        if (success) {
          this.$toast(this.$t(`The post '{title}' was successfully updated`, {title: body.title}))
          this.hideModal();
        } else {
          this.$toast(body.message, 'danger')
        }
      } else {
        const {success, body} = await this.shareToTimeline(this.model);
        if (success) {
          if (body.action === SHARE_ARTICLE) {
            this.$toast(this.$t(`The article '{title}' was successfully shared on timeline`, {title: this.title}))
          } else if (body.action === SHARE_FILE) {
            this.$toast(this.$t(`{number} attachment(s) were successfully shared on timeline`, {number: this.selectedIds.length}))
          }
          this.hideModal();
        } else {
          this.$toast(body.message, 'danger')
        }
      }
    },
    hideModal() {
      this.show = false;
      this.edit = false;
      this.model = new TimelineFormModel()
    }
  },
  watch: {
    timelineData() {
      if (this.timelineData) {
        this.model = new TimelineFormModel(this.timelineData)
      }
    },
  },
  mounted() {
    eventBus.$on(SHARE_TO_TIMELINE, ({type, articleModel, selectedIds}) => {
      this.show = true;
      this.model.action = type;
      this.model.article_id = articleModel.id;
      this.model.workspace_id = articleModel.workspace_id;
      this.selectedIds = selectedIds;
      this.title = articleModel.title;
    });
    eventBus.$on(EDIT_TIMELINE, ({model}) => {
      this.show = true;
      this.edit = true;
      this.model = new TimelineFormModel(model);
    });
  }
}
</script>

<style scoped>

</style>