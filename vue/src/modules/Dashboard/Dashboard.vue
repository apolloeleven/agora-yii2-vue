<template>
  <div class="p-3 h-100 dashboard-container">
    <div class="twitter-feed-container">
      <a class="twitter-timeline" :href="twitterFeedUrl">Tweets</a>
    </div>

    <div class="m-3" ref="postsContent" @scroll="onScroll" style="grid-column: 2/4;overflow-y: scroll">
      <workspace-timeline :workspace-id="0" :create-new="false" />
    </div>

  </div>
</template>

<script>
import {AppSettings} from "@/shared/AppSettings";
import WorkspaceTimeline from "@/modules/Workspace/view/timeline/WorkspaceTimeline";

export default {
  name: "Dashboard",
  components: {
    WorkspaceTimeline,
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
    }
  },
  mounted() {
    this.initTwitterTimeline();
  }
}
</script>

<style lang="scss" scoped>
.dashboard-container {
  display: grid;
  grid-gap: 1em;
  grid-template-columns: repeat(4, 1fr);

  .twitter-feed-container {
    overflow-y: scroll;
  }
}
</style>
