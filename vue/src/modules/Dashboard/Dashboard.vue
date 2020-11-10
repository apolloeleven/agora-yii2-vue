<template>
  <div class="p-3 h-100 dashboard-container">
    <div class="twitter-feed-container">
      <a class="twitter-timeline" :href="twitterFeedUrl">Tweets</a>
    </div>
  </div>
</template>

<script>
import {AppSettings} from "@/shared/AppSettings";

export default {
  name: "Dashboard",
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
