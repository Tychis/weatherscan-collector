<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Example Component</div>

                    <div class="card-body">
                        I'm an example component.
                        <template v-for="alert in alerts">
                          {{alert.issue_datetime}}
                        </template>
                    </div>
                </div>
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
