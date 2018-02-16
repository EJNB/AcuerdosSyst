/**
* Created by jorgito on 12/12/14.
*/


/*Clase persona para guardar los option de las personas asignada*/
function Persona(id, nombre){

    this.id = id;
    this.nombre = nombre;
}


function guardarDatosAcuerdos(){

    $('form[name=acuerdos_gestionbundle_acuerdo]').find(':input[type=text], :input[type=date], textarea').each(function(){
        var elemento = this;
        sessionStorage.setItem(elemento.id, elemento.value);
    });

    var estado = $('#acuerdos_gestionbundle_acuerdo_estado :selected').val();
    var procedencia = $('#acuerdos_gestionbundle_acuerdo_idProcedencia :selected').val();
    var autor = $('#select-autor :selected').val();
    sessionStorage.setItem('estado', estado);
    sessionStorage.setItem('procedencia', procedencia);
    sessionStorage.setItem('autor', autor);

    var responsables = [];
    var ejecutores = [];

    $('#select-responsables-asignados option').each(function(){
        responsables.push(new Persona($(this).val(), $(this).text()));

    });
    $('#select-ejecutores-asignados option').each(function(){
        ejecutores.push(new Persona($(this).val(), $(this).text()));
    });

    sessionStorage.setItem('responsables', JSON.stringify(responsables));
    sessionStorage.setItem('ejecutores', JSON.stringify(ejecutores));

}

function loadDatosAcuerdo(){

    if(sessionStorage.estado){
        $('form[name=acuerdos_gestionbundle_acuerdo]').find(':input[type=text], :input[type=date], textarea').each(function(){
            this.value = sessionStorage.getItem(this.id);
        });

        $('#acuerdos_gestionbundle_acuerdo_estado option[value= "' + sessionStorage.getItem('estado') + '"]').attr('selected', 'selected');
        $('#acuerdos_gestionbundle_acuerdo_idProcedencia option[value= "' + sessionStorage.getItem('procedencia') + '"]').attr('selected', 'selected');
        $('#select-autor option[value= "' + sessionStorage.getItem('autor') + '"]').attr('selected', 'selected');

        var responsables = JSON.parse(sessionStorage.getItem('responsables'));
        var ejecutores = JSON.parse(sessionStorage.getItem('ejecutores'));

        for (var i=0; i<responsables.length; i++){
            $('#select-responsables-asignados').append(new Option(responsables[i].nombre,responsables[i].id,true,false));
        }

        $.each(ejecutores, function(index, value){
           $('#select-ejecutores-asignados').append(new Option(value.nombre,value.id,true,false));
        });

    }
    sessionStorage.clear();

}


$(document).ready( function() {

    $('body').height($('.content').height() + 100);

    //eliminar validacion html5

    loadDatosAcuerdo();

    $('#add-persona-acuerdo').click( function (event){
        guardarDatosAcuerdos();
    });

    $(".link-eliminar").click( function( event ) {
        $("#link-popup-eliminar").attr("href", $(this).attr("data-url"));
        $("#text-descripcion-popup-eliminar").html($(this).attr("data-descripcion"));
    });

    $("#admin-page-list").change( function() {
        var url = $(this).attr('data-url');
        url = url.replace('ppage', $(this).val());

        window.location.href = url;
    });

    $("#admin-search-button").click( function() {
        $("#admin-search-form").submit();
    });


    $("#btn-plus-resp").click( function( event ){

        var valor = $("#select-responsables-disponibles").val();
        var text = $("#select-responsables-disponibles option:selected").text();

        if(valor!=null && !$("#select-responsables-asignados option[value='" + valor +"']").val()){
            $('#select-responsables-asignados').append(new Option(text,valor,true,false));
        }
        else{
            alert("Por favor seleccione un elemento valido");
        }

    });

    $("#btn-minus-resp").click( function( event ){

        var valor = $("#select-responsables-asignados").val();
        var text = $("#select-responsables-asignados option:selected").text();

        if(valor!=null){
            $("#select-responsables-asignados option[value='" + valor +"']").remove()
        }
        else{
            alert("Por favor seleccione un elemento");
        }
    });

    $("#btn-plus-ejec").click( function( event ){

        var valor = $("#select-ejecutores-disponibles").val();
        var text = $("#select-ejecutores-disponibles option:selected").text();

        if(valor!=null && !$("#select-ejecutores-asignados option[value='" + valor +"']").val()){
            $('#select-ejecutores-asignados').append(new Option(text,valor,true,false));
        }
        else{
            alert("Por favor seleccione un elemento");
        }
    });

    $("#btn-minus-ejec").click( function( event ){

        var valor = $("#select-ejecutores-asignados").val();
        var text = $("#select-ejecutores-asignados option:selected").text();

        if(valor!=null){
            $("#select-ejecutores-asignados option[value='" + valor +"']").remove()
        }
        else{
            alert("Por favor seleccione un elemento");
        }
    });

    $('#btn-enviar').click( function( event) {

        $("#select-responsables-asignados").each(function(){
            $("#select-responsables-asignados option").prop('selected', true);
        });
        $("#select-ejecutores-asignados").each(function(){
            $("#select-ejecutores-asignados option").prop('selected', true);
        });
        $("#btn-send").click();

    });

    $('#select-proc').change( function( event ){
        var url = $(this).attr('data-url');
        url = url.replace('pidprocedencia', $(this).val());
        window.location.href = url;
    });

    $('#datetimepicker-add-cump-real').datetimepicker({
        format: "YYYY-MM-DD",
        locale: 'es',
        inline: true,
        showClear: true
    });

    $(".link-fecha-cump-real").click( function( event ) {
        $("#fecha-cump-real").val( $('#datetimepicker1').val());
        $("#form-fecha-cump-real").attr("action", $(this).attr("data-url"));
    });

    $('#acuerdos_gestionbundle_acuerdo_fechaCreacion').datetimepicker({
        format: "YYYY-MM-DD",
        locale: 'es'
    });
    $('#acuerdos_gestionbundle_acuerdo_fechaCumplimientoIndicada').datetimepicker({
        format: "YYYY-MM-DD",
        locale: 'es'
    });

//    $('#panel-filtro').hide();
    $('#sign-minus').show();
    $('#sign-plus').hide();

    $('#label-filtro').click( function( event ){
        $('#panel-filtro').toggle('fast');
        $('#sign-minus').toggle();
        $('#sign-plus').toggle();
    });

    $('#find-btn').click( function( event ){
        $('#form-report-find').submit();
    });

    $('#ind-date-start-find').datetimepicker({
        format: "YYYY-MM-DD",
        locale: 'es',
        showClear: true,
        defaultDate: ''
    });
    $('#ind-date-end-find').datetimepicker({
        format: "YYYY-MM-DD",
        locale: 'es',
        showClear: true
    });
    $('#real-date-start-find').datetimepicker({
        format: "YYYY-MM-DD",
        locale: 'es',
        showClear: true
    });
    $('#real-date-end-find').datetimepicker({
        format: "YYYY-MM-DD",
        locale: 'es',
        showClear: true
    });

    $('.link-tooltip').tooltip();


    $('#link-popup-add-persona').click( function( event ){

        var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
        //Se utiliza la funcion test() nativa de JavaScript
        if (regex.test($('#popup-persona-email').val().trim())) {
            $("#form-persona-modal").attr('action', $(this).attr('data-url'));
            $("#submit-persona").click();
        }
        else
        {alert('Introduzca una direccion de correo valida');}
    });

    $('#acuerdos_seguridadbundle_user_roles').attr('class', 'switch');
    $('.link-tooltip').tooltip();

    $(".link-changepass").click( function( event ) {
        $('#form-changepass').attr("action", $(this).attr("data-url"));
    });
    $("#link-popup-changepass").click( function( event ){
        if($('#password').val() != "" && $('#password').val() == $('#repeat-password').val()) {
            $('#form-changepass').submit();
        }
        else{
            $('#alert-password-match').attr('hidden',false);
        }
    });

    $(".link-changepass-user").click( function( event ) {
        $('#form-changepass-user').attr("action", $(this).attr("data-url"));
    });
    $("#link-popup-changepass-user").click( function( event ){
        if($('#previous_password').val() != "" && $('#password_user').val() != "" && $('#password_user').val() == $('#repeat_password_user').val()) {
            $('#form-changepass-user').submit();
        }
        else{
            $('#alert-password-match-user').attr('hidden',false);
        }
    });

    $('#modal-alert-changepass').modal();
    $('#modal-alert-changepass').click( function (event){
        $(this).modal('hide');
    });

    $("#select-all").change(function(event){

       if($(this).is(':checked')){
           $(".select-item").prop('checked',true);
       }
        else{
           $(".select-item").prop('checked',false);
       }
    });

    $("input:checkbox").change(function(event){

        var cant = $(".select-item:checked").length;
        $("#cant-send").text("(" + cant + ")");
        $("#cant-print").text("(" + cant + ")");
    });

    var cant_select = $(".select-item:checked").length;
    $("#cant-send").text("(" + cant_select + ")");
    $("#cant-print").text("(" + cant_select + ")");

    $("#send-button").click( function( event ) {
        $("#textarea-msg").val("");
        $("#form-group-action").attr('action', $(this).attr('data-url'))
    });

    $("#link-popup-send").click( function(event){
        $("#text-msg").val($("#textarea-msg").val());
        $("#form-group-action").submit();
    });

    $("#print-button").click( function( event ) {
        $("#form-group-action").attr('action', $(this).attr('data-url'))
        $("#form-group-action").submit();
    });

    $("#link-ac-pers").click( function( event) {
       $("#form-ac-pers").submit();
    });

    //$('textarea').prop('required', false);



});


