<template>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Vue - Websocket Demo</div>

                    <div class="card-body">
                        Current Status
                        <template v-for="alert in alerts">
                          {{alert.issue_datetime}}
                        </template>
                    </div>
                </div>
            </div>
</template>

<script>
    export default {
      data() {
        return {
          alerts: [],
        }
      },
        mounted() {
            console.log('Component mounted.');
            Echo.channel('alerts').listen('AlertsUpdated', (e) => {
              this.alerts = e.alerts;
              console.log("Event thrown!");
              console.log(e);
            });
        }
    }
</script>
