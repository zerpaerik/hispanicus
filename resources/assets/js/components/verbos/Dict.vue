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
<template>
    <div class="panel panel-default">
            <div class="panel-heading">
                <h5>
                    Añadir Diccionario
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
                    <button class="btn btn-success btn-block" @click="upload('show')" v-if="file && lang"><i class="fa fa-upload"></i></button>
                </div>

                <div class="col-md-2">
                  <select class="form-control" variant="info" v-model="lang">
                    <option value="undefined" disabled>Idioma</option>
                    <option value="es">Español</option>
                    <option value="en">Ingles</option>
                    <option value="pt">Portugés</option>
                    <option value="cn">Chino</option>
                  </select>
                </div>   

                <div class="col" style="padding-top:10px;">
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" v-bind:style="{width: + pc + '%'}" :aria-valuenow="pc" aria-valuemin="0" aria-valuemax="100">
                            {{pc}}%
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div style="position:fixed; width:30%; bottom:0; left:-1;">
        <b-alert show dismissible v-if="saved">
          <p>¡Se han añadido nuevos registros!</p>
        </b-alert>
        </div>   
    </div>    
</template>

<script>
    export default{
        data(){
            return {
                file: '',
                pc : 0,
                uploading : false,
                data: new FormData(),
                saved: false,
                region : undefined,
                lang : undefined
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
                this.data.append('lang', this.lang);
                
                this.uploading = true;
                this.saved = false;
                if (action == "save") {
                    axios.post('/api/v1/verbos', this.data, config)
                        .then(response => {
                            this.saved = response.Dicts;
                        });
                }else{

                    axios.post('/api/v1/dicts', this.data, config)
                    .then(response => {
                        this.saved = true;
                        setTimeout(() => {
                            this.saved = false;
                        }, 5000);
                    }).catch(error => {
                        console.log(error);
                    });
                }
            }
        }
    }
</script>