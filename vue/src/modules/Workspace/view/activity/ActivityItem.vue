<template>
  <div class="ActivityItem">
    <b-card no-body class="mb-0">
      <b-card-body class="pr-5 pb-3">
        <b-media>
          <h5 class="mb-0" v-html="activity.description"></h5>
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
    prepareLink(href, id, description, action) {
      if (action === 'delete')
        return description.replace(/(<([^>]+)>)/gi, "");

      return `<a href="${href}/${id}" target='_blank'>${description.replace(/(<([^>]+)>)/gi, "")}</a>`
    },

    prepareUser() {
      return `<a href="#" target='_blank'>${this.activity.createdBy.username}</a>`;
    },

    prepareParent() {
      if (this.activity.description.search('{parent}') === -1) {
        return null;
      }
      let parentIdentity = JSON.parse(this.activity.parent_identity);
      return `<a href="#" target='_blank'>${parentIdentity.username}</a>`;
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
          return this.prepareLink("timeline", data.id, data.description, this.activity.action);
        case 'articles':
          return this.prepareLink("articles", data.id, data.title, this.activity.action);
        case 'folders':
          return this.prepareLink("files", data.id, data.name, this.activity.action);
      }
    },

    prepareActivity() {
      this.activity.description = this.activity.description.replace('{user}', this.prepareUser());

      if (this.prepareParent())
        this.activity.description = this.activity.description.replace('{parent}', this.prepareParent());
      if (this.prepareModel())
        this.activity.description = this.activity.description.replace('{model}', this.prepareModel());
      if (this.prepareTitle())
        this.activity.description = this.activity.description.replace('{title}', this.prepareTitle());
    }
  },
  beforeMount() {
    this.prepareActivity();
  }
}
</script>

<style scoped>

</style>