<template>
  <div>

    <v-card class="mx-auto" style="max-width: 500px; margin-top:5%">

      <v-toolbar color="indigo darken-1" cards>
        <v-card-title
          class="title font-weight-regular"
          style="color:white"> Авторизация
        </v-card-title>
      </v-toolbar>

      <v-form ref="form" v-model="form" class="pa-4 pt-6">

        <v-text-field
          v-model="authData.email"
          filled
          color="primary"
          label="Email"
          type="text"
        ></v-text-field>

        <v-text-field
          v-model="authData.password"
          filled
          color="primary"
          label="Пароль"
          style="min-height: 96px"
          type="password"
        ></v-text-field>

      </v-form>

      <v-divider></v-divider>

      <v-card-actions>

        <v-btn text @click="$refs.form.reset()"> Отмена</v-btn>

        <v-spacer></v-spacer>

        <v-btn style="border-radius:0px; width:30%"
               @click="auth()"
               class="white--text"
               color="indigo darken-1"
        >Войти
        </v-btn>

      </v-card-actions>

    </v-card>

  </div>
</template>

<script>
export default {
  data: () => ({
    form: true,
    response: [],
    authData: {
      email: '',
      password: ''
    }
  }),

  methods: {

    auth() {
      const postData = this.authData
      const apiUrl = '/post/auth/login'
      this.send(apiUrl, 'post', postData).then(this.responseHandle)
    },

    responseHandle(resp) {
      this.response = resp
      if (!resp.status || !resp.token) {
        alert('Не удалось авторизовать пользователя')
        return false
      }

      this.setToken(resp.token)

      this.store('user_name', resp.user.name)
      this.store('user_id'  , resp.user.user_id)

      this.sendEventBus ('auth_event', { user_id : resp.user.user_id } )

      this.$router.push('/page/profile')
    }

  } // methods

}
</script>
