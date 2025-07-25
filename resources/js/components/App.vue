<script>
export default {
    name: 'App',
}
</script>


<script setup>

// import { Channel } from 'laravel-echo';
import { ref, onMounted, watch, watchEffect } from "vue";

const props = defineProps({
    user: {
        type: Object,
        default: {},
    },
})

const task = ref(null);
const data = ref([]);
onMounted(() => {

    // Establecer configuraciones por defecto al crear la instancia
    
    window.Echo
        .channel('New-Task') //public
        .listen('.new-task', e => {
            task.value = e;
            

            setTimeout(() => {
                task.value = null;
            }, 10000);

        });
});
watch( () => {

    console.log('Lanzandola');
    const instance = axios.create({
        baseURL: 'http://localhost'
    });

    // Modificar valores por defecto después que una instancia ha sido creada
    instance.defaults.headers.common['Authorization'] = 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0L2FwaS9sb2dpbiIsImlhdCI6MTc0MTk4MzY1NywiZXhwIjoxNzQxOTg3MjU3LCJuYmYiOjE3NDE5ODM2NTcsImp0aSI6IlpkR1lHVnhzcG01bExOdWciLCJzdWIiOiIxIiwicHJ2IjoiMjNiZDVjODk0OWY2MDBhZGIzOWU3MDFjNDAwODcyZGI3YTU5NzZmNyJ9.k2uOWhps6r_TXb0HrHCRoCmn1dbQp-3sRAwHHcPScW0';

    
    const { response } = instance.get('http://localhost/api/task?limit=100&offset=0')
        .then(function (response) {
            // manejar respuesta exitosa
            console.log(response);
            data.value = response.data;
            console.log(data);
            return {
                message: response.message,
                data: response.data,
                color: 'primary'
            };
        })
        .catch(function (error) {
            // manejar error
            console.log(error);
            return {
                message: 'Error inesperado',
                data: [],
                color: 'danger'
            }
        })
        .finally(function () {
            // siempre sera executado
        });
})
</script>

<template>
    
    <div class="container">
        <div v-if="task" class="alert alert-success">
            {{ task }} <br>
            <!-- {{ promotion.descripcion }} -->
        </div>

        <slot></slot>
    </div>
    <section class="task" v-if="user">

        <div class="alert alert-primary" role="alert">
            A simple primary alert—check it out! {{ data.message }}
        </div>

        <div class="row">
            <div class="col-sm-4 mb-3 mt-4 mb-sm-0" v-for="task of data.data">
                <div class="card text-center">
                    <div class="card-header bg bg-primary-subtle">
                        {{ task.title }}
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{ task.title }}</h5>
                        <p class="card-text">{{ task.description }} </p>
                        <a href="#" class="btn btn-primary">Ver Mas..</a>
                    </div>
                    <div class="card-footer text-body-secondary">
                        <span>Inicia: {{ task.date_start }}</span>
                        <br>
                        <span>Finaliza: {{ task.date_end }}</span>
                    </div>
                </div>
            </div>
        </div>

        
    </section>
    



</template>


<style scoped></style>