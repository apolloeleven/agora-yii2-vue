<template>
  <b-card class="workspace-sidebar ml-auto" :title="$t('Contacts')" no-body>
    <b-card-header header-bg-variant="dark" header-text-variant="white">
      <h5 class="card-title mb-0">{{ $t('Contacts') }}</h5>
    </b-card-header>

    <div class="p-3">
      <b-input-group>
        <b-input-group-prepend is-text>
          <i class="fas fa-search"></i>
        </b-input-group-prepend>
        <b-form-input :placeholder="$t('Search for contacts')"></b-form-input>
      </b-input-group>
    </div>

    <b-list-group style="max-width: 300px;">
      <b-list-group-item v-for="contact in contacts" class="d-flex align-items-start pb-0 pt-3">
        <b-img class="contact-image" :src="contact.avatar"></b-img>
        <div class="contact-content pb-3 border-bottom d-flex flex-column w-100">
          <div class="d-flex justify-content-between">
            <span class="mr-auto">{{ contact.from }}</span>
            <span class="message-time">{{ contact.time | relativeDate }}</span>
          </div>
          <div class="d-flex justify-content-between align-items-start">
            <span class="latest-message">{{ contact.message }}</span>
            <b-badge v-if="contact.unread" pill variant="danger">{{ contact.unread }}</b-badge>
          </div>
        </div>
      </b-list-group-item>
    </b-list-group>
  </b-card>
</template>

<script>
export default {
  name: "Contacts.vue",
  data(){
    return {
      contacts: [
        {
          id: 1,
          message: 'Lets all be unique together until we realise we are all the same.',
          from: 'Cody Romero',
          avatar: 'https://randomuser.me/api/portraits/men/34.jpg',
          unread: 8,
          time: Date.now() - 1000 * 5
        },
        {
          id: 2,
          message: 'Please wait outside of the house.',
          from: 'Diane Herrera',
          avatar: 'https://randomuser.me/api/portraits/men/6.jpg',
          unread: 3,
          time: Date.now() - 1000 * 55
        },
        {
          id: 3,
          message: 'The book is in front of the table.',
          from: 'Leonard Reyes',
          avatar: 'https://randomuser.me/api/portraits/men/27.jpg',
          unread: 1,
          time: Date.now() - 1000 * 60 * 2
        },
        {
          id: 4,
          message: 'How was the math test?',
          from: 'Alan Austin',
          avatar: 'https://randomuser.me/api/portraits/men/79.jpg',
          unread: 0,
          time: Date.now() - 1000 * 60 * 26
        },
        {
          id: 5,
          message: 'I really want to go to work, but I am too sick to drive.',
          from: 'Scarlett Fernandez',
          avatar: 'https://randomuser.me/api/portraits/women/81.jpg',
          unread: 0,
          time: Date.now() - 1000 * 60 * 55
        },
        {
          id: 6,
          message: 'Christmas is coming.',
          from: 'Larry Stewart',
          avatar: 'https://randomuser.me/api/portraits/men/70.jpg',
          unread: 0,
          time: Date.now() - 1000 * 60 * 60 * 24
        },
        {
          id: 7,
          message: 'She advised him to come back at once.',
          from: 'Thomas Bradley',
          avatar: 'https://randomuser.me/api/portraits/men/16.jpg',
          unread: 0,
          time: Date.now() - 1000 * 60 * 60 * 24 * 5
        },
        {
          id: 8,
          message: 'The lake is a long way from here.',
          from: 'Veronica Brown',
          avatar: 'https://randomuser.me/api/portraits/women/86.jpg',
          unread: 0,
          time: Date.now() - 1000 * 60 * 60 * 24 * 12
        }
      ]
    }
  }
}
</script>

<style lang="scss">
.workspace-sidebar {
  overflow-y: auto;
  width: 300px;
  box-shadow: -2px 0 5px rgba(0, 0, 0, 0.2);

  .list-group-item {
    cursor: pointer;
    border: none;
    background-color: transparent;
    transition: background-color 0.1s;

    .contact-image {
      border-radius: 50%;
      margin-right: 0.5rem;
      width: 36px;
      height: 36px;
    }

    .message-time,
    .latest-message {
      font-size: 90%;
      color: #727272;
    }

    &:hover {
      background-color: #eeeeee;
    }
  }

  &::-webkit-scrollbar {
    width: 8px;
  }

  /* Track */
  &::-webkit-scrollbar-track {
    background-color: #d6d6d6;
    border-radius: 10px;
  }

  /* Handle */
  &::-webkit-scrollbar-thumb {
    background: rgba(0, 0, 0, 0.2);
    border-radius: 10px;
  }

  /* Handle on hover */
  &::-webkit-scrollbar-thumb:hover {
    background: rgba(0, 0, 0, 0.4);;
  }
}
</style>