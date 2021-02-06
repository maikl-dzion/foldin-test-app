import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store  from './store'
import vuetify from './plugins/vuetify'
//import Plugins from './plugins/Plugins'
import Http from './plugins/Http'

// Vue.use(Plugins)
Vue.use(Http)

Vue.config.productionTip = false
Vue.prototype.$eventBus = new Vue()

new Vue({
  router,
  store,
  vuetify,
  render: h => h(App)
}).$mount('#app')
