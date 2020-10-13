<template>
  <b-card no-body class="mb-3" :style="'animation-delay: '+(index / 5)+'s'">
    <b-card-body class="pr-5">
      <b-media>
        <p class="mb-0">
          <i class="far fa-clock"/>
          {{ timeline.updated_at | relativeDate }}
        </p>
      </b-media>
    </b-card-body>
    <b-card-body>
      <div class="row">
        <div class="col border-left">
          <b-card-text text-tag="div" class="short-description" v-html="timeline.description"/>
        </div>
      </div>
    </b-card-body>
    <dropdown-button :model="timeline" type="timeline"/>
  </b-card>
</template>

<script>
import DropdownButton from "../Workspace/components/DropdownButton";

export default {
  name: "TimelineItem",
  props: {
    index: Number,
    timeline: Object
  },
  components: {DropdownButton}
}
</script>

<style scoped>

</style>