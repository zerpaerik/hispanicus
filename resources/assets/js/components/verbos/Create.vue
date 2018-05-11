<style scoped>
  .first{
    background-color: lightgray;
    font-weight: bold;
  }
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
  .full-mid{
    position: fixed;
    bottom:0;
    left:-1;
  }
  .check{
    width: 30px;
    height: 30px;
  }
</style>

<template>

    <div class="panel panel-default">
            <b-alert class="full-mid" show variant="warning" v-if="saving">
              <h1 class="alert-heading">Guardando en la base de datos...</h1>
              <p><b>Por favor no cambie de pagina durante este proceso.</b></p>
            </b-alert>
            <div class="panel-heading">
                <h5>
                    Crear Verbo
                </h5>
                <div align="right">
                <button :disabled="saving"  v-if="datatable.length > 0" class="btn btn-success" @click="upload('save')">Guardar</button>
                <button :disabled="saving"  v-if="datatable.length > 0" class="btn btn-danger" @click="clean()">Cancelar</button>
                </div>
            </div>
        
        <div class="row panel-body" style="padding-left:2%; padding-right:2%;">
            <div class="col-md-12" style="padding-top:2%; padding-bottom:2%;">
                <div class="col-md-2">
                    <span class=" btn btn-block btn-primary btn-file" id="btnfile">
                        <span v-if="!file && !uploading">Seleccionar</span>
                        <span v-else>{{file.name}}</span>
                        <input type="file" accept=".xls,.xlsx,.csv,.ods" v-on:change="onFileChange">
                    </span>
                </div>
                <div class="col-md-2">
                    <button :disabled="saving" class="btn btn-success btn-block" @click="upload('show')" v-if="file && tipo && lang && region"><i class="fa fa-upload"></i></button>
                </div>
                
                <div class="col-md-2">
                  <label>Tipo de verbo</label>
                  <select class="form-control" variant="info" v-model="tipo">
                    <option value="1">Regular</option>
                    <option value="2">Regular (cambio ortografico)</option>
                    <option value="3">Irregular</option>
                  </select>
                </div>

                <div class="col-md-2">
                  <label>Región</label>
                  <select class="form-control" variant="info" v-model="region">
                    <option value="1">España</option>
                    <option value="2">Latino america</option>
                    <option value="3">Voseo</option>
                  </select>
                </div>

                <div class="col-md-2">
                  <label>Idioma</label>
                  <select class="form-control" variant="info" v-model="lang">
                    <option value="es">Español</option>
                    <option value="en">Ingles</option>
                    <option value="pt">Portugés</option>
                    <option value="cn">Chino</option>
                  </select>
                </div>     

                <div class="col-md-2">
                  <label>Solo Reflexivo</label>
                  <br>
                  <input class="form-check-input check" type="checkbox" v-model="reflexOnly">
                </div>                
                
                <div class="col-md-4" style="padding-top:10px;">
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" v-bind:style="{width: + pc + '%'}" :aria-valuenow="pc" aria-valuemin="0" aria-valuemax="100">
                            {{pc}}%
                        </div>
                    </div>
                </div>
            </div>
        </div>

      <div class="row">
        <table class="table table-borderless m-b-none col" v-if="datatable.length > 0">
            <tbody>
                <tr v-for="(d, index) in datatable" v-bind:class="{ first: (index == 0) }">
                    <!-- ID -->
                    <td v-for="i in idxs" style="vertical-align: middle;">
                        <span> {{ d[i] }} </span>
                    </td>

                </tr>
            </tbody>
        </table>            
        </div>
        <div style="position:fixed; width:30%; bottom:0; left:-1;">
        
        <b-alert show dismissible v-if="new_verbs && saved">
          <p>¡Se han añadido nuevos registros!</p>
        </b-alert>

        <b-alert show dismissible v-else-if="!new_verbs && saved" variant="danger">
            Esta hoja ya ha sido agregada, no se han hecho cambios en la base de datos.
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
                datatable: [],
                new_verbs: false,
                new_types: false,
                new_des: false,
                saved: false,
                saving : false,
                idxs : [],
                tipo : null,
                region : null,
                lang : null,
                reflexOnly : false
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
                this.data.append('tipo', this.tipo);
                this.data.append('region', this.region);
                this.data.append('lang', this.lang);
                this.data.append('reflexOnly', this.reflexOnly);
                
                this.uploading = true;

                if (action == "save") {
                    if (this.tipo < 1) { return alert('Debe especificar un tipo de verbo'); }
                    this.saving = true;
                    this.saved = false;
                    axios.post('/api/v1/verbos', this.data, config)
                        .then(response => {
                            this.new_types = response.data.new_types;
                            this.new_verbs = response.data.merges;
                            this.new_des = response.data.new_des;
                            this.saved = true;
                            this.saving = false;
                            this.datatable = [];
                            setTimeout(() => {
                                this.saved = false;
                            }, 5000);                              
                        }).catch(error => {
                            this.saving = false;
                        });
                }else{
                    axios.post('/api/v1/upload_verbos', this.data, config)
                    .then(response => {
                      var res = response.data.data;
                      for(let data in res){
                        if (res[data].length < 1) {
                          res.splice(data, 1);
                        }
                      }
                      
                      var values = ["verbo", "raíz", "desinencia", "formaverbal", "pers.gram.", "verboauxiliar", "pronombrereflexivo", "pronombreformal", "pronombreinformal", "tiempoverbal"];
                      var indexes = [];
                      for(let d in res[0]){
                        if(values.includes(res[0][d].toLowerCase().replace(" ", ""))){
                          indexes.push(d);
                        }
                      }
                      this.idxs = indexes;
                      this.datatable = res;
                      
                    }).catch(error => {
                        console.log(error);
                    });
                }
            },
            clean(){
              location.reload();
            }
        }
    }
</script>