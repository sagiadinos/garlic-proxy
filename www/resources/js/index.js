function modifyList(uuid)
{
	var tr          = document.getElementById('uuid_'+uuid);
	tr.style.color = "green";
	var td          = tr.getElementsByClassName('last_update');
	td[0].innerText = "updated";
}

function setListError(uuid)
{
	var tr          = document.getElementById('uuid_'+uuid);
	tr.style.color = "red";
}

function refreshIndex(uuid)
{
	var request_url = 'async.php?site=get_index&uuid='+uuid;
	var MyRequest   = new XMLHttpRequest(); // a new request
	MyRequest.open("GET", request_url, true);
	MyRequest.onload = function (e)
	{
		if (MyRequest.readyState === 4)
		{
			if (MyRequest.status === 200)
			{
				modifyList(uuid);
			}
			else
			{
				setListError(uuid);
				console.error(MyRequest.statusText);
			}
		}
	};
	MyRequest.onerror = function (e)
	{
		setListError(uuid);
		console.error(MyRequest.statusText);
	};
	MyRequest.send(null);

}