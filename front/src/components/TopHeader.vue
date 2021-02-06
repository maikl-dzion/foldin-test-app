<template><header class="header-area" style="border:0px red solid">

    <div class="nikki-main-menu">
        <div class="classy-nav-container breakpoint-off">
            <div class="container-fluid">
                <!-- Menu -->
                <nav class="classy-navbar justify-content-between" id="nikkiNav">

                    <router-link to="/" >
                        <img src="/img/core-img/logo.png" alt="" >
                    </router-link>

                    <div class="classy-navbar-toggler">
                        <span class="navbarToggler"><span></span><span></span><span></span></span>
                    </div>

                    <div class="classy-menu">

                        <div class="classycloseIcon">
                            <div class="cross-wrap"> <span class="top"></span><span class="bottom"></span> </div>
                        </div>

                        <div class="classynav" style="border:0px green solid;" >
                            <ul style="display: contents;" >

                                <slot name="top-menu"></slot>

                                <template v-if="page_role == 2" ><!--- Performer --->

                                    <li>
                                        <router-link to="/page/performer">Документы</router-link>
                                    </li>

                                    <li>
                                        <router-link to="/page/create/object">Создание объектов</router-link>
                                    </li>

                                    <li>
                                        <router-link to="/page/chat">Чат сообщений </router-link>
                                    </li>

                                </template>
                                <template v-else-if="page_role == 3" ><!--- Employee ------>

                                </template>
                                <template v-else-if="page_role == 4" ><!--- CompanyUser --->
                                    <li>
                                        <router-link to="/page/chat">Чат сообщений </router-link>
                                    </li>
                                </template>

                            </ul>


                            <div class="my-divider" >0</div>

                            <div class="top-social-info">
                                <router-link to="/page/profile" style="font-style: italic">
                                    <v-icon left color="green">mdi-account</v-icon> {{userName}}
                                </router-link>
                            </div>

                            <div class="my-divider" >0</div>

                            <div class="top-social-info">
                                <v-btn icon @click="storeClear()">
                                   <v-icon>mdi-home-export-outline</v-icon>
                                </v-btn>
                                <!--<a href="#" data-toggle="tooltip" data-placement="bottom" title="Facebook"><i class="fa fa-facebook" aria-hidden="true"></i></a>-->
                                <!--<a href="#" data-toggle="tooltip" data-placement="bottom" title="Twitter"><i class="fa fa-twitter" aria-hidden="true"></i></a>-->
                            </div>

                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>

</header></template>

<script>

export default {

  name  : "TopHeader",
  props : ['page_role'],
  data: () => ({
      userId : 0,
  }),
  created() {
      this.userId = this.store('role');
      this.setRole();
      this.setUserName();
      this.checkUserRole();
  },

  methods : {
      checkUserRole() {

          if(!this.page_role)
              return;

          const currentRole = this.store('role');
          if(!currentRole) {
              this.authPageRedirect();
          }

          if(currentRole != this.page_role) {
              this.$router.push('/');
          }
      }
  },

};
</script>

<style >
     .topMenuLink {
       text-decoration: none; font-weight:bold; color:green
     }

     .my-divider {
         width: 1px;
         border: 1px solid #ebebeb; color:white; margin:0px 15px 0px 15px;
     }
    
</style>
