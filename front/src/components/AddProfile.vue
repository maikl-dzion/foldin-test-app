<template>
    <v-dialog v-model="dialog" max-width="500px">

        <template v-slot:activator="{on}">
            <v-btn color="success" dark class="mb-2" v-on="on" style="border-radius:0px;">Добавить пользователя</v-btn>
        </template>

        <v-card>
            <v-card-title >
                <div class="headline"
                     style="font-style: italic; font-size: 15px !important; color:green"
                >{{formTitle}}</div>
            </v-card-title>

            <v-card-text>
                <v-container>

                    <v-row>

                        <v-col cols="12" sm="12" md="12">
                            <v-text-field v-model="item.username" label="Имя" ></v-text-field>
                        </v-col>

                        <v-col cols="12" sm="12" md="12">
                            <v-text-field v-model="item.login" label="Логин" required></v-text-field>
                        </v-col>

                        <v-col cols="12" sm="12" md="12">
                            <v-text-field v-model="item.password" label="Пароль" required></v-text-field>
                        </v-col>

                        <v-col cols="12" sm="12" md="12">
                            <v-text-field v-model="item.phone" label="Телефон" ></v-text-field>
                        </v-col>

                        <v-col cols="12" sm="12" md="12">
                            <v-text-field v-model="item.email" label="Email" ></v-text-field>
                        </v-col>

                        <v-col cols="12" sm="12" md="12">
                            <v-text-field v-model="item.post" label="Должность" ></v-text-field>
                        </v-col>

                        <v-col cols="12" sm="12" md="12">
                            <input type="hidden" v-model="item.company_id = companyItem.id">
                            <v-select
                                label='Выбрать компанию'
                                v-model='companyItem'
                                :items='companyList'
                                item-value='id'
                                item-text='company_name'
                                return-object >
                            </v-select>
                        </v-col>

                        <v-col cols="12" sm="12" md="12">
                            <input type="hidden" v-model="item.role = roleItem.id">
                            <v-select
                                    label='Выбрать роль'
                                    v-model='roleItem'
                                    :items='roleList'
                                    item-value='id'
                                    item-text='name'
                                    return-object >
                            </v-select>
                        </v-col>

                    </v-row>
                </v-container>
            </v-card-text>

            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
                <v-btn color="blue darken-1" text @click="save">Save</v-btn>
            </v-card-actions>
        </v-card>

    </v-dialog>

</template>


<script>
    export default {
        data: () => ({
            pageTitle : 'Добавить нового работника',
            companyItem : {},
            //personItem  : {},
            roleItem : {},
            roleList : [
                { id : 1, name : 'Admin'},
                { id : 2, name : 'Менеджер (исполнитель)'},
                { id : 3, name : 'Работник'},
                { id : 4, name : 'Менеджер (работодатель)'},
            ],

            item      : {},
            itemIndex : -1,
            dialog    : false,

            modelItem : {
                "id": 0,
                "username" : "",
                "login"    : "",
                "password" : "",
                "role"      : 0,
                "company_id": 0,
                "phone": "",
                "email": "",
                "post": "",
            },

        }),

        computed: {
            formTitle() {
                return this.itemIndex === -1 ? "Добавить работника" : "Редактировать работника";
            }
        },

        watch: {
            dialog(val) {
                val || this.close();
            }
        },

        created() {
            this.item = Object.assign({}, this.modelItem);
            this.getTableData('company', 'companyList');
            // this.getPersonDocuments();
            //this.getTableData('doctype', 'docTypeList');
            //this.getTableData('person' , 'personsList');
            // this.getCompanyDocuments();
        },

        methods: {

            close() {
                this.dialog = false;
                this.$nextTick(() => {
                    this.item = Object.assign({}, this.modelItem);
                    this.itemIndex = -1;
                });
            },

            save() {
                const apiUrl = 'documents/add/person';
                this.send(apiUrl, "post", this.item).then(response => {
                    this.saveResponse(response);
                    this.close();
                });
            },
        }
    };
</script>

<style>
    .v-data-table-header {
        background: gray;
    }

    .v-data-table-header tr th span{
        color:white
    }
</style>
