<!DOCTYPE html>
<html lang='es'>

<head>
    <meta charset='utf-8' />
    <script src="{{ asset('js/fullcalendar.js') }}"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var data = @json($eventos);
            console.log(data);
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                selectable: true,
                headerToolbar: {
                    left: 'prev,next',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay' // user can switch between the two
                },

                dateClick: function(info) {
                    //alert('Clicked on: ' + info.dateStr);
                    document.getElementById('fecha_fin').min = info.dateStr;
                    $('#modalEvento').modal('show');
                    eventosDia(info.dateStr);
                    areas();
                },
                eventClick: function(info) {
                    editarEvento(info.event.extendedProps);
                }
            });
            calendar.render();

            data.forEach(element => {
                var evento = calendar.addEvent({
                    id: element['id'],
                    title: element['nombre'],
                    start: element['fecha_inicio'] + "T" + element['hora_inicio'],
                    end: element['fecha_fin'] + "T" + element['hora_inicio'],
                    extendedProps: {
                        id: element['id'],
                    }
                })
            });
        });
    </script>
</head>

<body>
    <div>
        <div id='calendar' class="p-3" style="max-height: 90vh"></div>
    </div>

    <div class="modal fade" id="modalEvento" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Crear evento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="contenido">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    @if (Auth::check())
                        <button class="btn btn-primary" data-bs-target="#exampleModalToggle2"
                            data-bs-toggle="modal">Crear
                            evento</button>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModalToggle2" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2"
        tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="tituloModal"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalBody">
                    <form method="post" id="createEvent">
                        @csrf
                        <div>
                            <label for="">Título</label>
                            <input class="form-control" type="text" name="titulo">
                        </div>
                        <div>
                            <label for="">Descripción</label>
                            <textarea name="descripcion" id="descripcion" class="form-control"></textarea>
                        </div>
                        <div>
                            <h6>Inicio</h6>
                            <div>
                                <label for="">Hora Inicio</label>
                                <input class="form-control" type="time" name="hora_inicio" id="hora_inicio">
                            </div>
                            <h6>Fin</h6>
                            <div>
                                <label for="">Fecha fin</label>
                                <input class="form-control" type="date" name="fecha_fin[]" id="fecha_fin">
                            </div>

                            <div>
                                <label for="">Hora Fin</label>
                                <input class="form-control" type="time" name="fecha_fin[]" id="hora_fin">
                            </div>
                            <hr>
                        </div>
                        <div id="areas">
                        </div>
                        <div>
                            <label for="">Notas CTA</label>
                            <textarea name="notas_cta" id="notas_cta" class="form-control"></textarea>
                        </div>
                        <div>
                            <label for="">Notas Servicio Generales</label>
                            <textarea name="notas_generales" id="notas_generales" class="form-control"></textarea>
                        </div>
                        <div class="text-end">
                            <span class="btn btn-success mt-1 btn-sm " onclick="guardarEvento()">
                                Guardar
                            </span>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" data-bs-target="#modalEvento" data-bs-toggle="modal">Regresar</button>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
        integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"
        integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous">
    </script>

    <script src="{{ asset('vendor/sweetalert/sweetalert.all.js') }}"></script>

    <script>
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            showCloseButton: true
        });

        var diaSeleccionado = Date.now();
        async function eventosDia(date) {
            diaSeleccionado = date;
            let url = "{{ route('eventos.dia', ':id') }}";
            url = url.replace(':id', date);
            try {
                const response = await fetch(url);
                if (!response.ok) {
                    throw new Error(`Response status: ${response.status}`);
                }
                const html = await response.text();
                document.getElementById('contenido').innerHTML = html;
            } catch (error) {
                console.error(error.message);
            }
        }

        async function areas() {
            let url = "{{ route('areas.index') }}";
            try {
                const response = await fetch(url);
                if (!response.ok) {
                    throw new Error(`Response status: ${response.status}`);
                }
                const html = await response.text();
                document.getElementById('areas').innerHTML = html;
            } catch (error) {
                console.error(error.message);
            }
        }

        function guardarEvento() {
            console.log(diaSeleccionado);
            let url = "{{ route('eventos.store', ':id') }}";
            url = url.replace(':id', diaSeleccionado);
            $.ajax({
                url: url,
                method: 'POST',
                data: $('#createEvent').serialize(),
            }).done(function(data) {
                if (data.success === true) {

                    Toast.fire({
                        type: 'success',
                        title: data.message,
                        icon: "success"
                    });
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    Toast.fire({
                        type: 'error',
                        title: data.message,
                        icon: "error"
                    });
                }
            });
        }

        async function editarEvento(id) {
            let url = "{{ route('eventos.show', ':id') }}";
            url = url.replace(':id', id.id);
            try {
                const response = await fetch(url);
                if (!response.ok) {
                    throw new Error(`Response status: ${response.status}`);
                }
                const evento = await response.text();
                console.log(evento);
                document.getElementById('contenido').innerHTML = evento;
                $('#modalEvento').modal('show');
            } catch (error) {
                console.error(error.message);
            }
        }
    </script>
</body>

</html>
