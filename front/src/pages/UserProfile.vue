<template>
  <v-container style="margin:0px !important;">
    <template v-if="userId">
      <template>
        <div class="text-left">
          <v-avatar
            tile color="indigo darken-1"
            style="min-width:150px; color:white; padding:10px;">
            <v-icon dark>mdi-account</v-icon>
            {{ userName }} ({{ userId }})
          </v-avatar>
        </div>
      </template>

      <v-card>

        <v-toolbar dense>
          <v-toolbar-title style="font-size: 14px">Форма редактирования</v-toolbar-title>
          <v-spacer></v-spacer>
        </v-toolbar>

        <v-form ref="form" class="pa-4 pt-6">

          <v-text-field
            v-model="linkItem.link"
            color="primary"
            label="Ссылка"
            type="text"
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
          >Сохранить
          </v-btn>

        </v-form>
      </v-card>

      <v-card>

        <v-toolbar dense>
          <v-toolbar-title style="font-size: 14px">Список ссылок</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-btn icon>
            <v-icon>mdi-magnify</v-icon>
          </v-btn>
        </v-toolbar>

        <v-simple-table>
          <template v-slot:default>
            <thead>
            <tr>
              <th class="text-left" style="font-weight: bold; color:blue">Ссылка</th>
              <th class="text-left" style="font-weight: bold; color:blue">Короткая ссылка</th>
              <th class="text-left" style="font-weight: bold; width:30px">Delete</th>
            </tr>
            </thead>
            <tbody>
            <tr
              v-for="item in userLinkItems"
              :key="item.link_id" style="cursor: pointer">
              <td @click="selectLink(item)">{{ item.link }}</td>
              <td>
                <a :href="'http://' + item.code" target="_blank">{{ item.code }}</a>
              </td>
              <td>
                <div @click="deletelink(item.link_id)">X</div>
              </td>
            </tr>
            </tbody>
          </template>
        </v-simple-table>
      </v-card>
      <!--    <pre>{{userLinkItems}}</pre>-->
    </template>
  </v-container>
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
      if(!this.userId) return false
      const apiUrl = '/get/links/' + this.userId
      this.send(apiUrl).then(response => {
        this.userLinkItems = response.result
      })
    },

    saveResponseHandle(response) {

      const resp = this.saveResponse(response)
      if(resp.status) {
         alert('Успешное изменение')
      } else {
        alert('Не удалось изменить -' + resp.error)
      }
      this.afterSaveActions()
    },

    afterSaveActions() {
      this.saveType = 'add'
      this.selectLinkId = 0;
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
</style>
