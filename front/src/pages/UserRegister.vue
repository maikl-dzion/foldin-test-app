<template>
  <v-container>
    <v-card style="width: 50%; margin: 0 auto 0 auto;">

      <!----- Заголовок формы  ------>
      <v-toolbar color="indigo darken-1" cards dark flat style="margin:0px; padding:0px; border-radius: 0px">
        <v-card-title class="title font-weight-regular">Страница регистрации</v-card-title>
      </v-toolbar>
      <p></p>

      <!----- Данные формы ------>
      <v-card-text>
        <v-container>
          <v-row>

            <v-col cols="12" sm="12" md="12">
              <v-text-field v-model="user.name" label="Имя" required="true"></v-text-field>
            </v-col>

            <v-col cols="12" sm="12" md="6">
              <v-text-field v-model="user.email" label="Email" required="true"></v-text-field>
            </v-col>

            <v-col cols="12" sm="12" md="6">
              <v-text-field v-model="user.password" label="Пароль" required="true"></v-text-field>
            </v-col>

            <!--            <v-col cols="12" sm="12" md="6">-->
            <!--              <v-text-field v-model="user.login" label="Логин"></v-text-field>-->
            <!--            </v-col>-->

            <!--            <v-col cols="12" sm="12" md="6">-->
            <!--              <v-text-field v-model="user.phone" label="Телефон"></v-text-field>-->
            <!--            </v-col>-->

          </v-row>
        </v-container>
      </v-card-text>

      <!----- Кнопки действия ------>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="indigo darken-1" text @click="save()">Сохранить</v-btn>
      </v-card-actions>

    </v-card>
  </v-container>
</template>

<script>

export default {
  name: 'UserRegister',
  data () {
    return {
      user: {
        name: '',
        email: '',
        password: '',
        login: ''
      }
    }
  },

  methods: {

    save () {
      if (!this.validate()) { return false }

      const postData = this.user
      const apiUrl = '/post/user/register'

      this.send(apiUrl, 'post', postData)
         .then(this.saveResponseHandle)
    },

    validate () {
      if (!this.user.email) {
        alert('Вы не прописали email, это обязательное поле')
        return false
      }

      if (!this.user.password) {
        alert('Вы не установили пароль, это обязательное поле')
        return false
      }

      if (!this.user.name) {
        alert('Вы не прописали имя, это обязательное поле')
        return false
      }

      if (!this.user.login) {
        this.user.login = this.user.name
      }

      return true
    },

    saveResponseHandle (response) {
      const resp = this.saveResponse(response)
      if (resp.status) {
        alert('Успешное создание пользователя')
        this.$router.push('/page/auth')
      } else {
        alert('Не удалось создать пользователя -' + resp.error)
      }
    }

  } // methods
}
</script>

<style>
.custom-text-warning {
  color: red;
  font-style: italic;
  font-size: 10px;
}
</style>
