<template>
  <li>
    <b-card no-body class="mb-0">
      <b-card-body class="p-2">
        <b-media no-body>
          <b-media-aside>
            <b-img class="mr-2" rounded="0" :src="this.getAvatar()" width="48"/>
          </b-media-aside>

          <b-media-body>
            <div>
              <strong class="mr-1">
                {{ this.activity.createdBy.first_name + ' ' + this.activity.createdBy.last_name }}
              </strong>
              <span v-if="tableName === 'timeline_posts'">
                  {{ description }}
                  <router-link :to="{name: 'todo'}">{{ $t('timeline post') }}</router-link>
              </span>
              <span v-else-if="tableName === 'articles'">
                <template v-if="action === 'create' || action === 'update' || action === 'delete'">
                  {{ description }}
                  <router-link :to="{name: 'workspace.articles.view',params: {articleId: this.activity.content_id}}">
                    {{ this.activity.data.title }}
                  </router-link>
                </template>
              </span>
              <span v-else-if="tableName === 'folders'">
                <template
                  v-if="action === 'create' || action === 'upload' || action === 'update' || action === 'delete'">
                  {{ description }}
                  <router-link :to="{name: 'workspace.files', params: {folderId: this.activity.content_id}}">
                    {{ this.activity.data.name }}
                  </router-link>
                </template>
              </span>
              <!--              <activity-item-description :model-labels="modelLabels" :table-name="tableName" :activity="activity" :route="route"/>-->

            </div>
            <div>
              <i class="fas fa-clock mr-2"></i>
              <p class="text-muted d-inline">{{ this.activity.created_at * 1000 | toDatetime }}</p>
            </div>
          </b-media-body>
        </b-media>
      </b-card-body>
    </b-card>
  </li>
</template>

<script>

export default {
  name: "ActivityItem",
  components: {},
  props: {
    activity: Object,
  },

  data: function () {
    return {
      messageMap: {
        articles: {
          create: this.$t('created article'),
          update: this.$t('updated article'),
          delete: this.$t('deleted article'),
        },
        timeline_posts: {
          create: this.$t('created'),
          update: this.$t('updated'),
          delete: this.$t('deleted'),
          like: this.$t('liked'),
          unlike: this.$t('unliked'),
          comment: this.$t('commented on'),
          reply: this.$t('replied on comment on'),
        },
        folders: {
          create: this.$t('created folder'),
          upload: this.$t('uploaded file'),
          update: activity => this.$t('updated ' + (activity.data.is_file ? 'file' : 'folder')),
          delete: activity => this.$t('deleted ' + (activity.data.is_file ? 'file' : 'folder')),
        }
      },
      path: '',
      id: '',
      name: '',
      clickable: true,
      storage: process.env.VUE_APP_API_HOST,
      modelLabels: {
        folders: this.$t('folder'),
        articles: this.$t('article'),
        timeline_posts: this.$t('timeline post'),
      }
    }
  },
  computed: {
    tableName() {
      return this.activity.table_name.replaceAll(/\W/ig, "");
    },
    action() {
      return this.activity.action
    },
    route() {
      switch (this.tableName) {
        case 'timeline_posts':
          //TODO
          return {};
        case 'articles':
          return {
            name: 'workspace.articles.view',
            params: {articleId: this.activity.content_id}
          };
        case 'folders':
          return {
            name: 'workspace.files',
            params: {folderId: this.activity.content_id}
          };
        default:
          return {};
      }
    },
    description() {
      const desc = this.messageMap[this.tableName][this.action];
      console.log(desc);
      if (typeof desc === 'function') {
        return desc(this.activity);
      }
      return desc;
    }
  },
  methods: {

    getAvatar() {
      if (this.activity.createdBy.image_path)
        return this.storage + this.activity.createdBy.image_path;

      return '/assets/img/avatar.svg';
    },
  },
}
</script>

<style scoped>

</style>