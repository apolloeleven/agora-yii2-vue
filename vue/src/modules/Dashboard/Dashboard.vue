<template>
  <div class="p-3 h-100 dashboard-container">
    <div class="twitter-feed-container">
      <a class="twitter-timeline" href="https://twitter.com/thecodeholic?ref_src=twsrc%5Etfw">Tweets
        by thecodeholic</a>
    </div>
  </div>
</template>

<script>
export default {
  name: "Dashboard"
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
