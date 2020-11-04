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
