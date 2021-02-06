import Vue from 'vue'
import axios from 'axios'

import TopHeader from '../components/TopHeader'
import AlertWindow from '../components/AlertWindow'

const HEADER_JWT_TOKEN_NAME = 'X-User-Jwt-Token'

const axiosHeaders = {
  'jwt_token': '',
  'Accept': 'application/json',
  'Access-Control-Allow-Origin': '*',
  'X-Requested-With': 'XMLHttpRequest',
  'Access-Control-Allow-Methods': 'GET,POST,PUT,DELETE,OPTIONS',
  'Access-Control-Allow-Headers': '*',
  'X-User-Jwt-Token': ''
}

axios.defaults.headers = axiosHeaders

const Plugins = {

  install(Vue, options) {
    Vue.component('TopHeader', TopHeader)
    Vue.component('AlertWindow', AlertWindow)

    Vue.mixin({

      data: () => ({
        apiUrl,
        rootUrl: '',
        getRootUrl: '',
        urlsList: {},
        respCallback: null,
        respItems: [],
        respItemName: '',
        userName: '',
        role: 0,

        companyList: [],
        personsList: [],

        userInfoDocType: [],
        userInfoDocList: []

      }),

      computed: {
        alertWindowToggle() {
          return this.sheet
        }
      },

      methods: {

        /// ////////////////
        // EVENT BUS
        sendEventBus(eventName, data = null) {
          this.$eventBus.$emit(eventName, data)
        },

        getEventBus(eventName, callBack) {
          this.$eventBus.$on(eventName, (item) => {
            callBack(item)
          })
        },

        getFaximileData(entity, callback = null, arrName = null) {
          const url = 'documents/' + entity
          this.send(url, 'get').then(response => {
            if (arrName) {
              this[arrName] = response
            }

            if (callback) {
              callback(response)
            }
          })
        },

        httpRequest(url, method = 'get', data = null) {
          this.makeRequest(url, method, data).then(response => {
            this.respItems = response.data
            if (this.respCallback) {
              this.respCallback(this.respItems)
            }

            if (this[this.respItemName]) {
              this[this.respItemName] = this.respItems
            }
          }).catch(function (error) {
            alert('HttpErrorAxios')
            console.log(error)
          })
        },

        respParamsClear() {
          this.respCallback = null
          this.respItems = []
          this.respItemName = ''
        },

        checkHttpData(data) {
          if (typeof data !== 'object') {
            lg(data)
          }
        },

        getRouteParam(name) {
          return this.$route.params[name]
        },

        /// ///////////////////
        // LOCAL STORE SERVICE
        store(key, value = null) {
          if (value) {
            localStorage.setItem(key, value)
          } else {
            return localStorage.getItem(key)
          }
        },

        storeRemove(key) {
          localStorage.removeItem(key)
        },

        pageRedirect(pageUrl) {
          this.$router.push(pageUrl)
        },

        authPageRedirect() {
          const authPageUrl = '/page/auth'
          this.pageRedirect(authPageUrl)
        },

        storeClear() {
          this.userName = ''
          localStorage.clear()
          this.authPageRedirect()
        },

        setUserName() {
          const username = this.store('username')
          if (!username) {
            this.authPageRedirect()
          } else {
            this.userName = username
          }
        },

        setRole() {
          this.role = 0
          const role = this.store('role')
          if (role) {
            this.role = role
          }
        },

        getToken() {
          const token = this.store('token')
          if (!token) {
            return false
          }
          return token
        },

        /// ////////////////
        // ПОЛУЧЕНИЕ ДАННЫХ
        getPersonDocuments(itemsName = 'personsList') {
          this.getFaximileData('executor/person_list', null, itemsName)
        },

        getCompanyDocuments(itemsName = 'companyList') {
          this.getFaximileData('executor/company_list', null, itemsName)
        },

        getTableData(tableName, arrayName = null) {
          const apiUrl = 'faximile/get-table-data/' + tableName
          if (!arrayName) {
            return this.send(apiUrl)
          }

          this.send(apiUrl).then(response => {
            this[arrayName] = response
          })
        },

        saveItem(apiUrl, postData = null, method = 'post') {
          if (!postData) {
            postData = this.item
          }
          this.send(apiUrl, method, postData).then(response => {
            this.saveResponse(response)
            this.close()
          })
        },

        saveResponse(response, message, resolve = null, reject = null) {
          if (!message) {
            message = 'Успешное сохранение'
          }

          if (response['save_result']) {
            this.alertWindow(message)
            if (resolve) {
              resolve(response)
            }
            return true
          }

          this.alertWindow('Произошла ошибка при сохранении', 2)

          if (response['save_error']) {
            if (reject) {
              reject(response)
            }
          }

          return false
        },

        async getUploadsFilesUrl() {
          const apiUrl = 'uploads/files/get/urls-list'
          // this.send(apiUrl).then(this.setRespUrls);
          this.urlList = await this.send(apiUrl)
          this.rootUrl = this.urlList.root_url
          this.getRootUrl = this.rootUrl
        },

        setRespUrls(response) {
          this.rootUrl = response.root_url
          this.urlsList = response
        },

        alertShow(text = null, type = null) {
          let defaultText = 'Успешное сохранение'
          this.snackbar = true
          if (text) defaultText = text
          this.snackbarText = defaultText
        },

        alertWindow(text = null, type = 1) {
          const messageData = {'message_type': type, 'message': text}
          this.sendEventBus('alert_window_message', messageData)
        },

        signUserVerify(documentId) {
          const apiUrl = 'documents/verify/user/' + documentId
          this.send(apiUrl, 'get').then(resp => {
            if ('verify_result' in resp &&
              resp['verify_result']) {

            }
            this.alertWindow(resp['message'])
          })
        },

        getUserInfoDocType(userType = null) {
          if (!userType) userType = this.userType
          const apiUrl = '/documents/user/info/doc-type-list/' + userType
          this.send(apiUrl).then(response => {
            this.userInfoDocType = response
            // lg(this.userInfoDocType);
          })
        },

        getUserInfoDocList(userId = null, callback = null) {
          if (!userId) userId = this.userId
          const apiUrl = 'documents/user/info/docs/' + userId
          this.send(apiUrl).then(response => {
            this.userInfoDocList = response
            if (callback) {
              callback(response)
            }
          })
        },

        async userInfoDocuments(param) {
          const userId = param.id
          const userType = param.type
          let apiUrl = 'documents/list/user/info/docs/' + userId
          let infoDocumentList = await this.get(apiUrl)
          // lg(infoDocumentList);
          apiUrl = 'documents/list/user/info/type/' + userType
          let infoDoctypeList = await this.get(apiUrl)

          this.infoListRender(infoDoctypeList, infoDocumentList)
        },

        infoListRender(typeList, docList) {
          this.infoList = {}

          for (const t in typeList) {
            const type = typeList[t]
            const typeId = type.id
            let docItem = {
              status: false,
              type: type,
              item: {}
            }
            for (const d in docList) {
              const doc = docList[d]
              const infoId = doc.user_infotype_id
              if (typeId == infoId) {
                docItem['status'] = true
                docItem['item'] = doc
              }
            }
            this.infoList[type.alias] = docItem
          }

          return this.infoList
        },

        /// //////////////////////////////////////
        /// /////////////////////////////////////
        //  NEW VERSION VER.2

        getUserDocumentList(userRole, arrName = null, callback = null) {
          const url = 'documents/list/' + userRole
          this.get(url).then(response => {
            if (arrName) {
              this[arrName] = response
            }

            if (callback) {
              callback(response)
            }
          })
        },

        getEmployeeDocumentList(userRole, userId, doctype, arrName = null, callback = null) {
          const url = 'documents/list/' + userRole + '/' + userId + '/' + doctype
          if (!arrName && !callback) {
            return this.get(url)
          }

          this.get(url).then(response => {
            if (arrName) this[arrName] = response
            if (callback) callback(response)
          })
        },

        getPersonList(userRole, arrName = null, callback = null) {
          const url = 'persons/list/' + userRole
          this.get(url).then(response => {
            if (arrName) {
              this[arrName] = response
            }

            if (callback) {
              callback(response)
            }
          })
        },

        getPerson(fieldName, value, arrName = null) {
          const url = 'persons/person/' + fieldName + '/' + value
          if (!arrName) {
            return this.get(url)
          }

          this.get(url).then(response => {
            this[arrName] = response
          })
        },

        getPersonInfoDocTypeList(userType = null, arrName = null) {
          if (!userType) userType = this.userType
          const apiUrl = 'persons/person/info/doctype-list/' + userType
          this.get(apiUrl).then(response => {
            if (!arrName) {
              this.personDocTypeList = response
            } else {
              this[arrName] = response
            }
          })
        },

        sendCode(userId) {
          const apiUrl = 'persons/send/phone/code/' + userId
          return this.get(apiUrl)
        },

        checkCode(userId, sendCode) {
          const apiUrl = 'persons/check/phone/code/' + userId + '/' + sendCode
          return this.get(apiUrl)
        },

        sendCodeResponse(resp, type = 'send') {
          switch (type) {
            case 'send' :
              if (resp['send_state']) {
                return true
              }
              break

            case 'check' :
              if (resp['check_code']) {
                return true
              }
              break
          }
          return false
        }

      } // Methods
    }) // VueMixin
  }
}

export default Plugins
