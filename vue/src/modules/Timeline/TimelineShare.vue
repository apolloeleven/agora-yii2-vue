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
import {createNamespacedHelpers} from "vuex";
import {EDIT_TIMELINE, eventBus, SHARE_ARTICLE, SHARE_FILE, SHARE_TO_TIMELINE} from "../../core/services/event-bus";

const {mapState, mapActions} = createNamespacedHelpers('timeline');

export default {
  name: "TimelineShare",
  components: {InputWidget},
  data() {
    return {
      show: false,
      edit: false,
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
  watch: {
    timelineData() {
      if (this.timelineData) {
        this.model = new TimelineFormModel(this.timelineData)
      }
    },
  },
  methods: {
    ...mapActions(['shareToTimeline', 'updateTimelinePost']),
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
        const {success, body} = await this.shareToTimeline();
        if (success) {
          if (body.action === SHARE_ARTICLE) {
            this.$toast(this.$t(`The article '{title}' was successfully shared on timeline`, {title: body.title}))
          } else if (body.action === SHARE_FILE) {
            this.$toast(this.$t(`{number} attachment(s) were successfully shared on timeline`, {number: body.articleFiles.length}))
          }
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
  mounted() {
    eventBus.$on(SHARE_TO_TIMELINE, ({type}) => {
      this.show = true;
      this.model.action = type;
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