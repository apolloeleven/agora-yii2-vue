<template>
  <div v-if="loading">
    <content-spinner show/>
  </div>
  <b-card v-else no-body class="workspace-polls">
    <b-card-body>
      <input-widget :model="model" attribute="question" :label="false" :placeholder="$t('Question')"/>
      <input-widget :model="model" attribute="description" type="richtext"/>
      <b-form-group>
        <label>
          {{ $t('Answers') }}
        </label>
        <b-input-group class="mb-2">
          <b-form-input :placeholder="$t('Add answer...')"/>
          <b-input-group-append>
            <b-button variant="secondary">
              <i class="fas fa-trash"/>
            </b-button>
          </b-input-group-append>
        </b-input-group>
        <b-input-group class="mb-2">
          <b-form-input :placeholder="$t('Add answer...')"/>
          <b-input-group-append>
            <b-button variant="secondary">
              <i class="fas fa-plus"/>
            </b-button>
          </b-input-group-append>
        </b-input-group>
      </b-form-group>
    </b-card-body>
  </b-card>
</template>

<script>
import ContentSpinner from "@/core/components/ContentSpinner";
import {createNamespacedHelpers} from "vuex";
import PollsFormModel from "./PollsFormModel";
import InputWidget from "@/core/components/input-widget/InputWidget";

const {mapState: mapWorkspaceState, mapActions: mapWorkspaceActions} = createNamespacedHelpers('workspace');
export default {
  name: "WorkspacePolls",
  components: {InputWidget, ContentSpinner},
  data() {
    return {
      model: new PollsFormModel(),
    }
  },
  computed: {
    ...mapWorkspaceState({
      loading: state => state.view.polls.loading,
    }),
    appendIcons() {
      return "<i class=\"fas fa-plus\"/>"
    }
  },
}
</script>

<style lang="scss" scoped>
.workspace-polls {
  width: 680px;
  margin: 0 auto;
}
</style>