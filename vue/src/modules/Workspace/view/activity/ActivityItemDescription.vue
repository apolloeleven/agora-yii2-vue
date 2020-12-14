<script>
export default {
  name: "ActivityItemDescription",
  props: {
    modelLabels: {
      required: true,
      type: Object
    },
    tableName: {
      required: true,
      type: String
    },
    activity: {
      required: true,
      type: Object
    },
    route: {
      required: true,
      type: [String, Object]
    }
  },
  render(createElement) {
    let desc = this.activity.description
      .replace('{model}', this.modelLabels[this.tableName] || '')
    ;
    // debugger;
    let name = '';
    switch (this.tableName) {
      case 'folders':
        name = this.activity.data.name;
        break;
      case 'articles':
        name = this.activity.data.title;
        break;
    }

    const searchWord = '{title}';
    const beforeTitle = desc.substring(0, desc.indexOf(searchWord))
    const afterTitle = desc.substring(desc.indexOf(searchWord) + searchWord.length);

    const routerLink = createElement('router-link', {
      props: {
        to: this.route
      }
    }, name);

    const children = [
      beforeTitle,
      routerLink,
      afterTitle
    ];
    return createElement('span', {
      class: 'text-muted mt-2'
    }, children)
  }
}
</script>

<style scoped>

</style>