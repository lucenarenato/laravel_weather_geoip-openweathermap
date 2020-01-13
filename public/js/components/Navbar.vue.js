Vue.component("navbar", {

        template: '<nav class="navbar navbar-expand-lg navbar-light bg-danger mb-2"><a href="/" class="navbar-brand text-white">Laravel/Vue Challenge</a> <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">\n' +
            '    <span class="navbar-toggler-icon"></span>\n' +
            '  </button>' +
            '<div class="collapse navbar-collapse" id="navbarSupportedContent"><ul class="navbar-nav mr-auto mt-2 mt-lg-0">' +

                ' <li class="nav-item">\n' +
            '        <a class="nav-link" href="/">Vue/Laravel</a>\n' +
            '      </li>' +
            ' <li class="nav-item">\n' +
            '        <a class="nav-link" href="/weather">Regular Laravel</a>\n' +
            '      </li>' +
            '</ul></div></nav>'

    }

);