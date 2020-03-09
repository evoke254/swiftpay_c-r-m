<template>
    <div>   
        <div v-if="messagepayloads">
            <div v-for="messagepayload in messagepayloads.slice().reverse()">       
                <div class="media w-75 ml-auto mb-3">
                  <div class="media-body">
                    <div class="bg-warning rounded py-2 px-3 mb-2 rounded-pill">
                      <p class="text-small mb-0 text-white p-5">{{messagepayload.message}}</p>
                    </div>
                    <p class="small text-dark">{{$moment(messagepayload.created_at).format('HH : mm') }} | {{ $moment(messagepayload.created_at).format('Do MMM - YYYY')}}</p>
                  </div>
                </div>
            </div>
        </div>
        <div v-for="chatmessage in chatmessages">
            <!-- Reciever Message-->
            <div v-if="$userId == chatmessage.User_id">   
                <div class="media w-75 ml-auto mb-3">
                  <div class="media-body">
                    <div class="bg-warning rounded py-2 px-3 mb-2 rounded-pill">
                      <p class="text-small mb-0 text-white p-5">{{chatmessage.message}}</p>
                    </div>
                    <p class="small text-dark">{{$moment(chatmessage.created_at).format('HH : mm') }} | {{ $moment(chatmessage.created_at).format('Do MMM - YYYY')}}</p>
                  </div>
                </div>
            </div>
            <!-- Sender Message-->
            <div v-else >
                <div class="media w-75 mb-3"><img class="img-fluid rounded-circle" src="https://res.cloudinary.com/mhmd/image/upload/v1564960395/avatar_usae7z.svg" alt="user" width="50">
                  <div class="media-body ml-3">
                      <p class="text-small text-primary">{{chatmessage.get_user.name}}:</p>
                    <div class="bg-light rounded py-2 px-3 mb-2 rounded-pill">
                      <p class="text-small mb-0 text-dark p-5">Best {{chatmessage.message}}</p>
                    </div>
                    <p class="small text-dark">{{$moment(chatmessage.created_at).format('HH : mm') }} | {{ $moment(chatmessage.created_at).format('Do MMM - YYYY')}}</p>
                  </div>
                </div>
            </div>

            <div ref="message"></div>
        </div>
    </div>
</template>


<script>
import moment from 'moment';

    export default {
        props:['messagepayloads'],
        data() {
            return {
                chatmessages: [],
            }
        }, 
        mounted() {
            axios.get('/moduleChats/'+this.$parent.requestparams.moduleName+'/'+this.$parent.requestparams.objectId).then((response) => {
                this.chatmessages = response.data;
            });
        }
    }
</script>
