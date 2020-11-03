<template>
  <div v-if="!model.length && !loading" class="no-data">
    {{ text }}
  </div>
</template>

<script>
export default {
  name: "NoData",
  props: {
    model: [Object, Array],
    text: String,
    loading: Boolean,
  }
}
</script>

<style scoped>
.no-data {
  display: flex;
  align-items: center;
  justify-content: center;
  text-align: center;
  flex-direction: column;
  color: #b6b6b6;
  font-weight: bold;
  text-shadow: 1px 1px 0 #FFFFFF;
  font-size: 1.275rem;
  min-height: 200px;
}
</style>