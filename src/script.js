
var api = 'http://teste.localhost/api';

var disableSelects = function(selects){
	selects.each(function(){
		$disabledChildren = $(this).children(':disabled');
		$(this).html('').append($disabledChildren).val("").attr('read-only', 'read-only');
	});
};

var errors = {};
function updateErrors() {
	$("[id*='helper-']").each(function (index, element) {
		var $this = $(this),
			$parent = $( $this.parent() ),
			$input = $this.prev(),
			id = $this.attr('id'),
			key = id.replace(/^helper\-(.*)$/, '$1');

		if ( errors[key] ) {
			$input
				.addClass('is-invalid')
				.removeClass('is-valid');

			$this
				.show()
				.text(errors[key] || 'Erro de formulário');
		}
		else if ($input.is('.is-valid, .is-invalid') || errors[key] === null) {
			$input
				.addClass('is-valid')
				.removeClass('is-invalid');

			$this.hide();
		}
	});
}

function validate(event) {
	var $this = $(this),
		id = $this.attr('id'),
		key = id.replace(/^input\-(.*)$/, '$1'),
		value = $this.val();

	if ( $this.is(':invalid, .force-invalid') ) {
		if ( value == "" ) {
			errors[key] = "Campo '" + key + "' é obrigatório!";
		}
		else {
			errors[key] = "Este campo é inválido!";
		}
	}
	else {
		delete errors[key];
	}

	updateErrors();
	console.log(errors);
}

$(document).ready(function(){

	// Colocando as validações em todos os inputs e selects
	$('select, input:not([type="checkbox"])').change(validate).blur(validate);

	$('#input-email').blur(function (event) {
		var $this = $(this),
			email = $this.val();

		$this.removeClass('force-invalid');

		if ( $this.is(':valid') ) {
			$.ajax({
				url: api + '/cadastro/validate/email',
				method: 'POST',
				data: {
					'email': email
				},
				dataType: 'JSON',
				success: function(data){
					if ( !data.success ) {
						bootbox.alert(data.errorMessage);
						$this.addClass('force-invalid');
						errors.email = data.errorMessage;
					}
					else {
						$this.removeClass('force-invalid');
						(validate.bind($this))();
					}
					updateErrors();
				}
			});
		}
	});

	$('#input-telefone').blur(function (event) {
		var $this = $(this),
			telefone = $this.val().replace(/[^0-9]/, '');

		$this.removeClass('force-invalid');

		if ( $this.is(':valid') ) {
			$.ajax({
				url: api + '/cadastro/validate/telefone',
				method: 'POST',
				data: {
					'telefone': telefone
				},
				dataType: 'JSON',
				success: function(data){
					if ( !data.success ) {
						bootbox.alert(data.errorMessage);
						$this.addClass('force-invalid');
						errors.telefone = data.errorMessage;
					}
					else {
						$this.removeClass('force-invalid');
						(validate.bind($this))();
					}
					updateErrors();
				}
			});
		}
	});

	$.ajax({
		url: api + '/estados',
		method: 'GET',
		dataType: 'JSON',
		success: function(data){

			console.log('retornou:' + data);

			var selectHtml =
					'<option disabled selected> Selecione o Estado </option>' +
					data.map(
						function(estado){
							return '<option value="' + estado.uf + '">' +( estado.uf.toUpperCase() + ' - ' + estado.nome) + '</option>';
						}
					).join('');

			$('#input-estado').html(selectHtml).attr('read-only', null);

		}
	});

	$('#input-estado').change(function(){
		var $this = $(this),
			uf = $this.val();

		disableSelects( $this.nextAll('select.referencied') );

		$.ajax({
			url: api + '/' + uf.toLowerCase() + '/cidades',
			method: 'GET',
			dataType: 'JSON',
			success: function(data){

				$nextSelect = $('#input-cidade');

				$disabledChildren = $( $nextSelect.children(':disabled') );
				//console.log($disabledChildren.html());

				var selectHtml =
						data.map(
							function(cidade){
								return '<option value="' + cidade.id + '">' + cidade.nome + '</option>';
							}
						).join('');

				$nextSelect
					.html(selectHtml)
					.prepend($disabledChildren)
					.attr('read-only', null);

			}
		})
	});

	$('#input-cidade').change(function(){
		var $this = $(this),
			cid = $this.val();

		disableSelects( $this.nextAll('select.referencied') );

		$.ajax({
			url: api + '/cidade/' + cid.toLowerCase() + '/escolas',
			method: 'GET',
			dataType: 'JSON',
			success: function(data){

				$nextSelect = $('#input-escola');

				$disabledChildren = $( $nextSelect.children(':disabled') );
				//console.log($disabledChildren.html());

				var selectHtml =
						data.map(
							function(escola){
								return '<option value="' + escola.id + '">' + escola.nome + '</option>';
							}
						).join('');

				$nextSelect
					.html(selectHtml)
					.prepend($disabledChildren)
					.attr('read-only', null);

			}
		});
	});

    $('#enviar').click( function( event ) {

		var $form = $('#form'),
			$inputs = $form.find(':input:not([type="checkbox"]):not(button)'),
		 	serializedValues = $('#form').serializeArray(),
			values = {};

		$inputs.each(validate);

		if ( Object.keys( errors ).length != 0 ) {
			bootbox.alert('Alguns campos estão incompletos, por favor, preencha adequadamente.');
			return;
		}

		serializedValues.forEach(function(input){
			if ( values[input.name] !== undefined ) {
				if ( Array.isArray(values[input.name]) ) {
					values[input.name].push(encodeURI(input.value));
				}
				else {
					values[input.name] = [ values[input.name], input.value ];
				}
			}
			else {
				values[input.name] = input.value;
			}
		});

  	$.ajax({
          'url': api + '/cadastro/enviar',
          'method': 'post',
          'data': values,
          'dataType': 'json',
          'success' : function () {
              bootbox.alert("Seu cadastro foi efetuado com sucesso. Obrigado");
              $('input:not([type="button"])').each(function () {
                  $(this).val('');
              });
          }
      });
  });
});

$(document).on('click', 'select.referencied[read-only]', function(){
	$(this).focusout();
	var infoError = $(this).data('error-referencied');
	bootbox.alert( infoError );
	console.log("Erro", infoError);
});
