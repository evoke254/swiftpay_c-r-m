<template>           
    <div>
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
          aria-hidden="true" :myselectedevent="myselectedevent">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header text-center">
                <h4 class="modal-title w-100" id="myModalLabel">Calendar Editor</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form method="POST" action="#" @submit="formSubmit">
                   <div class="row">
                      <div class="col">
                      <div class="form-group row">
                        <label for='title' class="col-sm-3 col-form-label"> Subject</label>
                        <div class="col-md-12">
                          <input type="text" class="form-control" v-model="title" value="hhhhhhvhgvghv" >
                        </div>
                      </div>
                        <div class="form-group row" >
                          <div class="col-md-12">
                              <label for='start' class="col-md-4 col-form-label"> Start Time:</label>
                              <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                                  <date-picker class="form-control datetimepicker-input" placeholder="Click on the calendar icon to select date and time" v-model="start" :config="options"></date-picker>
                              </div>
                          </div>
                        </div>

                      </div>
                      <div class="col">  
                      <div class="form-group row">
                        <label for='venue' class="col-sm-3 col-form-label"> Location</label>
                        <div class="col-md-12">
                          <input type="text" class="form-control" v-model="location">
                        </div>
                      </div>
                        <div class="form-group row">
                          <div class="col-md-12">
                          <label for='start' class="col-md-4 col-form-label"> End Time :</label>
                              <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                                  <date-picker class="form-control datetimepicker-input" placeholder="Click on the calendar icon to select date and time" v-model="end" :config="options"></date-picker>
                              </div>
                          </div>
                        </div>
                      </div>
                   </div>
                   <div class="row">
                      <div class="col-md-12">
                        <label for="desc" class="col-sm-3 col-form-label">Details</label>
                        <div class="col-md-12">
                          <textarea id="desc" v-model="details" class="form-control" ></textarea>
                        </div>
                      </div>
                   </div>
                   <input type="hidden" v-model="update">
                   <input type="hidden" v-model="id">
                  <div class="modal-footer">
                        <button type="button" class="btn btn-md btn-grey" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-md btn-dark-green" id="updateEvent">Post to Calendar</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-12">
            <i class="fas fa-plus-circle fa-2x mr-4 text-green p-3 white-text rounded hoverable myPointer mb-3" style="font-size: 50px" aria-hidden="true" v-on:click="getmymodal"></i>
                  <FullCalendar 
                defaultView="dayGridMonth" 
                :plugins="calendarPlugins" 
                eventColor="green"
                eventTextColor="white"
                :header="{
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                }"
                :height="700"
                :events="calendarEvents"
                @eventClick="eventclicked"
            />
        </div>
        <div class="modal fade" id="alertErrorModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
          aria-hidden="true">
          <div class="modal-dialog modal-sm modal-notify modal-danger" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title w-100 text-center" id="myModalLabel">Error</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                 <div class="text-center">
                  <i class="fas fa-times  fa-4x mb-3 animated rotateIn"></i>
                  <h6 class="text-danger" id="controllerError">Something is not right. Check that all fields have been properly filled before submitting the form. <br>If this error persists please contact Kahaki on 0742968713</h6>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
        <div class="modal fade" id="alertSuccessModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
          aria-hidden="true">
          <div class="modal-dialog modal-sm modal-notify modal-success" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title w-100 text-center" id="myModalLabel">Success</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                 <div class="text-center">
                  <i class="fas fa-check fa-4x mb-3 animated rotateIn"></i>
                  <h6 id="controllerSuccess">Well Done. Wasn't that easy. Looking forward to create more calendar events</h6>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-success btn-sm" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
</div>
</template>
<style lang='scss'>

@import '~@fullcalendar/core/main.css';
@import '~@fullcalendar/daygrid/main.css';

</style>
<script>
import FullCalendar from '@fullcalendar/vue'
import dayGridPlugin from '@fullcalendar/daygrid'
import timeGridPlugin from '@fullcalendar/timegrid'
import interactionPlugin from '@fullcalendar/interaction'

import datePicker from 'vue-bootstrap-datetimepicker';
    export default {
        components: {
            FullCalendar,  datePicker
          },
        props: ['requestparams'],
        mounted() {
                axios.get('/calendar/'+this.requestparams.moduleName+'/'+this.requestparams.objectId).then((response) => {
                this.calendarEvents = response.data;
            });
            },

        data () {
              return {
                calendarPlugins: [ 
                dayGridPlugin,
                timeGridPlugin,
                interactionPlugin,
               ],
               calendarEvents:[],
                title: '',
                start: '',
                details: '',
                end: '',
                location: '',
                update: '',
                id: '',
                date: new Date(),
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
                myselectedevent: [],    
              }
            },
        methods: {
            getmymodal(e) {
              this.id = '';
              this.title = '';
              this.location = '';
              this.start = '';
              this.end = '';
              this.details = '';
              this.update = "";
               $('#editModal').modal();
            },
            formSubmit(e) {
                e.preventDefault();
                if(!this.title || this.title.trim() === '') {
                        $('#alertErrorModal').modal({backdrop: false});
                        return
                    }
                if(!this.location || this.location.trim() === '') {
                        $('#alertErrorModal').modal({backdrop: false});
                        return
                    }
                if(!this.start || this.start.trim() === '') {
                        $('#alertErrorModal').modal({backdrop: false});
                        return
                    }
                if(!this.end || this.end.trim() === '') {
                        $('#alertErrorModal').modal({backdrop: false});
                        return
                    }
              axios.post('/calendar', {
                    id: this.id,
                    title: this.title,
                    location: this.location,
                    start: this.start,
                    end: this.end,
                    details: this.details,
                    update: this.update,
                    /*add Attendees*/
                    module_name: this.requestparams.moduleName,
                    object_id: this.requestparams.objectId,
                    User_id: this.requestparams.userId
                    }).then((response) => {
                        this.calendarEvents = response.data;
                      $('#alertSuccessModal').modal({backdrop: false});
                    });
                    $('#editModal').modal('hide');
            },
        /*load edit update delete*/
        eventclicked(calEvent) {
              axios.get('/calendar/'+calEvent.event.id).then((response) => {
                var selectedEvent  = response.data;

              this.id = selectedEvent[0].id;
              this.title = selectedEvent[0].title;
              this.start = selectedEvent[0].start;
              this.location = selectedEvent[0].location;
              this.end = selectedEvent[0].end;
              this.details = selectedEvent[0].details;
              this.update = "true";
                });
          $('#editModal').modal();
          }
        },
    }
</script>

