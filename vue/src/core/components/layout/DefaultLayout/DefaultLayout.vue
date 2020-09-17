<template>
  <div id="default-layout">
    <Navbar/>
    <div id="menu-content-wrapper">
      <sidebar/>
      <div id="content">
        <router-view/>
      </div>
    </div>
    <UserInvitationForm/>
  </div>
</template>

<script>
  import Navbar from './../../navbar/Navbar';
  import Sidebar from "./../../sidebar/Sidebar";
  import UserInvitationForm from "../../../../modules/User/Invitation/UserInvitationForm";

  export default {
    name: "DefaultLayout",
    components: {
      Sidebar,
      Navbar,
      UserInvitationForm,
    }
  }
</script>

<style lang="scss" scoped>
  #default-layout{
    height: 100vh;
    display: flex;
    flex-direction: column;
  }
  #menu-content-wrapper{
    flex: 1;
    display: flex;
  }
  #content{
    flex: 1;
  }
</style>
