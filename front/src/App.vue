<template>
  <v-app>

    <v-app-bar color="indigo darken-1" dense dark app>
      <v-container>
          <ul class="menu-main">
              <li><router-link tag="a" to="/page/profile" class="current" >Профиль</router-link></li>
              <li><router-link tag="a" to="/page/register" >Регистрация</router-link></li>
              <template v-if="userId">
                  <li><a @click="logout" href="#" >Выход</a></li></template>
              <template v-else >
                  <li><router-link tag="a" to="/page/auth" >Войти</router-link></li></template>
          </ul>
      </v-container>
    </v-app-bar>

    <v-container>
      <v-content>
        <router-view></router-view>
      </v-content>
    </v-container>

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
      this.setToken(null)
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


@import url('https://fonts.googleapis.com/css?family=Ubuntu+Condensed');
.menu-main {
  list-style: none;
  margin: 0px 0 5px;
  padding: 25px 0 5px;
  text-align: center;
  background: none;
}
.menu-main li {display: inline-block;}
.menu-main li:after {
  content: "|";
  color: white;
  display: inline-block;
  vertical-align:top;
}
.menu-main li:last-child:after {content: none;}
.menu-main a {
  text-decoration: none;
  font-family: 'Ubuntu Condensed', sans-serif;
  letter-spacing: 2px;
  position: relative;
  padding-bottom: 20px;
  margin: 0 34px 0 30px;
  font-size: 17px;
  text-transform: uppercase;
  display: inline-block;
  transition: color .2s;
}
.menu-main a, .menu-main a:visited {color: #9d999d;}
.menu-main a.current, .menu-main a:hover{ color: white; }
.menu-main a:before,
.menu-main a:after {
  content: "";
  position: absolute;
  height: 4px;
  top: auto;
  right: 50%;
  bottom: -5px;
  left: 50%;
  background: #3949ab;
  transition: .8s;
}
.menu-main a:hover:before, .menu-main .current:before {left: 0;}
.menu-main a:hover:after, .menu-main .current:after {right: 0;}
@media (max-width: 550px) {
  .menu-main {padding-top: 0;}
  .menu-main li {display: block;}
  .menu-main li:after {content: none;}
  .menu-main a {
    padding: 25px 0 20px;
    margin: 0 30px;
  }
}

</style>
