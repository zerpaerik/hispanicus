<style scoped>
    .action-link {
        cursor: pointer;
    }

    .m-b-none {
        margin-bottom: 0;
    }
</style>

<template>
    
    <div>

    <div class="alert alert-success alert-dismissible" role="alert" v-if="lastCode">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      Este es el codigo que se ha generado: <span style="font-size: 20px; font-weight: bold;">{{lastCode.code}}</span>
    </div>

        <div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span>
                            Codigos de cliente
                        </span>

                        <a class="action-link" @click="generateCode">
                            Crear nuevo codigo
                        </a>
                    </div>
                </div>

                <div class="panel-body" v-if="codes.length > 0">
                    <table class="table table-borderless m-b-none">
                        <thead>
                            <tr>
                                <th>Codigo</th>
                                <th>ID del dispositivo</th>
                                <!--<th>Usuario</th>-->
                                <th>Creado</th>
                                <th>Consumido</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="code in codes">
                                
                                <td style="vertical-align: middle;">
                                    <code style="font-size: 18px;">{{ code.code }}</code>
                                </td>

                                <td style="vertical-align: middle;">
                                    {{ code.device_id || '-'}}
                                </td>
                                <!--
                                <td style="vertical-align: middle;">
                                    {{ code.user_id || 'Codigo no asignado'}}
                                </td>  
                                -->
                                <td style="vertical-align: middle;">
                                    {{ code.created_at }}
                                </td>    
                                
                                <td style="vertical-align: middle;">
                                    {{ (code.created_at == code.updated_at) ? '-' : code.updated_at }}
                                </td>                                                                                            

                                <td style="vertical-align: middle;">
                                    <a class="action-link text-danger" @click="revoke(code.id)">
                                        Revocar
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        /*
         * The component's data.
         */
        data() {
            return {
                codes: [],
                lastCode : undefined
            };
        },

        /**
         * Prepare the component (Vue 1.x).
         */
        ready() {
            this.prepareComponent();
        },

        /**
         * Prepare the component (Vue 2.x).
         */
        mounted() {
            this.prepareComponent();
        },

        methods: {
            /**
             * Prepare the component (Vue 2.x).
             */
            prepareComponent() {
                this.getCodes();
            },

            generateCode(){
                this.lastCode = undefined;
                axios.get('/admin/make-code')
                    .then(response => {
                        this.lastCode = response.data;
                        this.getCodes();
                    }).catch(error => {
                        console.log(error);
                    });
            },
            /**
             * Get all of the authorized tokens for the user.
             */
            getCodes() {
                axios.get('/admin/codes')
                        .then(response => {
                            console.log(response.data);
                            this.codes = response.data;
                        });
            },

            /**
             * Revoke the given token.
             */
            revoke(code) {
                let result = confirm('¿Desea revocar este codigo? Si lo hace no podra revertirlo.');
                if(result){
                    axios.post('/admin/revoke-code/', {
                        code : code
                    })
                    .then(response => {
                        this.getCodes();
                    });
                }
            }
        }
    }
</script>
