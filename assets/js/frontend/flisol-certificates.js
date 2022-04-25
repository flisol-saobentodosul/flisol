/* global jQuery, flisolCertificatesParams */
( function( $ ) {
	const certSearch = $( '#formulario' );

	const form = certSearch.find( 'form' );

	form.submit( function( event ) {
		event.preventDefault();

		const data = {
			action: 'flisol_get_certificates',
			cpf: certSearch.find( '#cpf' ).val(),
		};

		$.ajax( {
			type: 'POST',
			url: flisolCertificatesParams.ajaxUrl,
			data,
			beforeSend() {
				$( '#resultado' ).empty();
				document.getElementById( 'carregando' ).classList.remove( 'd-none' );
				document.getElementById( 'ajax-no-result' ).classList.add( 'd-none' );
				document.getElementById( 'ajax-resultado-encontrado' ).classList.add( 'd-none' );
			},
			success( response ) {
				document.getElementById( 'carregando' ).classList.add( 'd-none' );
				document.getElementById( 'ajax-resultado-encontrado' ).classList.remove( 'd-none' );

				if ( ! $.trim( response ) ) {
					document.getElementById( 'carregando' ).classList.add( 'd-none' );
					document.getElementById( 'ajax-no-result' ).classList.remove( 'd-none' );
					document.getElementById( 'ajax-resultado-encontrado' ).classList.add( 'd-none' );

					$( 'html, body' ).animate( {
						scrollTop: $( '#ajax-no-result' ).offset().top,
					}, 2000 );
				} else {
				/**Nome da Pessoa */
					document.getElementById( 'certificado_nome' ).innerHTML = response.name;
					let html = '';

					response.certificates.forEach( function ( certificate ) {
						html +=

						`<div class="resultado__item">
							<div class="resultado__item-ano">
								<img src="${ flisolCertificatesParams.calendarIcon }" alt="Calendario do ano ${ certificate.year }" width="60" height="60">
								<h4>${ certificate.year }</h4>
							</div>
							<div class="resultado__item-tipo">
								<strong>${ certificate.type }</strong>
							</div>
							<div class="resultado__item-link">
								<a target="_blank" href="${ certificate.url }">Ver Certificado</a>
							</div>
						</div>`;
					} );

					document.getElementById( 'resultado' ).insertAdjacentHTML( 'beforeend', html );
				}

				$( 'html, body' ).animate( {
					scrollTop: $( '#ajax-resultado-encontrado' ).offset().top,
				}, 2000 );
			},
			error() {
				document.getElementById( 'carregando' ).classList.add( 'd-none' );
				document.getElementById( 'ajax-no-result' ).classList.remove( 'd-none' );
				document.getElementById( 'ajax-resultado-encontrado' ).classList.add( 'd-none' );

				$( 'html, body' ).animate( {
					scrollTop: $( '#ajax-no-result' ).offset().top,
				}, 2000 );
			},
		} );
	} );
}( jQuery ) );
