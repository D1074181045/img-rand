function htmlToElements(html) {
	var template = document.createElement('template');
	template.innerHTML = html;
	return template.content;
}

var generate_url;

document.getElementById("url_add")
.addEventListener('click', (e) => {
	let io_url = document.getElementById("io_url");
	if (!io_url.value) return;
	
	let url_list = document.getElementById("url_list");
	url_list.append(htmlToElements(
		'<div style="display: flex;margin-bottom: 5px;">' + 
		'<input class="form-control" autocomplete="off" name="img_url" type="text"/>' + 
		'<button class="btn btn-primary" style="margin-left:5px">-</button></div>'
	))
	
	let url_length = url_list.children.length - 1;
	let div = url_list.children[url_length];
	
	div.children[0].value = io_url.value;
	div.children[1].addEventListener('click', ({target}) => {
		target.parentNode.remove();
	});
	
	io_url.value = '';
})

document.getElementById("generate")
.addEventListener('click', (e) => {
	const img_url_array = [...document.querySelectorAll("input[name='img_url']")];

	if (!img_url_array.length) return;
	
	let query = img_url_array.map(({value}) => {
		return 'img=' + value;
	}).join('&');
	query = 'show.php?' + query;
	
	generate_url = new URL(query, location.origin).href;
	
	document.getElementById("url").value = generate_url;
})

document.getElementById("copy_url")
.addEventListener('click', (e) => {
	if (!generate_url) return;
	
	if (!navigator.clipboard) {
		document.getElementById('url').select();
		document.execCommand('copy');
		e.target.innerHTML = "複製成功";
		e.target.classList.remove('btn-primary');
		e.target.classList.add('btn-success');
	} else {
		navigator.clipboard.writeText(generate_url)
		.then(() => {
			e.target.innerHTML = "複製成功";
			e.target.classList.remove('btn-primary');
			e.target.classList.add('btn-success');
		}).catch(() => {
			e.target.innerHTML = "複製失敗";
			e.target.classList.remove('btn-primary');
			e.target.classList.add('btn-danger');
		});
	}
	
	if (this._tt) clearTimeout(this._tt);
	
	this._tt = setTimeout(() => {
		e.target.innerHTML = "複製";
		e.target.classList.remove('btn-success');
		e.target.classList.add('btn-primary');
	}, 1500)
})