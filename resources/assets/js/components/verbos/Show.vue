<style scoped>
  .header{
    text-transform: capitalize;
    font-weight: bold;
  }
  .region{
    background-color: rbga(190,50,23,0.5);
    color:red;
  }
</style>

<template>
    <div class="panel panel-default">
      <div class="panel-heading">
          <h5>
              Verbos
          </h5>
            <b-row class="my-1">
              <b-col sm="8">
                <b-form-input @keyup.enter="load()" id="input-small" v-model="search" size="sm"  type="text" placeholder="Nombre del verbo"></b-form-input>
              </b-col>
              <b-col sm="2">
                <button :disabled="searching" class="btn btn-success" @click="load()">Buscar</button>
              </b-col>
              <b-col sm="2">
                <button :disabled="deleting" v-if="data" class="btn btn-danger" @click="del()">Eliminar</button>
              </b-col>              
            </b-row>            
      </div>

      <div class="row panel-body" style="padding-left:2%; padding-right:2%;" v-if="!hideTable">
        <div v-for="(k, i) in keys" :key="i">
        <b-list-group class="col-md-2" v-for="(t, id) in getKeys(data[k])" :key="id">
          <b-list-group-item active class="header">
            {{k}}
          </b-list-group-item>

          <b-list-group-item variant="secondary" class="header">
            {{t}}
          </b-list-group-item>

          <div v-for="(v, idx) in getKeys(data[k][t])" :key="idx">
          <b-list-group-item variant="info">
            {{v}}
          </b-list-group-item>
          <b-list-group-item v-for="(f, idxs) in getKeys(data[k][t][v])" :key="idxs" v-bind:class="{ region: (data[k][t][v][f].region > 0) }">

            <span style="font-weight:bold;">{{data[k][t][v][f].pronombre}} {{data[k][t][v][f].pronombre_formal_id}}</span>
            <span v-if="data[k][t][v][f].negativo != '0'">no</span>
            <span>{{data[k][t][v][f].pronombre_reflex}}</span>
            <span>{{data[k][t][v][f].verbo_auxiliar}}</span>
            <span>{{html_decode(data[k][t][v][f].raiz)}}</span><span style="color:red;font-weight:bold;">{{html_decode(data[k][t][v][f].desinencia)}}</span>
            
          </b-list-group-item>
          </div>
        </b-list-group>
        </div>
      </div>
      <div style="position:fixed; width:30%; bottom:0; left:-1;">
        <b-alert show dismissible v-if="affectedRows > 0" variant="danger">
          <p>Han sido eliminados {{affectedRows}} registros.</p>
        </b-alert>
      </div>
        <div style="position:fixed; width:30%; bottom:0; left:-1;">
        <b-alert show dismissible variant="warning" v-if="notFound">
            <p>Verbo <b style="color:#000066;">{{search}}</b> no ha sido encontrado, asegurese de haberlo escrito correctamente.</p>
        </b-alert>
        </div>      
    </div>  
</template>

<script>
    export default{
        data(){
            return {
              search : '',
              hideTable : true,
              notFound : false,
              searching : false,
              deleting : false,
              data : null,
              keys : [],
              times : [],
              raices : [],
              affectedRows : 0
            }
        },
        methods: {
          load(){
            
            this.searching = true;
            
            axios.get('/api/v1/verbos/search/' + this.search).then(response => {
              var res = response.data.data;
              this.data = res;
              this.keys = this.getKeys(res);
              this.raices = response.data.raices;
              this.hideTable = false;
              this.searching = false;
              this.notFound = false;

            }).catch(error => {
              this.notFound = true;
              this.hideTable = true;
              this.searching = false;

            });
          },
          d(){
            this.deleting = true;
            axios.post('/api/v1/delete', {raices : this.raices}).then(response => {
              this.load();
              this.affectedRows = response.data;
              this.deleting = false;
            }).catch(error => {
              console.log(error);
              this.deleting = false;
            });
          },
          getKeys(o){
            return Object.keys(o);
          },
          html_decode(s){
            if (s == null) return;
            return s.replace(/<(?:.|\n)*?>/gm, '');
          },
          del(){
            if (confirm('Seguro desea eliminar estos registros de la base de datos?')) {
                this.d();
            } else {
                return;
            }
          }        
        }
    }
</script>
