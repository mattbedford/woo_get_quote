// Only for preventivo page on front end
function listenForButtons() {
	let wgqButtons = document.querySelectorAll('.wgq-command');
	wgqButtons.forEach(el => {
		el.addEventListener('click', function(elem) {
			elem.preventDefault();
			handleUpdateRequest(elem);
		});
	});
}

async function handleUpdateRequest(event) {
	let action = event.target.dataset.command;
	let id = event.target.dataset.id;
	
	if(!id) id = null;
	
	quoteContentsUpdate(id, action);
}


async function quoteContentsUpdate(prodId, command) {
		
    const initialState = await handleApi(prodId, command);
	const contents = await initialState.json();
	updateHtml(contents);
	// TODO: function to update cf7 form hidden field. Trigger on initial state also.
	// document.querySelector(".wpcf7-form-control-wrap[data-name='preventivo'] textarea").value = "xxxxxxxxxxxxxxxx";
}


function updateHtml(res) {
	
	const quantityInputs = document.querySelectorAll('.quantity');
	res = Object.values(res);
	
	quantityInputs.forEach(function(quantityInput) {
		const product_id = quantityInput.getAttribute('data-id');
		const item = res.find(function(item) {
			return item.product_id === product_id;
		});

		if (item) {
			quantityInput.innerHTML = item.quantity;
		} else {
			let itemToRemove = document.querySelector(`#quote-prod-${product_id}`);
			itemToRemove.remove();
		}
	});
	
	if(res.length <= 0) {
		let clearButton = document.querySelector('.wgq-command.clearAll');
		clearButton.remove();
	}
}


// trigger add product
async function doAddRequest() {
	// TODO: pass from server
	let id = document.querySelector('.status-publish').getAttribute('id').replace("post-", "");
	
	const added = await handleApi(id, 'add');
	const contents = await added.json();
	return contents;
}

