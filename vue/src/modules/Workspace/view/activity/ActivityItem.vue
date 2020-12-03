<template>
  <li>
    <b-card no-body class="mb-0">
      <b-card-body class="pr-5 pb-2">
        <b-media>
          <b-img class="mr-2" rounded="0" :src="this.getAvatar()" width="24" height="24"/>
          <strong class="mr-1">{{ this.activity.createdBy.first_name + ' ' + this.activity.createdBy.last_name + ' - ' }}</strong>
          <i class="fas fa-user-clock mr-2"></i>
          <p class="text-muted d-inline">{{this.getDate()}}</p>
          <span class="text-muted d-block mt-2">{{this.activity.description}}
            <router-link :event="clickable ? 'click' : ''" :to="this.path + '/' + this.id">
          {{this.name}}
          </router-link></span>
        </b-media>
      </b-card-body>
    </b-card>
  </li>
</template>

<script>

import moment from 'moment';

export default {
  name: "ActivityItem",

  props: {
    activity: Object,
  },

  data: function () {
    return {
      path: '',
      id: '',
      name: '',
      clickable: true,
      storage: process.env.VUE_APP_API_HOST
    }
  },

  methods: {
    getDate() {
      return moment((this.activity.created_at * 1000)).format('YYYY-MM-DD | HH:mm:ss');
    },

    getAvatar() {
      if(this.activity.createdBy.image_path)
        return this.storage + this.activity.createdBy.image_path;

      return '/assets/img/avatar.svg';
    },

    prepareParent() {
      if (this.activity.description.search('{parent}') === -1) {
        return null;
      }
      let parentIdentity = JSON.parse(this.activity.parent_identity);
      return parentIdentity.first_name + ' ' + parentIdentity.last_name;
    },

    prepareModel() {
      if (this.activity.description.search('{model}') === -1) {
        return null;
      }

      let model = this.activity.table_name.replaceAll(/\W/ig, "");
      switch (model) {
        case 'timeline_posts':
          this.clickable = false; //temporary disable link until timeline post link is created
          return 'post';
        case 'articles':
          this.path = "articles";
          this.id = this.activity.content_id;
          return 'article';
        case 'folders':
          this.path = "files";
          this.id = this.activity.content_id;
          return 'folder';
      }
    },

    prepareTitle() {
      if (this.activity.description.search('{title}') === -1) {
        return;
      }
      this.activity.description = this.activity.description.replace("{title}", "");
      let data = JSON.parse(this.activity.data);
      let tableName = this.activity.table_name.replaceAll(/\W/ig, "");
      let title = '';

      switch (tableName) {
        case 'timeline_posts':
          title = data.description;
          break;
        case 'articles':
          title = data.title;
          break;
        case 'folders':
          title = data.name;
          break;
      }
      if(this.activity.action === 'delete') {
        this.path = '';
        this.id = '';
        this.clickable = false;
      }
      this.name = title.replace(/(<([^>]+)>)/gi, "");
    },

    prepareActivity() {
      this.activity.description = this.activity.description.replace('{user}', this.activity.createdBy.username);

      if (this.prepareParent())
        this.activity.description = this.activity.description.replace('{parent}', this.prepareParent());
      if (this.prepareModel())
        this.activity.description = this.activity.description.replace('{model}', this.prepareModel());

      this.prepareTitle();
    }
  },
  beforeMount() {
    this.prepareActivity();
  }
}
</script>

<style scoped>

</style>