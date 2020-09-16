<template>
  <div id="default-layout">
    <Navbar/>
    <div id="menu-content-wrapper">
      <sidebar/>
      <div id="content">
        <router-view/>
      </div>
    </div>
    <UserForm/>
    <UserDeleteForm/>
    <UserReadOnlyInformation/>
  </div>
</template>

<script>
import Navbar from './../../navbar/Navbar';
import Sidebar from "./../../sidebar/Sidebar";
import UserForm from "../../../../modules/User/user/UserForm";
import UserDeleteForm from "../../../../modules/User/user/UserDeleteForm";
import UserReadOnlyInformation from "../../../../modules/User/user/UserReadOnlyInformation";


export default {
  name: "DefaultLayout",
  components: {
    Sidebar,
    Navbar,
    UserForm,
    UserDeleteForm,
    UserReadOnlyInformation,
  }
}
</script>

<style lang="scss" scoped>
#default-layout {
  height: 100vh;
  display: flex;
  flex-direction: column;
}

#menu-content-wrapper {
  flex: 1;
  display: flex;
}

#content {
  flex: 1;
}
</style>