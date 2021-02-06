<template>
  <v-app>

    <v-app-bar color="indigo darken-1" dense dark app>
      <v-toolbar-title>Сервис для создания коротких ссылок</v-toolbar-title>
      <v-spacer></v-spacer>
      <router-link tag="button" to="/page/profile" class="page-route-link">Профиль</router-link>
      <router-link tag="button" to="/page/register" class="page-route-link">Регистрация</router-link>
      <template v-if="userId">
        <button @click="logout" class="page-route-link">Выход</button>
      </template>
      <template v-else="userId">
        <router-link tag="button" to="/page/auth" class="page-route-link">Логин</router-link>
      </template>

    </v-app-bar>

    <v-main>
      <v-container>
        <v-content>
          <router-view></router-view>
        </v-content>
      </v-container>
    </v-main>

    <v-footer app>footer</v-footer>

  </v-app>
</template>

<script>
export default {
  name: 'App',
  data() {
    return {
      userId: 0,
    }
  },

  created() {
    this.userId = this.store('user_id')
  },

  methods: {

    logout() {
      this.userId = 0
      this.storeRemove('user_name')
      this.storeRemove('user_id')
      this.$router.push('/page/auth')
    }

  },

  mounted() {
    this.getEventBus('auth_event', resp => {
      this.userId = resp.user_id
    })
  }
}
</script>

<style>
.page-route-link {
  color: white;
  margin: 10px 20px 10px 10px;
  padding: 3px;
  border-bottom: 1px white solid;
}
</style>
