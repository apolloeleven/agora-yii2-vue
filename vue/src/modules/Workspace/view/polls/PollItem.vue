<template>
  <b-card no-body class="mb-2">
    <b-card-header>
      <h5 class="mt-0 mb-0">
        {{ item.question }}
      </h5>
      <p class="mb-0">
        <span style="color: #008BCA">{{ item.createdBy.displayName }}</span>
        &#x2027; {{ item.updated_at | relativeDate }}
      </p>
    </b-card-header>
    <b-card-body>
      <span class="mb-0" v-html="item.description"/>
      <div v-for="(answer, index) in item.pollAnswers" :key="`poll-item-answers-${index}`">
        <b-form-checkbox
          v-if="item.is_multiple" :id="`checkbox-${answer.id}`" v-model="selected" :name="`checkbox-${answer.id}`"
          :value="answer.id">
          {{ answer.answer }}
        </b-form-checkbox>
        <b-form-radio v-else v-model="selected" :name="`radio-${answer.id}`" :value="answer.id">
          {{ answer.answer }}
        </b-form-radio>
      </div>
      <b-button variant="primary" class="float-right">
        {{ $t('Vote') }}
      </b-button>
    </b-card-body>
  </b-card>
</template>

<script>
export default {
  name: "PollItem",
  props: {
    item: Object,
    index: Number,
  },
  data() {
    return {
      selected: [],
    }
  },
}
</script>

<style scoped>

</style>