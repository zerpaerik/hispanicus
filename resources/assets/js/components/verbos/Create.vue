<template>
    <div class="panel panel-default">
            <div class="panel-heading">
                <h5>
                    Crear Verbo
                </h5>
            </div>
        
        <div class="row panel-body" style="padding-left:2%; padding-right:2%;">
            <div class="col-md-12" style="padding-top:2%; padding-bottom:2%;">
                <div class="col-md-4">
                    <span class=" btn btn-block btn-primary btn-file" id="btnfile">
                        <span v-if="!file && !uploading">Seleccionar</span>
                        <span v-else>{{file.name}}</span>
                        <input type="file" accept=".xls,.xlsx,.csv,.ods" v-on:change="onFileChange">
                    </span>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-success btn-block" @click="upload('show')" v-if="file"><i class="fa fa-upload"></i></button>
                </div>

                <div class="col" style="padding-top:10px;">
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" v-bind:style="{width: + pc + '%'}" :aria-valuenow="pc" aria-valuemin="0" aria-valuemax="100">
                            {{pc}}%
                        </div>
                    </div>
                </div>
            </div>
        <table class="table table-borderless m-b-none" v-if="datatable.length > 0">
            <thead>
                <tr>
                    <th>Infinitivo</th>
                    <th>{{datatable[0].B}}</th>
                    <th>{{datatable[0].G}}</th>
                    <th>Verbo</th>
                    <th>{{datatable[0].E}}</th>
                    <th>{{datatable[0].F}}</th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="(d, index) in datatable">
                    <!-- ID -->
                    <td v-if="index > 0" style="vertical-align: middle;">
                        {{ d.A }}
                    </td>

                    <!-- Name -->
                    <td v-if="index > 0" style="vertical-align: middle;">
                        {{ d.B }}
                    </td>
                    
                    <!-- Name -->
                    <td v-if="index > 0" style="vertical-align: middle;">
                        {{ d.G }}
                    </td>    

                    <!-- Name -->
                    <td v-if="index > 0" style="vertical-align: middle;">
                        {{ d.D }}
                    </td>

                    <!-- Name -->
                    <td v-if="index > 0" style="vertical-align: middle;">
                        {{ d.E }}
                    </td>

                    <!-- Name -->
                    <td v-if="index > 0" style="vertical-align: middle;">
                        {{ d.F }}
                    </td>

       
                </tr>
                <tr>
                    <td>
                        <button class="btn btn-primary" @click="upload('save')">Guardar</button>
                    </td>
                </tr>
            </tbody>
        </table>            
        </div>
        <div style="position:fixed; bottom:0; left:-1;">
        <b-alert show dismissible v-if="new_types">Se han añadido nuevos Tipos de Verbos</b-alert>
        <b-alert show dismissible v-if="new_verbs">Se han añadido nuevos Verbos</b-alert>
        <b-alert show dismissible v-if="new_des">Se han añadido nuevos Desinencias</b-alert>
        <b-alert show dismissible v-if="!new_types && !new_verbs && saved" variant="danger">
            Esta hoja ya ha sido agregada, no se han hecho cambios en la base de datos.
        </b-alert>
    </div>
    </div>    
</template>
<style scoped>  
    .btn-file {
        position: relative;
        overflow: hidden;
    }
    .btn-file input[type=file] {
        position: absolute;
        top: 0;
        right: 0;
        min-width: 100%;
        min-height: 100%;
        font-size: 100px;
        text-align: right;
        filter: alpha(opacity=0);
        opacity: 0;
        outline: none;
        background: white;
        cursor: inherit;
        display: block;
    }
</style>
<script>
    export default{
        data(){
            return {
                file: '',
                pc : 0,
                uploading : false,
                data: new FormData(),
                datatable: [],
                new_verbs: false,
                new_types: false,
                new_des: false,
                saved: false
            }
        },
        methods: {
            onFileChange(e) {
                let files = e.target.files || e.dataTransfer.files;
                if (!files.length)
                    return;
                this.file = e.target.files[0];
            },
            upload(action){

                var config = {
                    headers: { 'Content-Type': 'multipart/form-data' } ,
                    onUploadProgress: function(progressEvent) {
                        this.pc = Math.round( (progressEvent.loaded * 100) / progressEvent.total );
                    }.bind(this)
                };

                this.data.append('file', this.file);
                
                this.uploading = true;
                if (action == "save") {
                    axios.post('/api/v1/verbos', this.data, config)
                        .then(response => {
                            this.new_types = response.data.new_types;
                            this.new_verbs = response.data.new_verbs;
                            this.new_des = response.data.new_des;
                            this.saved = true;
                        });
                }else{
                    axios.post('/api/v1/upload_verbos', this.data, config)
                    .then(response => {
                        console.log(response);
                        this.datatable = response.data.data;
                    }).catch(error => {
                        console.log(error);
                    });
                }
            }
        }
    }
</script>