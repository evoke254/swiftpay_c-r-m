<template>
    <div class="content">
        <div class="row d-flex justify-content-center">
          <div class="col-md-12 text-center">
            <h4>Edit {{requestparams.name}} </h4>
          </div>
        </div>
        <form method="POST" :action="'/'+requestparams.moduleName+'/'+requestparams['showModuleData'][0]['id']" enctype="multipart/form-data">
        <input type="hidden" name="_token" v-bind:value="requestparams.csrf">
        <input type="hidden" name="_method" value="patch">
            <div class="row">
                <div v-for="(column, column_name) in requestparams.columns" class="col-md-6">
                    <div v-for="(display_label, column_type) in column" class="form-group row mb-5">
                        <label v-if="column_type !== 'json'" class="col-form-label col-md-4">{{display_label}} </label>
                        <div class="col">
                            
                            <date-picker 
                                placeholder="Click on the calendar icon to select date and time" 
                                :config="options"
                                v-if="column_type == 'datetime' || column_type == 'date'" 
                                class="form-control datetimepicker-input border border-light" 
                                :name="column_name" 
                                v-model="requestparams['showModuleData'][0][column_name]"
                            ></date-picker>

                             <textarea 
                                 v-else-if="column_type == 'text'" 
                                 class=" col-md-12 form-control rounded-4 border border-light"  
                                 rows="5" 
                                 :name="column_name">
                                {{requestparams['showModuleData'][0][column_name]}}
                             </textarea>
                            <model-select
                                    v-else-if="column_type == 'bigint'"
                                    class="form-control border border-light"
                                    :options="requestparams['selectOptions'][column_name]"
                                    v-model="selectOptions[0][column_name]"
                                    placeholder="Drop and search"
                                    @input="assign(column_name)"
                            >
                            </model-select>

                            <input 
                                v-else-if="display_label == 'Image'" 
                                type="file" class="myPointer" 
                                :name="column_name"
                            >
                            
                            <input 
                                v-else-if="column_type == 'string' || column_type == 'integer' || column_type == 'float'" 
                                class="form-control border border-light" 
                                type="text" 
                                :name="column_name" 
                                :value="requestparams['showModuleData'][0][column_name]"
                            >
                        </div>
                    </div>
                    <div v-for="(display_label, column_type) in column">
                        <input v-if="column_type == 'bigint'" type="hidden" :name="column_name" v-model="selectValue[0][column_name]">
                    </div>
                </div>
            </div>
            <div class="col-md-10 text-center">
                <button class="btn btn-md btn-success" type="submit">Submit</button>
            </div>
        </form>
    </div>
</template>

<script>

import 'vue-search-select/dist/VueSearchSelect.css';
import datePicker from 'vue-bootstrap-datetimepicker';
import { ModelSelect } from 'vue-search-select';
  export default {
    props: ['requestparams'],
    data () {
      return {
             options: {
                format: 'YYYY-MM-DD HH:mm',
                useCurrent: false,
                icons: {
                    time: 'icon ion-ios-time',
                    date: 'icon ion-ios-calendar',
                    up: 'icon ion-ios-arrow-dropup',
                    down: 'icon ion-ios-arrow-dropdown',
                    previous: 'icon ion-ios-arrow-dropleft',
                    next: 'icon ion-ios-arrow-dropright',
                    today: 'icon ion-ios-calendar',
                    clear: 'icon ion-ios-trash',
                    close: 'icon ion-ios-close-circle-outline'
                },
                },

            selectOptions: [{
                Assigned_To: {
                  value: '',
                  text: ''
                },
                Opportunity: {
                  value: '',
                  text: ''
                },
                Quote_Stage: {
                  value: '',
                  text: ''
                },
                Organization: {
                  value: '',
                  text: ''
                },
                Client: {
                  value: '',
                  text: ''
                },
            }],
        selectValue: [{
            Client: '',
            Assigned_To: '',
            Opportunity: '',
            Quote_Stage: '',
            Organization: '' 
        }],
        }
    },
    mounted(){
        //this.selectOptions[0][Assigned_To] = this.requestparams['showModuleData'][0][Assigned_To];
this.selectValue[0]['Assigned_To'] = this.selectOptions[0]['Assigned_To']['value'] = this.requestparams['showModuleData'][0]['get_user']['id'];
this.selectOptions[0]['Assigned_To']['text'] = this.requestparams['showModuleData'][0]['get_user']['name'];

this.selectValue[0]['Opportunity'] = this.selectOptions[0]['Opportunity']['value'] = this.requestparams['showModuleData'][0]['get_opportunity']['id'];
this.selectOptions[0]['Opportunity']['text'] = this.requestparams['showModuleData'][0]['get_opportunity']['Opportunity_Name'];

this.selectValue[0]['Organization'] = this.selectOptions[0]['Organization']['value'] = this.requestparams['showModuleData'][0]['get_organization']['id'];
this.selectOptions[0]['Organization']['text'] = this.requestparams['showModuleData'][0]['get_organization']['Organization_Name'];

this.selectValue[0]['Quote_Stage'] = this.selectOptions[0]['Quote_Stage']['value'] = this.requestparams['showModuleData'][0]['get_quote_stage']['id'];
this.selectOptions[0]['Quote_Stage']['text'] = this.requestparams['showModuleData'][0]['get_quote_stage']['name'];


    },
    methods: {
    reset () {
        this.column_name = {}
      },
    selectFromParentComponent1 () {  },
    
    assign(column){
        this.selectValue[0][column] = this.selectOptions[0][column]['value'];
    }

    },
    
    computed: {
            select: function (column_name) {
                
            }
    },

    components: {
      ModelSelect, datePicker
    }
  }
</script> 