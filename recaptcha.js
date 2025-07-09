for (let form of document.forms) {
	if (form.dataset.recaptchaSiteKey === undefined)
		continue;
	// console.log(form);
	// console.log(form.dataset.recaptchaSiteKey);
	form.onsubmit = event => {
		event.preventDefault();
		const form = event.target;
		grecaptcha.ready(() => {
			grecaptcha.execute(form.dataset.recaptchaSiteKey, {action: 'submit'}).then(token => {
				// console.log(token);
				form.recaptcha.value = token
				fetch(form.action, {
					body: new FormData(form),
					method: 'POST',
				}).then(response => {
					if (response.ok) {
						if (response.headers.get('content-type') === 'application/json') {
							response.json().then(json => {
								// console.log(json);
								if (json.redirect !== undefined)
									location.href = json.redirect;
							});
						} else {
							response.text().then(text => {
								alert(text);
							});
						}
					} else {
						alert(response.status)
					}
				});
			});
		});
	};
}
