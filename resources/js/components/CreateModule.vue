<template>
    <div class="content">
        <div class="row d-flex justify-content-center">
          <div class="col-md-12 text-center">
            <h4 class="content-title">Generate {{requestparams.name}} </h4>
          </div>
        </div>
        <form method="POST" :action="'/'+requestparams.moduleName" enctype="multipart/form-data">
        <input type="hidden" name="_token" v-bind:value="requestparams.csrf">
            <div class="row">
                <div v-for="(column, column_name) in requestparams.columns" class="col-md-6">
                    <div v-for="(display_label, column_type) in column" class="form-group row mb-5">
                        <label v-if="column_type !== 'json'" class="col-form-label col-md-4">{{display_label}} </label>
                        <div class="col">
                            <datetimepicker v-if="column_type == 'datetime' || column_type == 'date'" class="form-control border border-light" :name="column_name" ></datetimepicker>
                             <textarea v-else-if="column_type == 'text'" class=" col-md-12 form-control rounded-4 border border-light"  rows="5" :name="column_name"></textarea>
                            <model-select
                                    v-else-if="column_type == 'bigint'"
                                    class="form-control border border-light"
                                    :options="requestparams['selectOptions'][column_name]"
                                    v-model="selectOptions[0][column_name]"
                                    placeholder="Drop and search"
                                    @input="assign(column_name)"
                            >
                            </model-select>
                            <input v-else-if="display_label == 'Image'" type="file" class="myPointer" :name="column_name">
                            <input v-else-if="column_type == 'string' || column_type == 'integer' || column_type == 'float'" class="form-control border border-light" type="text" :name="column_name">
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
import { ModelSelect } from 'vue-search-select';
  export default {
    props: ['requestparams'],
    data () {
      return {
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
                User: {
                  value: '',
                  text: ''
                },
                Department: {
                  value: '',
                  text: ''
                },
            }],
        selectValue: [{

            Assigned_To: '',
            Opportunity: '',
            Quote_Stage: '',
            Organization: '',
            Client: '' 
        }],
        }
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

    components: {
      ModelSelect
    }
  }
</script> 