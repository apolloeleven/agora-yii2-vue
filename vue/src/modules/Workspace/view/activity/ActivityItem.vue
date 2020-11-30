<template>
  <div class="ActivityItem">
    <b-card no-body class="mb-0">
      <b-card-body class="pr-5 pb-3">
        <b-media>
          <h5 class="mb-0">
            {{ activity.description }}
          </h5>
        </b-media>
      </b-card-body>
    </b-card>
  </div>
</template>

<script>

export default {
  name: "ActivityItem",

  props: {
    activity: Object,
  },

  methods: {
    prepareUser() {
      return this.activity.createdBy.username;
    },

    prepareModel() {
      if (this.activity.description.search('{model}') === -1) {
        return null;
      }

      let model = this.activity.table_name.replaceAll(/\W/ig, "");
      switch (model) {
        case 'timeline_posts':
          return 'post';
        case 'articles':
          return 'article';
        case 'folders':
          return 'folder';
      }
    },

    prepareTitle() {
      if (this.activity.description.search('{title}') === -1) {
        return null;
      }
      let data = JSON.parse(this.activity.data);
      let tableName = this.activity.table_name.replaceAll(/\W/ig, "");

      switch (tableName) {
        case 'timeline_posts':
          return data.description;
        case 'articles':
          return data.title;
        case 'folders':
          return data.name;
      }
    },

    prepareActivity() {

      this.activity.description = this.activity.description.replace('{user}', this.prepareUser());
      if (this.prepareModel())
        this.activity.description = this.activity.description.replace('{model}', this.prepareModel());
      if (this.prepareTitle())
        this.activity.description = this.activity.description.replace('{title}', this.prepareTitle().replace(/(<([^>]+)>)/gi, ""));
    }
  },
  beforeMount() {
    this.prepareActivity();
  }
}
</script>

<style scoped>

</style>