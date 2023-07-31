<template>
  <div>
    <div
      class="spinner-border text-secondary float-left mr-2"
      role="status"
      v-if="loading"
     >
      <span
        class="sr-only"
      >
        Loading...
      </span>
    </div>
    <button
      class="btn btn-success float-left mr-2"
      v-if="!loading"
      @click="sendJob"
    >
      {{ title }}
    </button>
  </div>
</template>
<script>
  export default {
    props: [
      'link',
      'title'
    ],
    mounted: function() {},
    data: function() {
      return {
        loading: false
      }
    },
    methods: {
      sendJob: function() {
        this.loading = true;
        this.notification('information', 'Please wait, your request is processing...');
        axios.post(this.link).then(res => {
          this.loading = false;
          this.statusJob(res.data.job_id);
          this.notification('information', 'Job Process');
        });
      },
      statusJob: function(id) {
        const statusInterval = setInterval(() => {
          axios.get(`/api/jobs/${id}/status`).then(res => {
            switch (res.data.status) {
              case 2:
                this.notification('success', 'Job Done');
                clearInterval(statusInterval);
                break;
              case 0:
                this.notification('failed', 'Job Failed');
                clearInterval(statusInterval);
                break;
            }
          }).catch(error => {
            clearInterval(statusInterval);
          });
        }, 5000);
      },
      notification: function(type, message) {
        noty({
          type: type,
          layout: 'bottomRight',
          text: message,
          timeout: 3000
        });
      }
    }
  }
</script>
