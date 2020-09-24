<template>
  <input-widget :model="model" attribute="name"></input-widget>
</template>

<script>
import WorkspaceFormModel from "./WorkspaceFormModel";
import InputWidget from "../../../core/components/input-widget/InputWidget";

export default {
  name: "WorkspaceForm",
  components: {InputWidget},
  data() {
    return {
      model: new WorkspaceFormModel(),
    }
  },
}
</script>

<style scoped>

</style>