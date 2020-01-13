<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vue Weather Report TFREC</title>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <script>window.Laravel = {csrfToken: '{{csrf_token()}}'}</script>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

</head>
<body>

<div id="app">

    <navbar></navbar>

    @if (session('error'))
        <h3 class="alert alert-info"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> {{ session('error') }}</h3>

    @else

    <div class="container">

        <h1>Weather Forecast <span class="text-muted">| @{{ cityByIp }}</span> </h1>


        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li v-bind:class="[{disabled: pageNumber === 0}]" class="page-item"><a class="page-link" href="#" @click="prevPage">Previous</a></li>

                <li v-bind:class="[{disabled: pageNumber >= pageCount -1}]" class="page-item"><a class="page-link" href="#" @click="nextPage">Next</a></li>
            </ul>
        </nav>

        <div class="card card-body mb-2" v-for="forecast in paginatedData">

            <h3> @{{moment(forecast.dt_txt).format('MMMM Do, YYYY, h:mm:ss a')}}</h3>

            <h2>@{{forecast.weather[0].description}}</h2>
            <h2><img v-bind:src="'http://openweathermap.org/img/wn/'+forecast.weather[0].icon+'@2x.png'" v-bind:title="forecast.weather[0].description" ></h2>

            <p>Temp:  @{{forecast.main.temp}}&#8457;</p>
            <p>Max Temp:  @{{forecast.main.temp_max}}&#8457;</p>
            <p>Min Temp:  @{{forecast.main.temp_min}}&#8457;</p>
            <p>Pressure:  @{{forecast.main.pressure}}</p>
            <p>Sea Level:  @{{forecast.main.sea_level}}</p>
            <p>Ground Level:  @{{forecast.main.grnd_level}}</p>
            <p>Humidity:  @{{forecast.main.humidity}}</p>
            <p>Temp KF:  @{{forecast.main.temp_kf}}</p>

        </div>

    </div>

    @endif

    <stickyfoot></stickyfoot>

</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.10/dist/vue.js"></script>
<script src="/js/components/Navbar.vue.js"></script>
<script src="/js/components/Footer.vue.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.js"></script>

<script>

    const app = new Vue({

        el: '#app',

        data: {
            forecasts: [],
            cityByIp: '',
            moment: moment,
            pageNumber: 0,

        },

        props:{

        //pagination default size
        size:{
            type:Number,
            required:false,
            default: 8
        }
    },

        created: function(){ this.fetchForecasts();},
        methods: {
            fetchForecasts() {

                //set URL for API route...
                let url = '/api/forecasts';
                axios
                    .get(url)
                    .then(response => {

                        console.log(response.data);
                        //set forecasts array to list object from response...
                        this.forecasts = response.data.list;
                        this.cityByIp = response.data.city.name;



                        //testing by logging values to console...
                      //  console.log(response.data);
                      //  console.log(this.cityByIp);
                      //console.log(this.forecasts.length);

                    })
                    .catch(error => {
                        console.log(error);
                    });
            },

            //pagination methods...
            nextPage(){
                this.pageNumber++;
            },
            prevPage(){
                this.pageNumber--;
            },

        },

        computed:{
            pageCount(){
                let l = this.forecasts.length,
                    s = this.size;
                return Math.floor(l/s);
            },
            paginatedData(){
                const start = this.pageNumber * this.size,
                    end = start + this.size;
                return this.forecasts
                    .slice(start, end);
            }
        },

        });

</script>
</body>
</html>