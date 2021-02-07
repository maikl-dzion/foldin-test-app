<template>
  <div>

    <v-container style="margin:0px !important;">
      <template v-if="userId">


        <v-card>

          <v-toolbar dense>
            <v-toolbar-title style="font-size: 14px">Форма редактирования</v-toolbar-title>
            <v-spacer></v-spacer>
            <div style="font-style: italic; color: #3949ab">{{ userName }} (user_id-{{ userId }})</div>
          </v-toolbar>

          <div v-if="linkItem.link_id"
               style="margin:4px 4px 0px 8px; padding:5px; font-style: italic; color: #3949ab">
               ID ссылки - {{linkItem.link_id}}
          </div>

          <v-form ref="form" class="pa-4 pt-6" style="margin-top:-15px">

            <v-text-field
              v-model="linkItem.link"
              color="primary"
              label="Ссылка"
              type="text"
              style="margin:0px"
            ></v-text-field>

            <v-text-field
              v-model="linkItem.code"
              color="primary"
              label="Короткая ссылка"
              style="min-height: 96px"
              type="text"
              disabled
            ></v-text-field>

            <v-btn style="border-radius:0px; width:200px"
                   @click="save()"
                   class="white--text"
                   color="indigo darken-1"
            >Сохранить</v-btn>

            <v-btn style="margin-left:60px; width:100px; border-radius: 0px"
                   @click="reset()"
                   class="white--text"
                   color="indigo darken-1"
            >Очистить
            </v-btn>

          </v-form>
        </v-card>

        <div style="margin:4px 0px 0px 0px; padding:0px; border:0px red solid">

          <v-toolbar dense>
            <v-toolbar-title style="font-size: 14px">Список ссылок</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn icon>
              <v-icon>mdi-magnify</v-icon>
            </v-btn>
          </v-toolbar>

          <dl class="holiday" style="cursor: pointer">
            <template v-for="item in userLinkItems">
              <dt @click="selectLink(item)">{{ item.link }}</dt>
              <dd><a :href="'http://' + item.code" target="_blank" style="text-decoration: none">{{ item.code }}</a>
              </dd>
              <cc @click="deletelink(item.link_id)"> X</cc>
            </template>
          </dl>

        </div>


        <!--      <v-card>-->

        <!--        <v-toolbar dense>-->
        <!--          <v-toolbar-title style="font-size: 14px">Список ссылок</v-toolbar-title>-->
        <!--          <v-spacer></v-spacer>-->
        <!--          <v-btn icon>-->
        <!--            <v-icon>mdi-magnify</v-icon>-->
        <!--          </v-btn>-->
        <!--        </v-toolbar>-->

        <!--        <v-simple-table>-->
        <!--          <template v-slot:default>-->
        <!--            <thead>-->
        <!--            <tr>-->
        <!--              <th class="text-left" style="font-weight: bold; color:blue">Ссылка</th>-->
        <!--              <th class="text-left" style="font-weight: bold; color:blue">Короткая ссылка</th>-->
        <!--              <th class="text-left" style="font-weight: bold; width:30px">Delete</th>-->
        <!--            </tr>-->
        <!--            </thead>-->
        <!--            <tbody>-->
        <!--            <tr-->
        <!--              v-for="item in userLinkItems"-->
        <!--              :key="item.link_id" style="cursor: pointer">-->
        <!--              <td @click="selectLink(item)">{{ item.link }}</td>-->
        <!--              <td>-->
        <!--                <a :href="'http://' + item.code" target="_blank">{{ item.code }}</a>-->
        <!--              </td>-->
        <!--              <td>-->
        <!--                <div @click="deletelink(item.link_id)">X</div>-->
        <!--              </td>-->
        <!--            </tr>-->
        <!--            </tbody>-->
        <!--          </template>-->
        <!--        </v-simple-table>-->
        <!--      </v-card>-->

        <!--    <pre>{{userLinkItems}}</pre>-->

      </template>
    </v-container>

  </div>
</template>

<script>
export default {

  data: () => ({
    tab: null,
    userId: 0,
    selectLinkId: 0,
    userName: null,
    saveType: 'add',

    linkItem: {
      link: '',
      code: '',
      user_id: 0
    },

    userLinkItems: []
  }),

  created: function () {
    this.userId = this.store('user_id')
    if (!this.userId)
      this.$router.push('/page/auth')

    this.userName = this.store('user_name')
    this.linkItem.user_id = this.userId
    this.getLinksByUserId()
  },

  methods: {

    reset() {
      let linkItem = {link: '', code: '', user_id: 0}
      this.linkItem = Object.assign({}, linkItem)
    },

    save() {
      switch (this.saveType) {
        case 'add' :
          this.addlink()
          break

        case 'update' :
          this.updatelink()
          break
      }
    },

    selectLink(item) {
      this.linkItem = Object.assign({}, item)
      this.saveType = 'update'
      this.selectLinkId = item.link_id
    },

    addlink() {
      const apiUrl = '/post/add/link'
      this.send(apiUrl, 'post', this.linkItem).then(this.saveResponseHandle)
    },

    updatelink() {
      const apiUrl = '/post/update/link/' + this.selectLinkId
      this.send(apiUrl, 'put', this.linkItem).then(this.saveResponseHandle)
    },

    deletelink(linkId) {
      const apiUrl = '/post/delete/link/' + linkId
      this.send(apiUrl, 'delete', this.linkItem).then(this.saveResponseHandle)
    },

    getLinksByUserId() {
      if (!this.userId) return false
      const apiUrl = '/get/links/' + this.userId
      this.send(apiUrl).then(response => {
        this.userLinkItems = response.result
      })
    },

    saveResponseHandle(response) {

      const resp = this.saveResponse(response)
      if (resp.status) {
        alert('Успешное изменение')
      } else {
        alert('Не удалось изменить -' + resp.error)
      }
      this.afterSaveActions()
    },

    afterSaveActions() {
      this.saveType = 'add'
      this.selectLinkId = 0;
      this.linkItem = Object.assign({}, {link: '', code: '', user_id: 0})
      this.getLinksByUserId()
    },

  }

}
</script>

<style>

.v-data-table-header {
  background: green;
}

.v-data-table-header tr th span {
  color: white
}

.link-item {
  margin: 0px 4px 0px 4px;
  padding: 0px 4px 0px 4px;
  border-bottom: 2px blue solid;
  width: 100%;
  color: blue;
}

.holiday {
  overflow: hidden;
  font-size: 16px;
}

.holiday dt, .holiday dd {
  height: 2.5em;
  line-height: 2.5em;
  padding: 0 0.625em 0 0.875em;
  color: #4C565C;
  box-sizing: border-box;
}

dt {
  width: 30%;
  float: left;
  clear: right;
  background: #D3E6DD;
  font-weight: bold;
}

dd {
  width: 65%;
  float: left;
  margin-left: 0;
  margin-bottom: .3125em;
  border: 1px solid #BECFC7;
  border-left: none;
}

cc {
  width: 5%;
  float: right;
  margin-left: 0;
  margin-bottom: .3125em;
  border: 1px solid #BECFC7;
  border-left: none;
  text-align: center;
  padding-top: 6px;
  color: red;
  font-weight: bold;
  height: 40px;
}


</style>
