<template><div>
    <div v-if="sheet" @click="close($event)" class="alert-window-popup" >

        <v-snackbar
             v-model="snackbar"
             :timeout="timeout"
             :color="snackColor"
             top="top" >

             <div style="text-align:center; z-index:99999999; width:100%; border:0px red solid">

                 <div style="text-align: right" >
                     <v-btn color="blue" text @click="close()">Close</v-btn>
                 </div>

                 <p></p>

                 <h5 style="text-align:center; font-weight: bold; padding:5px; color:white; " v-html="message"></h5>

             </div>
        </v-snackbar>

    </div>
</div></template>


<script>
export default {
    // props : ['show', 'message_type', 'message'],
    data: () => ({
        sheet : false,
        eventName   : 'alert_window_message',
        message     : 'Успешное сохранение',
        messageType : 1,


        timeout  : 400000,
        snackbar : false,
        multiLine: true,
        snackColor : 'green',
    }),

    computed: {
        // alertShow() {
        //     this.sheet = this.show;
        //     return this.sheet;
        // }
    },

    created(){
        this.getEventBus(this.eventName, this.alertMessageShow);
    },

    methods : {

        close(event) {
            this.sheet = false;
            this.snackbar = false;
        },

        alertMessageShow(item) {
            if('message_type' in item) {
                this.messageType = item['message_type'];
            }

            if('message' in item) {
                this.message = item['message'];
            }

            this.snackColor = 'green';

            switch (this.messageType) {
                case 1 : this.snackColor = 'green';  this.timeClose(); break;
                case 2 : this.snackColor = 'red';    break;
                case 3 : this.snackColor = 'orange'; break;
            }

            this.sheet = true;
            this.snackbar = true;

        },

        timeClose() {
            setTimeout (() => {
                this.sheet = false;
                this.snackbar = false;
            }, 3000);
        },
    }
}
</script>

<style>
    .alert-window-popup {
        padding:0px;
        position: fixed;
        width: 100%;
        height: 100%;
        background: none;
        z-index:99999999999999 !important;
        /*opacity: 0.5;*/
    }
</style>