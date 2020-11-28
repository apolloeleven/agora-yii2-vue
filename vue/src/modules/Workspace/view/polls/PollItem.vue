<template>
  <b-card no-body class="mb-2">
    <b-card-header>
      <div class="d-flex justify-content-between align-items-center">
        <div class="d-none d-sm-flex mb-0">
          <h5 class="mt-0 mb-0">
            {{ item.question }}
          </h5>
        </div>
        <div class="float-right">
          <i class="mr-3 fas fa-pencil-alt text-primary hover-pointer"/>
          <i class="far fa-trash-alt text-danger hover-pointer" @click="onDeleteClick"/>
        </div>
      </div>
      <p class="mb-0">
        <span style="color: #008BCA">{{ item.createdBy.displayName }}</span>
        &#x2027; {{ item.updated_at | relativeDate }}
      </p>
    </b-card-header>
    <b-card-body>
      <span class="mb-0" v-html="item.description"/>
      <div v-for="(answer, index) in item.pollAnswers" :key="`poll-item-answers-${index}`">
        <div class="row">
          <div class="col-md-1">
            <b-form-checkbox
              v-if="item.is_multiple && haveVotePermission" :id="`checkbox-${answer.id}`" v-model="selected"
              :name="`checkbox-${answer.id}`" :value="answer.id" class="mb-4">
            </b-form-checkbox>
            <b-form-radio
              v-else-if="!item.is_multiple && haveVotePermission" class="mb-4" v-model="selected"
              :name="`radio-${answer.id}`" :value="answer.id">
            </b-form-radio>
          </div>
          <div class="col-md-6">
            <h6 class="mb-0">{{ answer.answer }}</h6>
            <b-progress height="7px" :value="progressValue(item, answer)" variant="primary"/>
          </div>
          <div class="col-md-4 mt-2">{{ answer.userPollAnswers.length }} {{ $t('votes') }}</div>
        </div>
      </div>
      <b-button
        v-if="haveVotePermission" variant="primary" class="float-right" @click="onVoteClick(item)"
        :disabled="selected.length === 0">
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
  computed: {
    haveVotePermission() {
      return this.item.myVotes.length === 0
    },
  },
  methods: {
    onDeleteClick() {
      this.$emit('onDeleteClick')
    },
    onVoteClick(item) {
      if (this.selected.length > 0) {
        this.$emit('onVoteClick', {selected: this.selected, item: item})
      } else {
        this.$emit('onVoteClick', {selected: [this.selected], item: item})
      }
      this.selected = [];
    },
    progressValue(item, answer) {
      return answer.userPollAnswers.length * 100 / item.userPollAllAnswers.length
    },
  },
}
</script>

<style scoped>

</style>