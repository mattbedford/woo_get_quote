/*Loaded when we are on the page containing the quote form*/
 
// On page load, grab list of items in quote
document.addEventListener('DOMContentLoaded', async function() {
    const initialState = await handleApi(null, 'retrievefull');
	const contents = await initialState.json();
	handleHtml(contents);
}, false);



// Decide which html to show based on api content
function handleHtml(res) {
	let html = null;
	if (res == undefined || res.length <= 0) {
		html = `<p>Non hai inserito nessun prodotto nel tuo preventivo. Aggiungi dei prodotti per richiedere un preventivo più preciso.</p><a href='/prodotti' class='outline-btn'>Prodotti</a>`;
	}
	if (res.length > 0) {
		html = showQuoteProducts(res);
	}
	
	const div = document.querySelector(".quote-wrapper-inner");
	div.innerHTML = html;
	listenForButtons(); // In other script
}


// Print html of quoted products list
function showQuoteProducts(res) {
	let prodHtml = '';
	
	if(res.length > 0) {
		prodHtml += `<button class='wgq-command clearAll' data-command='clearall'>Elimina tutti prodotti dal preventivo</button>`
	}
	
	res.forEach(el => {
		prodHtml += `
			<div class='single-quote-product' id='quote-prod-${el.id}'>
				<div class='quote-item-image'><img src='${el.image}'></div>
				<div class='quote-item-texts'>
					<span><strong>ID:</strong> ${el.id}</span>
					<h3>${el.name}</h3>
					<p>${el.desc}</p>
				</div>
				<div class='quote-item-quantity'>
					<button class='wgq-command add' data-id='${el.id}' data-command='add'>+</button>
					<span class='quantity' data-id='${el.id}'>${el.quantity}</span>
					<button class='wgq-command remove' data-id='${el.id}' data-command='remove'>-</button>
					<span class='wgq-command clearOne' data-id='${el.id}' data-command='clearone'>&#x2716; Rimuovi</span>
				</div>
			</div>
	
		`
	})
	
	if(res.length > 0) {
		
		let title1 = 'Prodotto';
		let title2 = 'Quantità';
		
		prodHtml += `
			<div class='quote-table'>
				<div></div>
				<div><h4>${title1}</h4></div>
				<div><h4>${title2}</h4></div>
			</div>`
	}
	return prodHtml;
	
}



// Set up generic API handler 
async function handleApi(productId = null, action) {
	let url = `${rest_obj.url}${action}`;
	let data = {
		'product_id': productId
	};
	let res = fetch(url, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json',
			'X-WP-Nonce': rest_obj.security
		},
		body: JSON.stringify(data)
	})
	return res;
};

