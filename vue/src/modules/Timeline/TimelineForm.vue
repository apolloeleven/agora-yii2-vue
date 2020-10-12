<template>
  <b-modal
    :visible="showModal" id="timeline-form" ref="modal" size="lg"
    :title="$t('Write Something on Timeline')" @hidden="hideTimelineModal" @ok.prevent="handleSubmit(onSubmit)"
    :ok-disabled="loading" :ok-title="$t('Submit')" scrollable>
    <content-spinner :show="loading" :text="`${progress}% ${$t('Uploaded...')}`" class="h-100" :fullscreen="true"/>
    <b-form @submit.prevent="handleSubmit(onSubmit)" novalidate>
      <input-widget :model="model" attribute="description" type="richtext"/>
    </b-form>
  </b-modal>
</template>

<script>
import {createNamespacedHelpers} from "vuex";
import ContentSpinner from "../../core/components/ContentSpinner";
import TimelineFormModel from "./TimelineFormModel";
import InputWidget from "../../core/components/input-widget/InputWidget";

const {mapState, mapActions} = createNamespacedHelpers('timeline');

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
    ...mapState(['loading', 'showModal'])
  },
  methods: {
    ...mapActions(['hideTimelineModal']),
    async onSubmit() {

    },
  }
}
</script>

<style scoped>

</style>