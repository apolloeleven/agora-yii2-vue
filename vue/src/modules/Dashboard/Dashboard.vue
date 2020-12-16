<template>
  <div class="h-100 dashboard-container">

    <div class="px-3 m-3 workspace-timeline-wrapper" ref="postsContent" @scroll="onScroll">
      <workspace-timeline :workspace-id="0" :wants-workspace="true"/>
    </div>

    <contacts/>
  </div>
</template>

<script>
import {AppSettings} from "@/shared/AppSettings";
import WorkspaceTimeline from "@/modules/Workspace/view/timeline/WorkspaceTimeline";
import Contacts from "@/modules/Contacts/Contacts";
import {eventBus} from "@/core/services/event-bus";

export default {
  name: "Dashboard",
  components: {
    WorkspaceTimeline, Contacts
  },
  data() {
    return {
      twitterFeedUrl: AppSettings.getTwitterFeedUrl()
    }
  },
  methods: {
    /* This logic is required because twitter only initializes it's widgets only once on window load.
       So if you navigate to other page and come back twitter widgets will be disabled.
       You should always try and reinitialize it */
    initTwitterTimeline() {
      let count = 0;
      do {
        count++;
        if (window.twttr && window.twttr.widgets) {
          window.twttr.widgets.load();
          break;
        }
      } while (count < 10)

      if (count === 10) {
        this.$toast(this.$i18n.t("Could not initialize twitter feed. Try refreshing the page."), 'danger');
      }
    },
    onScroll() {
      if (this.$refs.postsContent.scrollTop + this.$refs.postsContent.offsetHeight === this.$refs.postsContent.scrollHeight) {
        eventBus.$emit('onScrollToBottom')
      }
    }
  },
  mounted() {
    this.initTwitterTimeline();
  }
}
</script>

<style lang="scss" scoped>

.dashboard-container {
  display: flex;

  .workspace-timeline-wrapper {
    flex: 1;
  }

  .twitter-feed-container {
    overflow-y: scroll;
  }
}
</style>
